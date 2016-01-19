<?php
/*
This is the custom post type post example.
If you edit the post type name, you've got
to change the name of the file to
reflect that name change.

i.e. if your custom post type is called
expert, then your file should be expert.php

*/

/*
1. Instantiate the class

The variable name must reflect the
same name of your custom post type.

For example, to create a custom post type
of expert, we could do:

$expert = new TCU_Register_Post_Type('Expert', 'Experts');

	- string (required) - First parameter is singular
	- string (required) - Second parameter is plural name
	- array (optional) - an array of arguments to use for supports
	- string (optional) - For a complete list of arguments: http://codex.wordpress.org/Function_Reference/register_post_type

We can change the menu position and add a menu icon too!
For more information on menu icons: https://developer.wordpress.org/resource/dashicons/#welcome-comments

$expert = new TCU_Register_Post_Type( 'Expert', 'Experts', array(
   		'supports' => array('title', 'editor', 'thumbnail'), 'menu_position' => 20, 'menu_icon' => 'dashicons-businessman'
   ));


We may also pass an optional third parameter
to override some of the defaults. For example, if
we only want to provide support for a title and editor,
we could do:

$expert = new TCU_Register_Post_type('Expert', array(
		'supports' => array('title', 'editor')
	));

Most common supports argument:
	- title
	- editor
	- thumbnail
	- excerpt

Note: All parameters are lower case. The last
parameter does not need a comma at the end.

*/
$faculty = new TCU_Register_Post_Type( 'Faculty', 'Faculty', array(
   		'supports' => array('title', 'editor', 'thumbnail')
   ));

/*
2. Add custom taxomonies

To add custom taxonomies, simply call the add_taxonomy method.
For example, to create a custom taxonomy of 'department'
within the 'expert' custom post type we could do:

$expert->add_taxonomy('department', 'departments');

	- string (required) - singular name for taxonomy
 	- string (required) - plural name for taxonomy

You can add as many as you need.

*/
$faculty->add_taxonomy( 'department', 'departments' );

/*
2. Create custom meta boxes

To add custom meta boxes, simply call
the add_meta_box method. First argument
is the title of the meta box and then
in the second argument you must
pass an array for each field.

For example, to add three meta boxes to the
expert custom post type of
	- Title
	- Email
	- Research Interest

We first give it a title of: About Expert. Then
we call an array for each field:

$expert->add_meta_box('About Expert', array(
		// Text field
		array(
			'name'  => 'Title',
		    'label' => 'Title',
		    'desc'  => 'Please enter your title',
		    'type'  => 'text'
		),
		// Email field
		array(
			'name'  => 'Email Address',
		    'label' => 'Email Address',
		    'desc'  => 'Please enter your email address',
		    'type'  => 'email'
		),
		// List field
		array(
		    'name'  => 'Research Interest',
		    'label' => 'Research Interest',
		    'desc'  => 'Please list all research',
		    'type'  => 'list'
		)
	)
);

Below you will find ALL available meta boxes. To view each
example just go into the WordPress dashboard > Faculty > Add New Faculty.

IMPORTANT: All meta data contains a prefix of _tcu_ in front.
You need this information to display data. All names should be
unique to the custom post type.

*/

/* The add_meta_box method takes four arguments
	- $title (string) (required)
	- $fields (array) (required)
	- $context (string) (optional) The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side').
	- $priority (string) (optional) The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
*/


$faculty->add_meta_box(
    'About Faculty', array(
    	// Text field
    	array(
		        'name'  => 'Text Name', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_text_name', true);
		        'label' => 'First Name', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'text'
		    ),
    	// Email field
    	array(
		        'name'  => 'Email Name', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_email_name', true);
		        'label' => 'Email Address', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'email'
		    ),
    	// Telephone field
    	array(
		        'name'  => 'Telephone Name', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_telephone_name', true);
		        'label' => 'Phone Number', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'tel'
		    ),
    	// Website/URL field
    	array(
		        'name'  => 'Website Name', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_website_name', true);
		        'label' => 'Website', // example
		        'desc'  => 'Please enter description', // web address must start with http://
		        'type'  => 'url'
		    ),
    	// Textarea
    	array(
		        'name'  => 'Textarea Name', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_textarea_name', true);
		        'label' => 'Textarea', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'textarea'
		    ),
    	// Image
    	array(
		        'name'  => 'Image Name', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_image_name', true);
		        'label' => 'Faculty Image', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'image'
		    ),
    	// Single Checkbox
    	array(
				'name'    => 'Single Checkbox', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_single_checkbox', true);
				'label'   => 'Label Name', // example
				'desc'    => 'Please enter description',
				'type'    => 'checkbox'
		    ),
    	// Group Checkbock
    	array(
				'name'    => 'Group Checkbox', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_group_checkbox', true);
				'label'   => 'Label Name', // example
				'desc'    => 'Please enter description',
				'type'    => 'checkbox_group',
				'options' => array(
					// each option is an array
		        	array(
		        		'value' => 'red',
		        		'label' => 'Red' //example
		        		),
		        	array(
		        		'value' => 'yellow',
		        		'label' => 'Yellow' //example
		        		)
		        	)
    		),
    	// Radio
    	array(
				'name'    => 'Radio', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_radio', true);
				'label'   => 'Label Name', // example
				'desc'    => 'Please enter description',
				'type'    => 'radio',
				'options' => array(
					// each option is an array
		        	array(
		        		'value' => 'red',
		        		'label' => 'Red' //example
		        		),
		        	array(
		        		'value' => 'yellow',
		        		'label' => 'Yellow' //example
		        		)
		        	)
    		),
    	// Date
    	array(
		        'name'  => 'Date', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_date', true);
		        'label' => 'Event Date', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'date'
		    ),
    	// Select
    	array(
				'name'    => 'Select', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_select', true);
				'label'   => 'Label Name', // example
				'desc'    => 'Please enter description',
				'type'    => 'select',
				'options' => array(
		        	array(
		        		'value' => 'horizontal',
		        		'label' => 'Horizontal' //example
		        		),
		        	array(
		        		'value' => 'vertical',
		        		'label' => 'Vertical' //example
		        		)
		        	)
		      ),
    	// List - this is a repeatable list
    	array(
		        'name'  => 'List', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_list', true);
		        'label' => 'Label Name', // example
		        'desc'  => 'Please enter description',
		        'type'  => 'list'
		    ),
    	// Wordpress editor
    	array(
				'name'    => 'Wordpress Editor', // This will be the name given to your meta data. i.e. get_post_meta($post->ID, '_tcu_wordpress_editor', true);
				'label'   => 'Education', // example
				'desc'    => 'Please enter description',
				'type'    => 'editor',
				'options' => array('media_buttons' => false) // Show media buttons: true/false
		    )
    	)
);

?>