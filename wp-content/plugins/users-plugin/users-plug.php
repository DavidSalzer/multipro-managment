<?php
/*
Plugin Name: Users plugin
Plugin URI: http://cambium.co.il
Description: דואג לפירסומים- הסרת פירסומים של פוסטים לפי התנהגות צפוייה מראש
Author: Yaakov Bernstein
Version: 0.1
Author URI: 
License: GPLv2 or later
*/

//include ("setpost.php");

///json api extention
// Add a custom controller

function add_users_controller($controllers) {
  // Corresponds to the class JSON_API_MyController_Controller
  $controllers[] = 'users';
  return $controllers;
}
add_filter('json_api_controllers', 'add_users_controller');

// Register the source file for JSON_API_Widgets_Controller

function set_users_controller_path() {
  return plugin_dir_path( __FILE__ ).'/users.php';
}
add_filter('json_api_users_controller_path', 'set_users_controller_path');





//Set Default Meta Value
//function set_default_meta_new_post($post_ID){

// $type=get_post_type($post_ID);
// if($type=='device'){
// 	$current_field_value = get_post_meta($post_ID,'deviceReady',true); //change YOUMETAKEY to a default 
//	$default_meta = '0'; //set default value
//	
//	if ($current_field_value == '' && !wp_is_post_revision($post_ID)){
//		add_post_meta($post_ID,'deviceReady',$default_meta,true);
//	}
//	return $post_ID;
// 
// }
// 
//}

//add_action('wp_insert_post','set_default_meta_new_post');


