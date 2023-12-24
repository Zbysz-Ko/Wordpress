=== Posts' Links by Category ===
Tags: navigation, post, next, previous, links
Contributors: wallace
Author: Zbigniew Koloda
Author URI: http://wallace.kom.pl/
PLUGIN URI: http://wallace.kom.pl/blog/wordpress-plugin-plc.html
Version: 0.1

"Posts' links by category" is a WordPress plugin that for a single post shows links to next/previous posts for all categories, which the post is assigned to.

== Installation ==

1. Put plc.php into [wordpress_dir]/wp-content/plugins/
2. Go to the WordPress admin interface and activate the plugin
3. (For theme Kubrick)
 a. Go to [theme_dir]/[your_theme]/single.php and change current block <div class="navigation"> to:

<div class="navigation">
			<div class="alignleft"><ul><?php plc('prev'); ?></ul></div>
            <div class="alignright"><ul><?php plc('next'); ?></ul></div>
		</div>

 b. At the end of your [theme_dir]/[your_theme]/style.css file add these lines:

.navigation ul {
	margin:0;
	padding:0;
	list-style:none;
}
.alignright {
	text-align:right;
	}
	
.alignleft {
	text-align:left;
	}


== Options ==

plc( direction, format, prefix, suffix )

direction: next or prev (required), shows links for next or previous posts;
format: generated code (default: '<li>%prefix%<a href="%link%">%category%: %title%</a>%suffix%</li>' )
    where:
    %prefix% will be replaced by prefix (only for links to previous posts)
    %link% will be replaced by link to the post
    %category% will be replaced by category name that post at link is assigned to
    %title% will be replaced by title
    %suffix% will be replaced by suffix (only for links to next posts)
prefix: prefix for links to previous posts (default: '&laquo; ')
suffix: suffix for links to next posts (default: ' &raquo;')

You can use plc() function as many times as you need. Data will be get from DB only once at the first usage.

= Examples =

1. Show links to previous posts with default options:

<ul><?php plc('prev'); ?></ul>

2. Show links to next posts with default options:

<ul><?php plc('next'); ?></ul>

3. Show links to next posts as default format without suffix:

<ul><?php plc( 'next','<li>%prefix%<a href="%link%">%category%: %title%</a>%suffix%</li>', '', '' ); ?></ul>

or

<ul><?php plc( 'next', '<li><a href="%link%">%category%: %title%</a></li>' ); ?></ul>

4. Show links to previous posts without category name and prefix:

<ul><?php plc('prev', '<li><a href="%link%">%title%</a></li>' ); ?></ul>

5. Show links to next posts with category name before post's link and suffix - > :

<?php plc('next', 'Category %category%: <a href="%link%">%title%</a> %suffix%<br />', '', '&gt;' ); ?></ul>

== Advanced ==

You can use plc_get() function to get data from DB. It will by placed at global variable $plc:

$plc = array(
    'prev' => array( // previous posts
        0 => array( 'link', 'post', 'category name' ),
        ...
    ),
    'next' => array( // next posts
        0 => array( 'link', 'post', 'category name' ),
        ...
    )
)

== Changelog ==

0.1 Initial version

== License ==

CREATIVE COMMONS LICENSE

COMMONS DEED 2.5 BY

You are free:
-to copy, distribute, display, and perform the work
-to make derivative works
-to make commercial use of the work

Under the following conditions:
Attribution. You must attribute the work in the manner specified by the author or licensor.

For any reuse or distribution, you must make clear to others the license terms of this work.
Any of these conditions can be waived if you get permission from the copyright holder.

http://creativecommons.org/licenses/by/2.5/deed.en