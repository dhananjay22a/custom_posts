<?php
/* 
Plugin Name: Custom Post Types & Widget
Plugin URI: 
Description: Plugin to create custom post type and widget to show 5 most recent posts, maximum of 30 days old, for posts of that custom post type
Version: 1.0
Author: Dhananjay Singh
Author URI: 
Text Domain: pmc-plugin
License: GPLv2 
*/


/*  Copyright 2015  Dhananjay Singh  (email : dsingh@pmc.com) 
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
     Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	
	 /*************************************
		Creating custom post type
		Created by : DJ
		Date : 21 Sept. 2015
	***************************************/
	namespace dj\custom_post;
	
	//Adding action hook to register custome post type 
	add_action( 'init', '\dj\custom_post\register_custom_post_types' ); 
	
	//Function to register custome post type 
	function register_custom_post_types() { 
		
		$lables		=	array( 
							'name' => 'News',
							'add_new' => 'Add New News',
							'add_new_item' => 'Add New news',
							'edit_item' => 'Edit News'
						);
		
		$supports	=	array( 'title', 'editor', 'thumbnail', 'author', 'comments' );
						
		$args		=	array(
							'labels' => $lables,
							'public' => true,
							'supports' => $supports
						);
		//WP inbuilt function to register post type
		register_post_type( 'news', $args );
	}
	
	
	/*
		Creating an widget for custom posts
		Created by : DJ
		Date : 24 Sept 2015
	*/
	
	//Adding action hook to register custom post widget
	add_action( 'widgets_init', '\dj\custom_post\register_widgets' ); 
	
	//Function to register custom post widget
	function register_widgets() {
		//WP inbuilt function to register widget
		register_widget( '\dj\custom_post\post_widget' ); 
	}
	
	
	
	//Adding action hook to add css in wp header for this widget.
	add_action( 'wp_head', '\dj\custom_post\widget_css' );
	function widget_css() { 
		?> 
     		<style type="text/css"> 
				#pmc_custompost_type_main_div { border:1px solid #2e2e2e; padding:0; }
				#pmc_custompost_type_main_div .widget_title_div { font-size:20px; background: #cccccc; color:#0088CC; text-align:center; }
				#pmc_custompost_type_main_div .widget_title_div { font-size:20px; background: #cccccc; color:#0088CC; text-align:center; }
				#pmc_custompost_type_main_div .news_item_main_div {  font-size:12px; padding:5px; height:62px; }
				#pmc_custompost_type_main_div .news_item_thumbnail{ width:52px; height:52px; padding:1px; float:left; }
				#pmc_custompost_type_main_div .news_item_title { width:296px; height:37px; padding:0 5px; float:left; }
				#pmc_custompost_type_main_div .author_name { float:left; height:15px; font-size:10px; color:#7e7e7e; padding: 0 5px; }
     		</style>
 		<?php 
	}
	
	
	
	//Function to filter upto 30 days old posts
	function filter_where($where = '') {
		//posts in the last 30 days
		$where .= " AND post_date > '" . date('Y-m-d', strtotime('-30 days')) . "'";
		return $where;
	}
	
	
	
	/*
		Class for widget
	*/
	class post_widget extends \WP_Widget {
		
		function __construct() {
     		
			$widget_ops = array(
					         'classname'   => 'post_widget_class',
					         'description' => 'Widget to display custom post type data .' 
						);
		
			parent::__construct( 'post_widget', 'Custom Post Widget', $widget_ops ); 
		
		}
		
		
		
		//Function to display up to 5 most recent posts, maximum of 30 days old, for posts of that custom post type
		function widget( $args, $instance ) {
			
			extract( $args );
			echo $before_widget;
			
			$currentID = get_the_ID(); //Getting current post id
			
			$args =  array( 
						'posts_per_page' => '5', 
						'post_type'      => 'news',
						'post__not_in'	=> array($currentID),
						'orderby' => 'date',
						'order'   => 'DESC'
			); 
			
			//Adding filter hook filter_where.
			add_filter('posts_where', '\dj\custom_post\filter_where');
			
			//Checking cache if data is available. And if so assign it to $news and avoid if part.
			$news = get_transient('custom_posts_key_'.$currentID);
			if ($news === false) {
				$news = new \WP_Query( $args );
				//Adding data to cache
				set_transient('custom_posts_key_'.$currentID, $news, 3600 * 24);
			}
			
			//Removing filter hook filter_where
			remove_filter('posts_where', '\dj\custom_post\filter_where');
			
			//Main widget ourter div
			echo '<div id="pmc_custompost_type_main_div">';
				//Widget title div
				echo '<div class="widget_title_div">';
					echo "Latest News";
				echo '</div>';
				//Widget title div ends here
			
				// The Loop 
				$counter	=	0;
				while ( $news->have_posts() ) : $news->the_post();
					
					if($counter%2 == 0 ) {
						$textColor	=	'#2e2e2e';
						$bgColor	=	'#cceeff';
					} else {
						$textColor	=	'#2e2e2e';
						$bgColor	=	'#FFFFFF';
					}
					
					//News Item main div
					echo '<div style="color:'.$textColor.'; background:'.$bgColor.';" class="news_item_main_div">';
						//New Item thumbnail div
						echo '<div style="color:'.$textColor.'; background:'.$bgColor.';" class="news_item_thumbnail" >';
							?>
								<a href="<?php the_permalink()?>" title="<?php the_title()?> "> 
							<?php
							if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
								the_post_thumbnail( array(50, 50) );
							} else {
								
							}
							echo '</a>';
						echo '</div>';
						//News Item thumbnail div ends here
						
						//News Item title div
						echo '<div style="color:'.$textColor.'; background:'.$bgColor.';" class="news_item_title" >';
							?><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo $this->short_title(100); ?></a><br /><?php 
						echo '</div>';
						//News item title div ends here
						
						//Authorname div
						echo '<div class="author_name">';
							echo "Author : ";
							the_author();
						echo '</div>';
						//Authername div ends here
						
					$counter++;
					echo '</div>';
					//News item main div ends here
			endwhile;
			
			//Widget main div ends here
			echo "</div>";
			echo $after_widget;
			// Reset Post Data 
			wp_reset_postdata();
		}
		
		
		//Function to short title if larger than $length
		function short_title( $length ) {
			
			$newsTitle	=	get_the_title();
			if(strlen($newsTitle) > $length )
				return $shortNewsTitle	=	substr($newsTitle, 0, $length ). '...';
			else return $newsTitle;
		}
		
	}
?> 