<?php

/**
 * Add custom buttons in TinyMCE.
 */
function register_buttons( $buttons ) 
{
  array_push( $buttons, '|', 'wpd3' );
  return $buttons;
}

/**
 * Register button scripts.
 */
function add_buttons( $plugin_array ) 
{
  $plugin_array['wpd3'] = plugins_url('tinymce/wpd3mce.js' , __FILE__);
  return $plugin_array;
}

/**
 * Register buttons in init.
 */
function buttons_init() 
{
  if ( ! current_user_can( 'edit_posts' ) &&  !current_user_can( 'edit_pages')) 
  {
    return;
  }
  if (true == get_user_option( 'rich_editing')) {
    add_filter( 'mce_external_plugins', 'add_buttons');
    add_filter( 'mce_buttons', 'register_buttons');
  }
}

function dialog() {
    include plugin_dir_path( __FILE__ ) . 'tinymce/dialog.php';
    die();
}

add_action( 'wp_ajax_getCustomFielContent', 'getCustomFielContent');
function getCustomFielContent ()
{
	$postId = $_REQUEST["postId"];
	$val = get_post_custom_keys($postId);
	$keys = array();
	$contents = array();
	$nonceValid = check_ajax_referer('wpd3-nonce', 'security');
	if (!$nonceValid) {
		wp_send_json_error();
	}
	
	foreach ($val as $var) {
		if (preg_match("/wpd3-/", $var))
		{
      		array_push($keys, $var);
      		$values = get_post_custom_values($var, $postId);
			array_push($contents, $values[0]);
		}
	}
    $response = array(
    	'keys'=>$keys,
    	'contents'=>$contents,
    	'post'=>$postId
	);
	wp_send_json ($response);
}

add_action( 'wp_ajax_setCustomField', 'setCustomField');
function setCustomField ()
{
	$postId = $_REQUEST["postId"];
	$fieldId = $_REQUEST["fieldId"];
	$content = $_REQUEST["content"];
	$nonceValid = check_ajax_referer('wpd3-nonce', 'security');
	if (!$nonceValid) {
		wp_send_json_error(array("error" => 1));
	}

	// delete and add because using update_post_meta
	// makes the custom field values at post override the
	// values stored previously
	delete_post_meta($postId, $fieldId);
	add_post_meta($postId, $fieldId, $content);
	wp_send_json(array('error' => 0));
}

add_action ('wp_ajax_deleteCustomField', 'deleteCustomField');
function deleteCustomField ()
{
	$postId = $_REQUEST["postId"];
	$fieldId = $_REQUEST["fieldId"];
	$nonceValid = check_ajax_referer('wpd3-nonce', 'security');
	if (!$nonceValid) {
		wp_send_json_error(array("error" => 1));
	}
	delete_post_meta($postId, $fieldId);
	wp_send_json(array("error" => 0));
}

add_action ('wp_ajax_getValidFieldNumber', 'getValidFieldNumber');
function getValidFieldNumber ()
{
	$postId = $_REQUEST["postId"];
	$nonceValid = check_ajax_referer('wpd3-nonce', 'security');
	if (!$nonceValid) {
		wp_send_json_error(array("error" => 1));
	}

	$fieldNames = get_post_custom_keys($postId);
	$max = 0;
	foreach ($fieldNames as $fieldName) {
		if (preg_match("/wpd3-/", $fieldName))
		{
			$number = intVal(substr($fieldName, 5));
			if ($number >= max)
			{
				$max = $number + 1;
			}
		}
	}
	echo $max;
	wp_send_json(array("error" => 0));
}

function getD3LibraryInUse () {
	$options = get_option( 'wpd3_settings' );
	if ($options['wpd3_version'] == 2) {
		return plugins_url('wp-d3/js/d3.v43.min.js');
	}
	else {
		return plugins_url('wp-d3/js/d3.v35.min.js');
	}
}

add_action ('wp_ajax_previewContent', 'previewContent');
function previewContent ()
{
	$postId = $_REQUEST["postId"];
	$editorName = $_REQUEST["editor"];
	$nonceValid = check_ajax_referer('wpd3-nonce', 'security');
	if (!$nonceValid) {
		wp_send_json_error();
	}
	$val = get_post_custom_keys($postId);
	$keys = array();
	foreach ($val as $var) {
		if (!strcmp($editorName, $var))
		{
      		$content = get_post_custom_values($var, $postId);
      		break;
		}
	}
	$code = json_decode($content[0], true);
	$genChartId = genRandomId();
	if (!containsAutoIdFlag ($code["code"]))
	{
		$genChartId = $editorName;
	}
	$includes = $code["includes"];
	$code = replaceAutoIdFlag ($code["code"], $genChartId);
	
	$result = "";
	foreach ($includes as $include)
	{
	  	if (substr_compare($include, "js", -strlen("js"), strlen("js")) === 0) {
	        $result = $result . getJavaScriptInclude ($include);
		}
        if (substr_compare($include, "css", -strlen("css"), strlen("css")) === 0) {
            $result = $result . getCssInclude ($include);
	  	}
	}
	echo "<html><head>" 
			. getJavaScriptInclude (getD3LibraryInUse())
		 	. $result 
		 	. "</head>"
		 	. "<body><div class=\"" . $genChartId . "\"\>"
		 	. "<script type=\"text/javascript\">" . $code . "</script>"
		    . "</body></html>";
	exit();
}

?>
