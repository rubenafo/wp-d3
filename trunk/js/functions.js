
function getFieldName (postId, fieldId)
{
  return "wpd3-" + postId + "-" + fieldId; 
}

function extractFieldNumber(fieldName)
{
  if (fieldName)
  {
    return eval(fieldName.substr(fieldName.lastIndexOf('-')+1));
  }
  return -1;
}

function strMatchesWpField (str)
{
  return (str.match(/^wpd3-/));
}

/*
 * Returns the number of tabs in the editor
 */
function getNumberTabs ()
{
  var tabs = $('.ui-tabs-anchor');
  if (tabs)
  {
    return tabs.length;
  }
  return 0;
}

/*
 * Returns the next valid fieldNumber not present in 
 * the already inserted tabs
 */
function getNextValidFieldNumber ()
{
  var max = 0;
  var tabs = $('.ui-tabs-anchor');
  for (var i = 0; i < tabs.length; i++) {
    var id = tabs[i].text;
    if (strMatchesWpField(id)) {
      var field = extractFieldNumber(id);
      if (field >= max)
      {
        max = field + 1;
      }
    }
  }
  return max;
}

/*
 * Adds a new tab.
 * Context: {keys, contents}
 */
function addNewTab(postId, context) 
{
  var tabs = $("#tabs").tabs({
      collapsible: false,
      active: true
    });
  if (context) {
    var fieldKeys = context.keys;
    var fieldContents = context.contents;
    
    var fieldNumber = -1;
    if (context) { // loading saved fields
      if (fieldKeys.length == 0)
      {
        fieldNumber = getNextValidFieldNumber ();
        appendTabCode (postId, tabs, fieldNumber);
        tabs.tabs ({active: getNumberTabs() - 1});
      }
      else {
        for (var i = 0; i < fieldKeys.length; i++) {
          fieldNumber = extractFieldNumber (fieldKeys[i]);
          appendTabCode (postId, tabs, fieldNumber, fieldContents[i]);
          tabs.tabs ({active: 0});
        }
      }
    }
  }
  else { // adding a new user-requested tab
    fieldNumber = getNextValidFieldNumber ();
    appendTabCode (postId, tabs, fieldNumber);
    tabs.tabs ({active: getNumberTabs() - 1});
  }
}

function appendTabCode (postId, tabs, fieldNumber, fieldContent)
{
  var template = "<li><a href=\"#tabs-" + fieldNumber + "\"" + ">" + getFieldName(postId, fieldNumber) + "</a>" + "</li>";
  tabs.find (".ui-tabs-nav").append(template);
  tabs.append("<div id='tabs-" + fieldNumber + "'style=\"width:100%;resize:none\">"+
              "<div id=\"includes-" + fieldNumber +  "\"style=\"display:none\"></div>" +
              "<button class=\"tabbutton\" id=\"save-" + fieldNumber + "\">Save</button>" +
              "<button class=\"tabbutton\" id=\"remove-" + fieldNumber + "\">Remove</button>" +
              "<button class=\"tabbutton\" id=\"insert-" + fieldNumber + "\">Insert</button>" +
              "<button class=\"tabbutton-left\" id=\"include-" + fieldNumber + "\">Include</button>" +
              "<div id=\"area-" + fieldNumber + "\" style=\"height:300px; width:100%; min-height:280px;resize:none \">" + "" + 
              "</div>" );
  tabs.tabs ("refresh");
  var editor = ace.edit("area-" + fieldNumber);
  if (fieldContent) { // loading saved chart
    var obj = JSON.parse(fieldContent);
    if (obj.code) {
      editor.getSession().setValue (obj.code);
    }
    if (obj.includes) {
      var str = "";
      var includeList = obj.includes;
      includeList.forEach (function (elem) {
          str = str + ";" + elem;
      });
      $("#includes-" + fieldNumber).text(str);
    }
  }
  editor.setTheme("ace/theme/eclipse");
  editor.getSession().setMode("ace/mode/javascript");
}

function saveTab (postId, name, code, includeList)
{
  var numberId = name.split("-")[1];
  var includeArray = [];
  includeList.split(";").forEach (function (url)
  {
    if (url)
    {
      includeArray.push (url);
    } 
  });
  jQuery.post(
      ajaxurl,
      {
          'action': 'setCustomField',
          'postId': postId,
          'fieldId': getFieldName(postId, numberId),
          'content': JSON.stringify({"includes": includeArray, "code":code})
      }
  )
  .done(function () {
      $("#save-ok-dialog").dialog({
        modal: true,
        buttons: {
          Ok: function() {
            $( this ).dialog( "close" );
          }
        }
      });
      $("#save-ok-dialog").dialog('option', 'title', "WpD3-" + numberId);
  });
}

function insertRef (postId, name)
{
  var fieldId = name.split("-")[1];
  var strToSrc = "[d3-source canvas=\"" + getFieldName(postId, fieldId) +"\"]";
  tinyMCEPopup.execCommand('mceReplaceContent', false, strToSrc);
}

function removeTab (postId, name)
{
  var tabs = $("#tabs").tabs();
  var numberId = name.split("-")[1]
  $("#dialog-confirm" ).dialog({
    resizable: false,
    height:140,
    modal: true,
    buttons: {
    Ok: function() {
      $( this ).dialog( "close" );
      jQuery.post(
          ajaxurl,
          {
              'action': 'deleteCustomField',
              'postId': postId,
              'fieldId': getFieldName(postId, numberId)
          },
          function (data, textStatus, jqXHR) {
            $("#delete-ok-dialog").dialog({
              modal: true,
              buttons: {
                Ok: function() {
                  $( this ).dialog( "close" );
                }
              }
            });
          $("#delete-ok-dialog").dialog('option', 'title', "WpD3-" + numberId);
      });
      var selected = eval(tabs.tabs('option','active'));
      $('#tabs-' + numberId).remove();
      selected++;
      $('#ui-id-' + selected).closest("li").remove().attr("aria-controls");
      $('#tabs').tabs({active: selected-2});
      tabs.tabs("refresh");
    },
    Cancel: function() {
      $( this ).dialog( "close" );
    }
    }
  });
}

function showIncludes (postId, name)
{
  var numberId = name.split("-")[1];
  $("#users > tbody").empty();
  var string = $("#includes-" + numberId).text();
  string.split(";").forEach (function (urlString) {
    if (urlString)
    {
     $("#users > tbody").append ("<tr><td id=\"" + numberId +"\" class=\"user-url\">" + urlString +  
                                 "</td><td><span id=\"" + urlString +"\" class=\"ui-icon ui-icon-close\"/></td></tr>");
    }
  });
  $("#users-contain").dialog({width:600});
  $("#create-user").button().click(function() {
    $("#dialog-form").dialog({
      buttons: {
        "Add URL": function()  {
          var newUrl = $("#url").val();
          var string = $("#includes-" + numberId).text();
          $("#includes-" + numberId).text(string + ";" + newUrl);
          $("#users > tbody").append ("<tr><td id=\"" + numberId +"\" class=\"user-url\">" + newUrl + 
            "</td><td><span id=\"" + newUrl + "\" class=\"ui-icon ui-icon-close\"/></td></tr>");
          $(this).dialog("close");
        },
        Cancel: function() {  jQuery(this).dialog("close");}
      },
    });
  });
  $(document.body).on("click","#users > tbody > tr > td > span",function () {
    var oldText = $("#includes-" + numberId).text();
    oldText = oldText.replace($(this).attr('id'), "");
    $("#includes-" + numberId).text(oldText);
    $(this).parent().parent().remove();
  });
}