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

	
	namespace dj\custom_post;
	
	require_once( dirname( __FILE__ ) . '/widget_class.php' );
	require_once( dirname( __FILE__ ) . '/functions.php' );
	
	//Adding action hook to register custome post type 
	add_action( 'init', '\dj\custom_post\register_custom_post_types' ); 
	
	//Adding action hook to register custom post widget
	add_action( 'widgets_init', '\dj\custom_post\register_widgets' ); 
	
	//Adding action hook to include css file
	add_action( 'wp_enqueue_scripts', '\dj\custom_post\custom_post_css' );
	
?> 