<?php

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
	return "wpd3_" . $randomString;
}

/**
 * Wraps the code inside a body function and then invokes it. The function names
 * is the provided $id so this should be a unique identifier.
 * E.g. 
 * function $id () {
 *        $code ...
 * }
 * $id ();
 * This is a workaround to define namespaces in javascript and avoid conflicts
 * when two charts defines variables with the same name.
 * As identificador we use the $postId
 */
function wrapAsFunction ($code, $id) 
{
	$functionName = str_replace("-", "_", $id);
	return "function " . $functionName . " () {" . $code . "}; " . $functionName . "();";
}

function getJavaScriptInclude ($js)
{
	return '<script type="text/javascript" src=\'' . $js . '\'></script>' . PHP_EOL;
}

function getCssInclude ($css)
{
	return '<link type="text/css" rel="Stylesheet" href=\'' . $css . '\'/>' . PHP_EOL;
}

?>
