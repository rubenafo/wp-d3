<?php

/*
Plugin Name: Wordpress-d3.js
Plugin URI: http://wordpress.org/extend/plugins/wp-d3/
Description: D3 is a very popular visualization library written in Javascript. This plugins provides a set of tags to link page/post content to d3.js libraries in order to visualize the javascript snippets inside Wordpress.
All javascript code can be added directly to the posts by means of a custom javascript code editor (Wp-D3 Chart Manager)
Version: 2.0
Author: Ruben Afonso
Author URI: http://www.figurebelow.com
License: GPL2
*/

/**
 * Checks that the user defined the generated id-chart constant.
 * Just a simple "string contains" check.
 * Parameters:
 *        * chartContent - the raw D3 code in the chart tab
 */
function containsAutoIdFlag ($chartContent) 
{
	return (strpos($chartContent,'"WPD3_CHART_ID"') !== false);
}

/**
 * Replaces the autoId flag with the actual generated chart id.
 * Parameters:
 *        * chartContent - the raw D3 code in the chart tab
 *        * genChartId   - the generated chart id 
 */
function replaceAutoIdFlag ($chartContent, $genChartId)
{
	return (str_replace ('"WPD3_CHART_ID"', '".' . $genChartId . '"', $chartContent));
}

/**
 * Generates a random chart id in case the user defined the WPD3_CHART_ID
 * inside the post
 */
function genRandomId () 
{
	// Generates 
	$randomString = substr(str_shuffle(MD5(microtime())),0,5);
	return "wpd3-" . $randomString;
}


?>
