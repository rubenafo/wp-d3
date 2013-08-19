<?php
/*
Plugin Name: Wordpress-d3.js
Plugin URI: http://wordpress.org/extend/plugins/wp-d3/
Description: D3 is a very popular visualization library written in Javascript. This plugins provides a set of tags to link page/post content to d3.js libraries in order to visualize the javascript snippets inside Wordpress.
Version: 1.1
Author: Ruben Afonso
Author URI: http://www.figurebelow.com
License: GPL2
*/

/* Init
 *
 */
function wordpressd3_init() {
	wp_deregister_script('wordpress-d3');
	wp_enqueue_script('wordpress-d3', true, '1.0.0');
}

/**
 * Include .js and .css resources from [d3-links] tag
 */
function include_resources ($attr, $content) {
	// add the d3 link
	echo '<script type="text/javascript" src=\'' . plugins_url('/js/d3.v3.js', __FILE__) . '\'></script>';
	$hrefs = array();
	$dom = new DOMDocument();
	if (!empty($content)) {
		$dom->loadHTML($content);
		$tags = $dom->getElementsByTagName('a');
		foreach ($tags as $tag) {
       			$hrefs[] =  $tag->getAttribute('href');
		}
		foreach ($hrefs as $js) {
	  		if (substr_compare($js, "js", -strlen("js"), strlen("js")) === 0) {
	     			echo '<script type="text/javascript" ';
	             		echo 'src=\'' . $js . '\'></script>';
			}
          		if (substr_compare($js, "css", -strlen("css"), strlen("css")) === 0) {
	     			echo '<link type="text/css" rel="Stylesheet" ';
             			echo 'href=\'' . $js . '\'/>';
	  		}
		}
	}
}

/**
 * Creates a div around the javascript code to visualize it.
 * Accepts the canvas="xxx" parameter to specify the canvas name. By default it is
 * created with id='canvas' (so creative uh ;-))
 */
function print_source ($attr, $content) {
	extract(shortcode_atts(array(
	      'canvas' => 'canvas'), $attr));
	$chart = $canvas;
  $result = '<div class="' . $chart . '">' . '<script type="text/javascript">' . $content . '</script>' . '</div>';
  return $result;
}

add_action('init', 'wordpressd3_init');

add_shortcode("d3-link", "include_resources");
add_shortcode("d3-source", "print_source");

// Remove WordPress auto-p and wptexturize from being executed before wordpress-d3
remove_filter('the_content', 'wptexturize');
remove_filter('the_content', 'wpautop' );
// and added again with less priority
// add_filter( 'the_content', 'wpautop' , 99);
//add_filter( 'the_content', 'wptexturize' , 99);
?>
