<?php
/******************************************************************

Plugin Name: 	TCU Custom Post Types
Description: 	Custom post type helper class to easily register them within any TCU theme. Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you. This plugin also includes an easy to use widget to pull a feed from a custom post type.
Version: 		3.3
Author: 		Website & Social Media Management
Author URI: 	http://mkc.tcu.edu/web-management.asp


 ******************************************************************/

if ( !defined( 'WPINC' ) ) {
    die;
} // don't remove bracket!

/************* INCLUDE NEEDED FILES ***************/

/*
1. classes/tcu_register_post_types_class.php
	- Register post type
	- Register meta data
	- Save meta data into database
*/
require_once('classes/tcu_register_post_types_class.php'); // Don't remove this!
require_once('widgets/custom_post_type_widget.php'); // add a custom feed widget
/*
* By Matthew Ruddy (http://easinglider.com)
*
* Information on how to use it go to (http://matthewruddy.github.io/Wordpress-Timthumb-alternative/)
*/
require_once('resize/resize.php'); // gives the ability to crop images

/*
2. custom_types/faculty.php
	- an example custom post type (like faculty)
	- example custom taxonomy (like departments)
	- example of all meta data fields (like phone number)
	- turn off (comment) if not needed
*/
require_once('custom_types/faculty.php');

/*
to add another custom post type simply create
the file inside 'custom_types' directory and
give it the same name of your post type.

For example, to create a custom post type of news,
we would create a file called news.php inside
the custom_types directory. Then simply call
it using:

	require_once('custom_types/news.php');

You can add as many as you need. Enjoy!
*/

/*
3. For examples on how to display data go to:
	- examples > archive-faculty.php
	- examples > single-faculty.php
*/

// INITIATE PLUGIN
function tcu_custom_post_types_initiate() {

    flush_rewrite_rules();

} // don't remove this bracket!

register_activation_hook( __FILE__, 'tcu_custom_post_types_initiate' );

// DEACTIVATE PLUGIN
function tcu_custom_post_types_deactivate() {

    flush_rewrite_rules();

} // don't remove this bracket!

register_deactivation_hook( __FILE__, 'tcu_custom_post_types_deactivate' );

// Check for empty array
function tcu_is_array_empty( $a ) {
	foreach( $a as $elm )
		if( !empty( $elm ) ) return false;
	return true;
} // don't remove this bracket!

?>