=== Plugin Name ===
Contributors: DanielSands
Donate link: http://www.danielsands.co.cc/
Tags: Status, Mood, Current, Facebook, MySpace
Requires at least: 2.0.2
Tested up to: 2.7
Stable tag: 1.15

A Simple Wordpress plug-in which enables you to show a current status/mood on your website.

== Description ==

A Simple Wordpress plug-in which enables you to show a current status/mood on your website, 
it includes full administration of your current status, and the apperance of the status holder can be 
fully altered using CSS within the admin page. This is only the first version and more concise versions 
will be released if it proves to be popular..

**New Version: 1.15**
This new version includes the following new features:<br />
>1. Status now shows an "X Minutes ago", etc  message to indicate how old the status is. By default it is included in fresh installations of MyStatus 1.10, however if you're upgrading from Version 1.01 and want to use this feature you must alter the 'Structure' and 'Style' settings in your administration panel, simply add<br />
		>`<span class="time">%TIME%</span>` <br />
to your structure, after the "%STATUS%" tag and add <br />
		>`.time { font: normal 10px verdana;}` <br />
to your Style setting.<br />
2. You can now disable the default function to load the status, and call the function directly. To disable the function simply untick the "Run Function in get_sidebar()" checkbox in the settings screen, you can then use the following code to get the status text: <br />
	>`<?php get_current_status($before, $after, $mid) ?>` <br />
	>`<?php get_previous_status($before, $after, $mid) ?>` <br />
	>`<?php get_mystatus_name() ?>` <br />
Simply replace $before, $after and $mid with the structure you want, for example: <br />
	>`<?php get_current_status('<span class="status">', '</span>', '</span><span class="time">') ?>` <br />
would return: <br />
	>`<span class="status">Current Status will be here</span><span class="time">Time ago status submitted will be here</span>`<br />
	
**Updating to version 1.15**
Simply replace the MyStatus.php file in your plugins directory.

== Installation ==

1. Upload the file 'MyStatus.php' to your wordpress plug-in's directory (wp-content/plugins)
1. Log-in to your administration page and click Plugins then activate the 'MyStatus' plugin.
1. Click the Settings Tab and then click 'MyStatus' this should show you a settings screen.
1. Settings:
 	* Your name - Simply enter your name as you'd like it to appear.
	* Previous text's to show - All of your previous status messages are stored in your wordpress database, and can be seen on your site when the user clicks [*] here you can set how many previous entries should be shown. Please ensure you enter one more than the actual number of entries you want to be shown, for example if you want 5 previous entries, enter '6'.
	* Structure - You shouldn't need to alter this in any way, but can if you wish to change the structure of the holding DIV your status text sits in.
	* Style - Change this as you see fit, you may wish to change colours or the position of your status box.
1. Click Save Settings.
1. At the top of the screen you will see 'Your name' [IS   ] simply enter your current mood / status in the text box and click 'Update', et voila! You now have a status box on your site.

== Frequently Asked Questions ==

If you have any questions please visit my website [Here](http://www.danielsands.co.cc/ "Danielsands.co.cc")

== Screenshots ==

1. `/tags/1.01/screenshot_1.jpg`
2. `/tags/1.01/screenshot_2.jpg`