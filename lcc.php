<?php
/*
Plugin Name: Latest Comments with Content
Plugin URI: http://wallace.kom.pl/wordpress/lcc/
Description: Show latest comments with content and posts topic. 
Author: WaLLacE
Version: 0.1
Author URI: http://wallace.kom.pl/
*/
/*  Copyright 2006  Zbigniew  Koloda (email : wallace@ym.pl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*
Inspiration on Latest Comments with Gravatars plugin
http://www.blokura.com/2006/05/wordpress/latest-comments-with-gravatars-en/
*/
function lcc_show_comments( $comment_limit = 10, $comment_length = 100, $comment_topic = TRUE, $comment_after = '[...]' ) {

			
		global $wpdb;

		if( !ctype_digit( $comment_length ) || $comment_length < 1 ) {
			$comment_length = 100;
		}
		
		if( $comment_topic ) {
			
			$query = "
			SELECT $wpdb->comments.comment_date, $wpdb->comments.comment_author, $wpdb->comments.comment_ID, IF( CHAR_LENGTH($wpdb->comments.comment_content) > $comment_length, CONCAT(LEFT($wpdb->comments.comment_content, LOCATE(CHAR(0x20), $wpdb->comments.comment_content, $comment_length ) ), '$comment_after' ), $wpdb->comments.comment_content ) AS comment_content, $wpdb->posts.post_title, $wpdb->posts.ID
			FROM $wpdb->comments
			INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID
			WHERE $wpdb->comments.comment_approved = '1' 
			ORDER BY $wpdb->comments.comment_date DESC LIMIT $comment_limit";
			
		} else {
		
			$query = "
			SELECT $wpdb->comments.comment_date, $wpdb->comments.comment_author, $wpdb->comments.comment_ID, IF( CHAR_LENGTH($wpdb->comments.comment_content) > $comment_length, CONCAT(LEFT($wpdb->comments.comment_content, LOCATE(CHAR(0x20), $wpdb->comments.comment_content, $comment_length ) ), '$comment_after' ), $wpdb->comments.comment_content ) AS comment_content
			FROM $wpdb->comments
			WHERE $wpdb->comments.comment_approved = '1' 
			ORDER BY $wpdb->comments.comment_date DESC LIMIT $comment_limit";
		
		}
			
		$lcc_comments = $wpdb->get_results( $query );
			
			if( $lcc_comments )
			{
				$prev_ID = 0;
				
				foreach( $lcc_comments as $comment )
				{
					$data = date("d/m", strtotime( $comment->comment_date ) );
					if( $prev_ID != $comment->ID )
					{
						$permlink = get_permalink($comment->ID);
						if( $comment_topic ) {
							echo '</ul>
							<h5>'.$comment->post_title.'</h5>
							<ul>';
						}
					}
					echo '<li><a href="'.$permlink.'#comment-'. $comment->comment_ID.'">'.$data.' <cite>'.$comment->comment_author.'</cite></a> &raquo; '.$comment->comment_content.'</li>'."\n";
					$prev_ID = $comment->ID;
				}
			}

} 
?>
