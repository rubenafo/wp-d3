=== Wp-D3 ===
Contributors: Ruben Afonso<ruben@figurebelow.com>
Donate link: http://www.figurebelow.com
Tags: d3, visualization, javascript
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.2
License: GPL2

A plugin to integrate D3 into your Wordpress post/pages.
== Description ==
D3.js is a JavaScript library for manipulating documents based on data. D3 helps you bring data to life using HTML, SVG and CSS. D3’s emphasis on web standards gives you the full capabilities of modern browsers without tying yourself to a proprietary framework, combining powerful visualization components and a data-driven approach to DOM manipulation. 

Ths plugin provides the current last version of D3 (v3.3) and a couple of tags to reference any possible .js or .css depedencies and to paste javascript code into your post or pages to render it.
All extra .css or .js files can be uploaded to your Wordpress blog and then included into the post/page easily using built-in functions.
Enjoy!.

== Installation ==
1. Upload `wp-d3/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit your page/post in Text mode (to avoid replacement of '<' and '>')
4. Add the tags [d3-link][/d3-link].
5. If your code depends on .js, .css, .json third files:
    Click between the tags to place the mouse cursor.
    Add the deps files through 'Add Media' button, select them, and then click on 'Insert into page'.
    The returned links should be now between the tags [d3-link] and [/d3-link] tags. 
    Example: [d3-link]
             <a href="http://figurebelow.com/wp-content/uploads/2013/02/mixes.js">mixes</a>
             [/d3-link]
6. Paste your d3 code between [d3-source] and [/d3-source] tags.
   Yoy can specify the canvas name, where the code is placed, adding the 'canvas' parameter e.g. [d3-source canvas="xxx"], 
   where xxx is the canvas name. The default id is "canvas".
7. Save the post/page.
8. Enjoy!

== Frequently Asked Questions == 
Lets wait until next version.

== Upgrade Notice == 
* Update Wordpress-d3 to version 1.0, first implementation.

== Screenshots ==

1. Live example <a href="http://figurebelow.com/d3/santander-shares-2012/">here</a>

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
