<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly. ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>Wp-D3 Chart Manager</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

<script src="<?php echo plugins_url( 'wp-d3/js/jquery-2.0.3.min.js' ); ?>"></script>
<script src="<?php echo plugins_url( 'wp-d3/js/jquery-ui-1.10.3.js' ); ?>"></script>
<script src="<?php echo plugins_url( 'wp-d3/js/functions.js' ); ?>"></script>
<script src="<?php echo plugins_url( 'wp-d3/js/src-min/ace.js' ); ?>"></script>
<script src="<?php echo plugins_url( 'wp-d3/js/src-min/mode-javascript.js' ); ?>"></script>

<link rel="stylesheet" href="http://jqueryui.com/jquery-wp-content/themes/jquery/css/base.css?v=1">
<link rel="stylesheet" href="http://jqueryui.com/jquery-wp-content/themes/jqueryui.com/style.css">
<link rel="stylesheet" href="<?php echo plugins_url( 'wp-d3/css/wpd3.css' ); ?>">

<script src="<?php echo includes_url( 'js/tinymce/tiny_mce_popup.js' ); ?>" type="text/javascript"></script>

<script>

jQuery(document).ready(function($) {
  var tabs = jQuery("#tabs");
  //var postId = jQuery(parent.post_ID).val();
  jQuery("#tabs").on ("click", "button", function (event) {
    var name = $(this).attr('id');
    var fieldNumber = name.split("-")[1];
    if (name.match ("save-")) 
    {
      var editor = ace.edit("area-" +  name.split("-")[1]);
      saveTab (postId, name, editor.getSession().getValue(), $("#includes-" + fieldNumber).text());
    }
    if (name.match ("remove-"))
    {
      removeTab(postId, name);
    }
    if (name.match("insert-"))
    {
      insertRef (postId, name); 
    }
    if (name.match ("include-"))
    {
      showIncludes (postId, name);
    }
  });
  jQuery("#main-close").on ("click", "", function(event) 
  {
    tinyMCEPopup.close();
  });
  jQuery("#main-new").on ("click", "", function(event)
  {
    addNewTab(postId);
  });
});

// Global variables
var ajaxurl = <?php echo "'" + admin_url('admin-ajax.php') + "'"; ?>;
var postId = jQuery(parent.post_ID).val();
jQuery.get(
    ajaxurl,
    {
        'action': 'getCustomFielContent',
        'postId': postId,
    }, 
    function(response){
      addNewTab(postId, response);
    }
);

</script>
</head>
<body style="font-size:62.5%; ">
  <div id="tabs">
    <ul>
    </ul>
  </div>
  <button class="tabbutton" id="main-close" style="float:right">Close</button>
  <button class="tabbutton" id="main-new" style="float:right">New Tab</button>

<!-- confirmation dialog -->
<div id="dialog-confirm" title="Confirm delete" style="display:none">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0"></span>
Delete selected chart?</p>
</div>
<!-- end confirmation dialog -->

<!-- include table -->
<div id="users-contain" class="ui-widget" style="display:none;" title="Include URL resources">
  <table id="users" class="ui-widget ui-widget-content">
  <thead>
    <tr class="ui-widget-header ">
      <th>Url</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <tr>

  </tr>
  </tbody>
  </table>
  
  <button id="create-user" class="tabbutton" style="margin: 5px">Add new URL</button>
</div>
<!-- end include table -->

<!-- add url dialog -->
<div id="dialog-form" title="Add URL" style="display:none">
  <form>
    <fieldset>
      <label for="url" >URL</label>
      <input type="text" style="width:100%" name="url" id="url" class="text ui-widget-content ui-corner-all">
    </fieldset>
  </form>
</div>
<!-- end add url dialog -->

<!-- save confirmation -->
<div id="save-ok-dialog" title="Chart Saved" style="display:none">
<p>
<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 4px 0;"></span>
Chart content saved.
</p>
</div>
<!-- end save confirmation -->

<!-- delete confirmation -->
<div id="delete-ok-dialog" title="Chart Deleted" style="display:none">
<p>
<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 4px 0;"></span>
Chart content deleted.
</p>
</div>
<!-- end save confirmation -->

<!-- confirmation dialog -->
<div id="close-dialog-confirm" title="Unsaved Changes" style="display:none">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0"></span>
Exit and discard changes?</p>
</div>
<!-- end confirmation dialog -->

</body>
</html>
