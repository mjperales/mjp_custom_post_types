# Custom Post Helper for Wordpress

This is a Custom Post type helper for WordPress to easily create custom post types, taxonomies and meta boxes. This class is based off of <a href="https://github.com/JeffreyWay/Easy-WordPress-Custom-Post-Types">Jeffrey Way</a>, <a href="https://github.com/Ginius/Wordpress-Custom-Post-Type-Helper">Gijs Jorissen</a>, <a href="http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-3-extra-fields/">Tammy Hart</a> and <a href="https://github.com/rmartindotco/WordPress-Custom-Post-Helper/blob/master/readme.md">Remy Martin</a>.

### Install:

Include the folder in your plugins folder or go through `Plugins > Add New > Upload`  *please make sure the file is in a zip format

### Usage:

To add a custom post type simply call the `TCU_Register_Post_Type` and pass a name.
**The first parameter is singular and second is plural.**

``` php
        $car = new TCU_Register_Post_Type( 'Car', 'Cars' );
```

**If you want to override defaults**

``` php
    $car = new TCU_Register_Post_Type( 'Car', 'Cars',

                // this takes title, editor, thumbnail, excerpt, comments, trackbacks, custom-fields
                array( 'supports' => array( 'title', 'editor', 'excerpt' ) )

         );
```

### Add Custom taxonomies:

To add Custom Taxonomies, simply call the `add_taxonomy` method.
**The first parameter is singular and second is plural.**

``` php
     $car->add_taxonomy( 'Model', 'Model' );
```

### Add Metaboxes:

First argument is the title of the meta box and
the second you must pass an array for each field.
Please see the file example under `custom_types > faculty.php`
for more instructions.

``` php
     $car->add_meta_box(
         'Car Info', array(
             // Text field
         array(
                'name'  => 'First Name',
                 'label' => 'First Name',
                 'desc'  => 'Please enter your first name',
                 'type'  => 'text'
             ),
         // Telephone field
         array(
                 'name'  => 'Telephone',
                 'label' => 'Telephone Number',
                 'desc'  => 'Please enter your office number',
                 'type'  => 'tel'
             ),
         // Website/URL field
         array(
                 'name'  => 'Website',
                 'label' => 'Website Address',
                 'desc'  => 'Please enter website address starting with http://',
                 'type'  => 'url'
             ),
         // Image
         array(
                 'name'  => 'Faculty Image',
                 'label' => 'Faculty Image',
                 'desc'  => 'Please select an image',
                 'type'  => 'image'
             ),
         // Single Checkbox
         array(
                 'name'  => 'Yes',
                 'label' => 'TCU Graduate',
                 'desc'  => 'Check if yes',
                 'type'  => 'checkbox'
             ),
         // Group Checkbock
         array(
                'name'    => 'Favorite color',
                'label'   => 'Favorite color',
                'desc'    => 'Check all that apply',
                'type'    => 'checkbox_group',
                'options' => array(
                     array(
                         'value' => 'red',
                         'label' => 'Red'
                         ),
                     array(
                         'value' => 'yellow',
                         'label' => 'Yellow'
                         )
                     )
                 ),
         // Radio
         array(
                'name'    => 'Radio',
                'label'   => 'Radio',
                'desc'    => 'Check only one',
                'type'    => 'radio',
                'options' => array(
                     array(
                         'value' => 'red',
                         'label' => 'Red'
                         ),
                     array(
                         'value' => 'yellow',
                         'label' => 'Yellow'
                         )
                     )
                 ),
         // Date
         array(
                 'name'  => 'Event Date',
                 'label' => 'Event Date',
                 'desc'  => 'Please choose the event date',
                 'type'  => 'date'
             ),
         // Select
         array(
                    'name'    => 'Select Slider Speed',
                    'label'   => 'Select Slider Speed',
                    'desc'    => 'Please choose in milliseconds',
                    'type'    => 'select',
                    'options' => array(
                         array(
                             'value' => 'horizontal',
                             'label' => 'Horizontal'
                             ),
                         array(
                             'value' => 'vertical',
                             'label' => 'Vertical'
                             )
                         )
               ),
         // Wordpress editor
         array(
                 'name'    => 'Wordpress Editor',
                 'label'   => 'WordPress Editor',
                 'desc'    => 'Please enter content',
                 'type'    => 'editor',
                 'options' => array('media_buttons' => false) // add media buttons true/false
             )
         ));
```

### Display
For examples on how to display data go to:

`examples > archive-faculty.php`

`examples > single-faculty.php`

### Display Custom Post Type Feed
Use the TCU Custom Feed widget to query through a selected custom post type.

