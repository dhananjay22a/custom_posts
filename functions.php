<?php
/**
* This file contains functions to be used in Custom Posts Widget plugin. This file is being imported in custom_posts.php file.
*
* @since 2015-10-09
* 
* @version 2015-10-09 Dhananjay Singh - PMCVIP-243
*
*/

	namespace dj\custom_post;
	
	/**
	* Function to register custom post type 
	*
	* @since 2015-10-09
	*/
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
	
	/**
	* Function to register custom post widget
	*
	* @since 2015-10-09
	*/
	function register_widgets() {
		//WP inbuilt function to register widget
		register_widget( '\dj\custom_post\post_widget' ); 
	}
	
	/**
	* Function to include css file
	*
	* @since 2015-10-09
	*/
	function custom_post_css() {
		wp_register_style( 'customPostWidgetStyle', plugins_url( 'custom_posts/css/custom_post.css' ) );
		wp_enqueue_style( 'customPostWidgetStyle' );
	}
	
?>