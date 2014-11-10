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
	
	// delete and add because using update_post_meta
	// makes the custom field values at post override the
	// values stored previously
	delete_post_meta($postId, $fieldId);
	add_post_meta($postId, $fieldId, $content);
	exit();
}

add_action ('wp_ajax_deleteCustomField', 'deleteCustomField');
function deleteCustomField ()
{
	$postId = $_REQUEST["postId"];
	$fieldId = $_REQUEST["fieldId"];
	delete_post_meta($postId, $fieldId);
}

add_action ('wp_ajax_getValidFieldNumber', 'getValidFieldNumber');
function getValidFieldNumber ()
{
	$postId = $_REQUEST["postId"];
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
	exit();
}
?>
