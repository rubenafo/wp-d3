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
	return "wpd3-" . $randomString;
}


?>
