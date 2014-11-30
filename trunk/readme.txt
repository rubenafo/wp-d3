=== Wp-D3 ===
Contributors: Ruben Afonso<ruben@figurebelow.com>
Donate link: http://www.figurebelow.com
Tags: d3, visualization, javascript, svg, charts
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 2.1.2
License: GPL2

A plugin to integrate D3 into your Wordpress post/pages.
== Description ==
D3.js is a JavaScript library for manipulating documents based on data. D3 helps you bring data to life using HTML, SVG and CSS. D3’s emphasis on web standards gives you the full capabilities of modern browsers without tying yourself to a proprietary framework, combining powerful visualization components and a data-driven approach to DOM manipulation. 

Ths plugin provides the current last version of D3 (v3.3) and a javascript editor to add javascript code into your post or pages and render it.
All extra .css or .js files can be uploaded to your Wordpress blog and then included into the post/page easily using built-in functions.
Enjoy!.

== Installation ==
1. Upload `wp-d3/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a post in Visual Mode. The last toobar icon is a shortcut to the Wp-D3 Manager Editor.
4. Paste your code into the editor.
5. If you have any extra dependencies to .js or .css files upload them to your Wordpress
   installation using the Wordpress Media Library and then add their URL to the list of includes that
   appears when Wp-D3 Chart Manager's 'Include' button is pressed.
6. In your D3 code, make sure that the chart is attached to a div with the same name as the current edition tab.
5. Press 'Insert' to add a shortcode into the current post with the chart reference.
6. Press 'Save'.
7. Preview your post and have fun!.

== Frequently Asked Questions == 
1. Is it possible to execute javascript code different than D3 one?
   Technically you can use any .js file in your code if you upload it to the Wordpress' Media Library and then copy its
   URL to the Wp-D3 "Includes" dialog.
   This plugin is designed with D3 in mind so any extra libs are not guaranteed to work.
2. What about including remote javascript files (i.e. hosted in another server)?
   Current browsers dont allow the reference of javascript code hosted in a machine different of the localhost due to security
   concerns so this is not a limitation of the Wp-D3 plugin.

== Upgrade Notice == 
= 1.0 = 
Update Wordpress-d3 to version 1.0, first implementation.

= 1.2.2 =
This version fixes a bug on plugin init. d3-link tag is now optional. Upgrade recommended.

= 2.0 =
Huge user interface improvement and better integration with Wordpress API.
D3 code now can be pasted, edited and saved using a GUI without affecting the post content.
Support to the old tags system is still provided so old charts should render fine.

== Screenshots ==

1. Live example <a href="http://figurebelow.com/d3/short-tutorial-into-wp-d3-v2/">here</a>
2. Wp-D3 Chart Manager to edit and save multiple charts code.
3. URL Include dialog to provide the URLs of extra style (.css) and javascript (.js) files used in the D3 snippets.
4. Example displaying D3 chart taking from bl.ocks.org, in <a href="http://figurebelow.com/d3/wp-d3-and-day-hour-heatmap/">figurebelow.com</a>

== Changelog ==
= 1.0.0 =
* First commit. Basic tag functionality, [d3-link], [d3 canvas="canvas"]
= 1.1 =
* Fixed d3 snippet insertion, now the code output is shown where it has been inserted inside the post.
= 1.2 =
* Fixed bug that provoked wpautop and wptexturize to be disabled permanently.
* Fixed d3-link include's generation.
* Added filter to avoid wptexturize messing the [d3-source] content.
* Updated D3 shipped version to last 3.3.3
* The plugin now uses the lighter d3.v3.min.js (instead of d3.v3.js).
= 1.2.1 =
* Implemented workaround to keep '&' symbols inside javascript code without unicode conversion.
= 1.2.2 =
* Fixed plugin initialization bug that generated a wrong js script include.
* The [d3-link] tag is now optional and can be skipped if nothing needs to be   included.
* The shipped d3.js version has been updated to D3 v3.3.10
= 2.0 =
* New interface added to edit and save D3 code without interfering with post content by means of a 
* javascript editor providing syntax highlightning, syntax error warnings and tab indentation.
= 2.1.1 =
* Added WPD3_CHART_ID feature: the constant WPD3_CHART_ID can be used instead of the chart title and the 
plugin will generate an ID automatically.
* Updated D3.js to version 3.4.13
* Updated ACE editor to version 1.1.7
* Validation of the Wp-D3 plugin with Wordpress 4.0
= 2.1.2 = 
* Removed nasty bug that made charts overlap when multiple charts had same javascript variables.
