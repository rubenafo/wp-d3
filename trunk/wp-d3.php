<?php
/*
Plugin Name: Wp-D3
Plugin URI: http://wordpress.org/extend/plugins/wp-d3/
Description: D3 is a very popular visualization library written in Javascript. This plugins provides a set of tags to link page/post content to d3.js libraries in order to visualize the javascript snippets inside Wordpress.
All javascript code can be added directly to the posts by means of a custom javascript code editor (Wp-D3 Chart Manager)
Version: 2.4.1
Author: Ruben Afonso
Author URI: http://www.figurebelow.com
License: GPL2
*/

include plugin_dir_path(__FILE__).'chartMethods.php';
include plugin_dir_path(__FILE__).'utils.php';
include plugin_dir_path(__FILE__).'options.php';

/* 
 * Init, add d3.js lib to the js scripts to be included. 
 */
function wordpressd3_init() {
  wp_enqueue_script ('d3', getD3LibraryInUse(), array(), '1.0.0', false);
}

/**
 * Include .js and .css resources from [d3-links] tag
 */
function include_resources ($attr, $content) {
  	$result = "";
  	$hrefs = array();
  	$dom = new DOMDocument();
  	if (!empty($content)) {
		$dom->loadHTML($content);
    	$tags = $dom->getElementsByTagName('a');
		foreach ($tags as $tag) {
       		$hrefs[] = $tag->getAttribute('href');
		}
    	foreach ($hrefs as $js) {
	  		if (substr_compare($js, "js", -strlen("js"), strlen("js")) === 0) {
	     		$result = $result . getJavaScriptInclude ($js);
			}
        	if (substr_compare($js, "css", -strlen("css"), strlen("css")) === 0) {
	     		$result = $result . getCssInclude (js);
	  		}
		}
  	}
  	return $result;
}

/**
 * Creates a div around the javascript code to visualize it.
 * Accepts the canvas="xxx" parameter to specify the canvas name. By default it is
 * created with id='canvas' (so original uh ;-))
 */
function print_source ($attr, $content) {
	extract(shortcode_atts(array('canvas' => 'canvas'), $attr));
	$chartId = $canvas;
	$field_code = get_post_meta (get_the_ID(), $chartId, true);
	$code = "";
	$result = "";
	if (!empty($field_code)) // custom field found
	{
		$jsonCode = json_decode($field_code, true);
		$code = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n\']+/", "\n", $jsonCode['code']); // remove empty lines
		foreach ($jsonCode['includes'] as $include)
		{
	  		if (substr_compare($include, "js", -strlen("js"), strlen("js")) === 0) {
	        	$result = $result . getJavaScriptInclude ($include);
			}
        	if (substr_compare($include, "css", -strlen("css"), strlen("css")) === 0) {
            	$result = $result . getCssInclude ($include);
	  		}
		}
	}
	else
	{
		$code = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n\']+/", "\n", $content); // remove empty lines
	}
	if (containsAutoIdFlag($code))
	{
		$genChartId = genRandomId ();
		$code = replaceAutoIdFlag ($code, $genChartId);
		$chartId = $genChartId;
	}
	$code = wrapAsFunction ($code, $chartId);
	$result = $result . '<div class="' . $chartId . '">' . '<script type="text/javascript">' . $code . '</script>' . '</div>';
	return $result;
}

/**
* This function avoids wptexturize from modifying the d3 code,
* just in case remove_filter() doesn't work as expected.
*/ 
function skip_d3_source($shortcodes){ 
    $shortcodes[] = 'd3-source';
    return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'skip_d3_source' );

/*
 * This function restores the ampersand that the inner wordpress behaviour changed somehow.
 * In the end, no forums nor docs seem to agree on how to fix this, but this workaround works.
 * The first ampersand code is replaced when the post is edited in Visual Mode.
 * The second ampersand code is replaced by wordpress itself.
 */
function restore_special_chars ($content)
{
  $content = preg_replace('/&#038;/','&', $content);
  $content = preg_replace('/&amp;/','&', $content);
  // This line should fix the issue when we have "<p>" inside the js code
  $content = preg_replace('/\n<p>/','<p>', $content);
  return $content;
}

add_action( 'wp_ajax_wpd3dialog_action', 'dialog');

// remove wpautop and wptexturize for a while if they were enabled

$isWpAutoEnabled = has_filter ('the_content', 'wpautop');
if ($isWpAutoEnabled) {
	remove_filter('the_content', 'wpautop' );
}

$isWpTexturizeEnabled = has_filter('the_content', 'wptexturize');
if ($isWpTexturizeEnabled) {
	remove_filter('the_content', 'wptexturize');
}

add_action('init', 'buttons_init');
add_action('init', 'wordpressd3_init');
add_shortcode("d3-link", "include_resources");
add_shortcode("d3-source", "print_source");

// and added again with less priority.
if ($isWpAutoEnabled) {
	add_filter( 'the_content', 'wpautop' , 90);
}
if ($isWpTexturizeEnabled) {
	add_filter( 'the_content', 'wptexturize' , 90);
}

/* Added with even less priority to make sure it's executed at the end
 * so the ampersands are restored.*/
add_filter ('the_content', 'restore_special_chars', 95);
?>
