<?php
/*
Plugin Name: Posts' Links by Category
Plugin URI: http://wallace.kom.pl/blog/wordpress-plugin-plc.html
Description: "Posts' links by category" is a WordPress plugin that for a single post shows links to next/previous posts for all categories, which the post is assigned to.
Author: Zbigniew Koloda (aka WaLLacE)
Version: 1.0
Author URI: http://wallace.kom.pl/
*/
/*  CREATIVE COMMONS LICENSE - COMMONS DEED 2.5 BY

You are free:
-to copy, distribute, display, and perform the work
-to make derivative works
-to make commercial use of the work

Under the following conditions:
Attribution. You must attribute the work in the manner specified by the author or licensor.

For any reuse or distribution, you must make clear to others the license terms of this work.
Any of these conditions can be waived if you get permission from the copyright holder.

http://creativecommons.org/licenses/by/2.5/deed.en
*/

// get next, previous posts
function plc_get() {
	
	global $post, $wpdb;

	if( !is_single() || is_attachment() )
		return null;

	$plc = array();
	$current_post_date = $post->post_date;
	$cat_array = get_the_category($post->ID);
	$now = current_time('mysql');
	$cat_count = count($cat_array);
	for ( $i = 0; $i < $cat_count; $i++ ) {
		//
		$plc_post = @$wpdb->get_row("SELECT ID, post_title FROM $wpdb->posts INNER JOIN $wpdb->post2cat ON $wpdb->posts.ID = $wpdb->post2cat.post_id AND category_id = ". intval($cat_array[$i]->cat_ID) ." WHERE post_date < '$current_post_date' AND post_status = 'publish' $sqlcat ORDER BY post_date DESC LIMIT 1");
		if( $plc_post ) {
			$plc['prev'][] = array(get_permalink($plc_post->ID), apply_filters('the_title', $plc_post->post_title, $plc_post), $cat_array[$i]->cat_name);
		} 
		
		$plc_post = @$wpdb->get_row("SELECT ID, post_title FROM $wpdb->posts INNER JOIN $wpdb->post2cat ON $wpdb->posts.ID = $wpdb->post2cat.post_id AND category_id = ". intval($cat_array[$i]->cat_ID) ." WHERE post_date > '$current_post_date' AND post_date < '$now' AND ID != $post->ID AND post_status = 'publish' $sqlcat ORDER BY post_date ASC LIMIT 1");
		if( $plc_post ) {
			$plc['next'][] = array(get_permalink($plc_post->ID), apply_filters('the_title', $plc_post->post_title, $plc_post), $cat_array[$i]->cat_name);
		}
	
	}
	$GLOBALS['plc'] = $plc;
}

function plc( $dir, $format = '<li>%prefix%<a href="%link%">%category%: %title%</a>%suffix%</li>', $prev = '&laquo; ', $next = ' &raquo;' ) {
	
	if( !isset( $GLOBALS['plc'] ) ) {
		plc_get();
	}
	
	global $plc;
	
	$ex = array('%link%', '%title%', '%category%', '%prefix%', '%suffix%');

	if( isset( $plc[$dir] ) && is_array( $plc[$dir] ) ) {

		foreach( $plc[$dir] as $data ) {
			
			if( $dir == 'prev' ) {
				$data[] = $prev;
				$data[] = '';
			} else {
				$data[] = '';
				$data[] = $next;
			}
			
			echo str_replace($ex, $data, $format);
		}
		
	} else {
		echo '';
	}
}
?>