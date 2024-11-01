=== WP Light Heatmap ===
Contributors: wplightheatmap
Tags: heatmap, heat map, click map, clickmap, analytics
Requires at least: 3.0.1
Tested up to: 5.3.2
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to create a heatmap based on mouse clicks and cursor movements.

== Description ==

This plugin allows you to create a heatmap based on mouse clicks and cursor movements. By default, positions of the cursor in the work area (e.g. main page of the blog, any post, categories, tags, etc.) will be saved per some interval in seconds (5 seconds by default) for every user that will visit your homepage. 

Also, you can add click tracking and the position of every mouse click will be saved too. All the saved coordinates will be saved in the database and can be rendered by admins at any time with the "Display Heatmap" button on the main page.

**Major features of the plugin**
- Automatically saves the position of the cursor per some time interval for every user
- Immediately saves click positions on any page
- Allows to set own position-save interval
- Saves everything in your own WP database. No 3rd party services involved!

== Installation ==

**Manual Installation**
1. Download *"wp-light-heatmap.zip"* archive from this page with the *"Download"* button
2. Unzip directory *"wp-light-heatmap"* with plugin from archive into *"plugins"* directory of your WordPress Installation  (e.g. *wp-content/plugins/wp-light-heatmap*)
3. Hit the *"Activate"* button in plugins menu of WordPress administration console

**Installing from WordPress plugins**
1. Find *"WP-Light-Heatmap"* plugin in the plugins menu of your WordPress administration console
2. Hit *"Install"* and then *"Activate"*

== Frequently Asked Questions ==

= How to use this plugin? =

To use this plugin install, activate and configure it. After that positions of cursor and clicks will be saved and can be rendered from the main page.

Once you click on display heatmap the processing time varies depending on how many clicks you have had on your site. For a few hundred clicks the processing will take a few seconds.  or a few thousand clicks it could take a few minutes. Accurate results can be obtained within a week.

If the process of loading points with coordinates takes too much time, it is enough to clear the database using the plugin options.

= What is minimal time to save by intervals? =  

It depends on you: if you want, you can set a time interval to any value in seconds, but the preferable value is 5 seconds.


== Screenshots ==

1. This is the administration/settings page of this plugin.

== Changelog ==

= 1.0 =
* Stable version release

== Upgrade Notice ==

= 1.0 =
* Stable version release