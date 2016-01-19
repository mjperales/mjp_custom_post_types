<?php
/**
*
* Register Post Type Class
*
* This is the core file where most of the
* main functions & features reside.
*
* Warning: DO NOT edit this page unless you
* know what you are doing.
*
* @author Mayra Perales <m.j.perales@tcu.edu>
*
*/

class TCU_Register_Post_Type {

	public $post_type_name;
	public $post_type_plural_name;
    public $post_type_args;
    public $post_types_labels;

    /* Class constructor */
    public function __construct( $name, $plural, $args = array(), $labels = array()  ) {

    	// Set some important variables
		$this->post_type_name        = self::uglify( $name );
		$this->post_type_plural_name = self::uglify( $plural );
		$this->post_type_args        = $args;
		$this->post_types_labels     = $labels;

		if( ! post_type_exists( $this->post_type_name ) ) {
			add_action('init', array( &$this, 'register_post_type' ));
		}

		if (is_admin()) {
			add_action('admin_head', array( &$this, 'add_custom_js_css' ) );
            add_action('admin_head', array( &$this, 'add_jquery_scripts' ) );
		}

		// Listen for the save post hook
		$this->save();

    } // don't remove this bracket!

    /* Method which registers the post type */
    public function register_post_type() {

    	// Capitalize words
		$name   = self::beautify( $this->post_type_name );
		$plural = self::beautify( $this->post_type_plural_name );

		$labels = array_merge(

			// Default
			array(
				'name' 				 => $plural,
				'singular_name' 	 => $name,
				'add_new' 			 => 'Add New ' . $name,
				'view_item' 		 => 'View ' . $name,
				'add_new_item' 		 => 'Add ' . $name,
				'edit_item' 		 => 'Edit ' . $name,
				'all_items' 		 => 'All ' . $plural,
				'search_items' 		 => 'Search ' . $plural,
				'not_found' 		 => 'No ' . $name . ' found',
				'not_found_in_trash' => 'No ' .  $name . ' found in Trash',
				'menu_name' 		 => $plural
			),

			$this->post_types_labels
		);

		// Same principle as the labels. We set some defaults and overwrite them with the given arguments.
		$args = array_merge(

		    // Default
		    array(
		        'labels'                => $labels,
		        'public'                => true,
		        'has_archive'           => true,
		        'supports'              => array('title', 'editor', 'thumbnail')
		    ),

		    // Given args
		    $this->post_type_args

		);

		// Register the post type
		register_post_type( $this->post_type_name, $args );

    } // end of register_post_type()

    /* Method to attach the taxonomy to the post type */
    public function add_taxonomy( $name, $plural ,$args = array(), $labels = array() ) {

    	if ( ! empty( $name ) && ! empty( $plural ) ) {
    		// We need to need to know the post type name so we can attach the taxonomy to it
    		$post_type_name = $this->post_type_name;

    		// Taxonomy properties
			$taxonomy_name   = self::uglify( $name );
			$tax_plural      = self::uglify( $plural );
			$taxonomy_args   = $args;
			$taxonomy_labels = $labels;

			if ( ! taxonomy_exists( $taxonomy_name ) ) {
				/* Create taxonomy and attach it to the object type (post type) */

				// Capitalize words and it plural
				$name   = self::beautify( $name );
				$plural = self::beautify( $plural );

				// Default labels, overwrite them with the given labels
				$labels = array_merge(
					array(
						'name'                  => $plural,
				        'singular_name'         => $name,
				        'search_items'          => 'Search ' . $plural,
				        'all_items'             => 'All ' . $plural,
				        'parent_item'           => 'Parent ' . $name,
				        'parent_item_colon'     => 'Parent ' . $name . ':',
				        'edit_item'             => 'Edit ' . $name,
				        'update_item'           => 'Update ' . $name,
				        'add_new_item'          => 'Add New ' . $name,
				        'new_item_name'         => 'New ' . $name . ' Name',
				        'menu_name'             => $name,
					),

					// Given labels
					$taxonomy_labels
				);

				// Default args, overwrite them with the given labels
				$args = array_merge(
					array(
				        'labels'       => $labels,
						'hierarchical' => true,
						'query_var'    => true,
						'rewrite' 	   => true
					),

					// Given args
					$taxonomy_args
				);

				// Registers taxonomy
				add_action('init', function() use( $taxonomy_name, $post_type_name, $args ) {
						register_taxonomy( $taxonomy_name, $post_type_name, $args );
					}
				);

			} else {
				/* The taxonomy already exists. We are going to attach the existing taxonomy to the object type (post type) */
				add_action('init', function() use( $taxonomy_name, $post_type_name ) {
						register_taxonomy_for_object_type( $taxonomy_name, $post_type_name );
					}
				);
			}
    	}

    } // end of add_taxonomy()

    /* Attaches meta boxes to the post type */
    public function add_meta_box( $title, $fields = array(), $context = 'normal', $priority = 'default' ) {

    	if ( ! empty ( $title ) ) {

    		// We need to know the post type name
    		$post_type_name = $this->post_type_name;

    		// Meta variables
			$box_id       = self::uglify( $title );
			$box_title    = self::beautify( $title );
			$box_context  = $context;
			$box_priority = $priority;

			global $custom_fields;
			$custom_fields[$title] = $fields;

			add_action( 'admin_init',
				function() use( $box_id, $box_title, $post_type_name, $box_context, $box_priority, $fields ) {

					// ( $id, $title, $callback, $post_type, $context, $priority )
					add_meta_box(
						$box_id,
						$box_title,
						function( $post, $data ) {

							global $post;

							// Add an nonce field so we can check for it later
							wp_nonce_field( 'tcu_custom_post_types_action', 'tcu_custom_wpnoncename' );

							$custom_fields = $data['args'][0];

							// Get the saved values
							// $meta = get_post_custom( $post->ID );

			                // Check the array and loop through it
			                if( ! empty( $custom_fields ) ) {

				                // begin table
								echo '<table class="form-table">';
			                    /* Loop through $custom_fields */
			                    foreach( $custom_fields as $field ) {

			                    	//Underscore first to keep it hidden from custom fields
			                    	$prefix = '_tcu_';
			                        $field_id_name  = $prefix . call_user_func("TCU_Register_Post_Type::uglify", $field['name']);

			                        $field['id'] = $field_id_name;

			                        array_push($field, $field['id']);

			                        // Get the value of this field if it exists
				                    $meta = get_post_meta( $post->ID, $field['id'], true );

				                    // begin table
									echo '<tr>
											<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
											<td>';
			                        switch ( $field['type'] ) {

				                        // Text
				                        case 'text':
				                        	echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.esc_attr($meta).'" size="30" /><br /><span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // Email
				                        case 'email':
				                        	echo '<input type="email" name="'.$field['id'].'" id="'.$field['id'].'" value="'.esc_attr($meta).'" size="30" /><br /><span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // Tel
				                        case 'tel':
				                        	echo '<input class="phone" type="tel" name="'.$field['id'].'" id="'.$field['id'].'" value="'.esc_attr($meta).'" size="30" /><br /><span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // URL
				                        case 'url':
				                        	echo '<input type="url" name="'.$field['id'].'" id="'.$field['id'].'" value="'.esc_attr($meta).'" size="30" /><br />
				                        	  <span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // Textarea
				                        case 'textarea':
				                        	echo '<textarea id="'.$field['id'].'" rows="4" class="large-text" name="'.$field['id'].'">'.esc_textarea($meta).'</textarea><br />
				                        	  <span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // Checkbox
				                        case 'checkbox':
				                        	echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ', esc_attr($meta) ? ' checked="checked"' : '','/>
				                        	  <label for="'.$field['id'].'">'.$field['desc'].'</label>';
				                        break;

				                        // Checkbox Group
                                        case 'checkbox_group':

                                            foreach ($field['options'] as $option) {
                                                echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"', esc_attr($meta) && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
                                                        <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                                            }
                                            echo '<span class="description">'.$field['desc'].'</span>';
                                        break;

                                        // Radio
                                        case 'radio':
                                            foreach ( $field['options'] as $option ) {
                                                echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ', esc_attr($meta) == $option['value'] ? ' checked="checked"' : '',' />
                                                        <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                                            }
                                            echo '<span class="description">'.$field['desc'].'</span>';
                                        break;

				                        // Select
				                        case 'select':
				                        	echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
				                        		foreach($field['options'] as $option) {
				                        			echo '<option', esc_attr($meta) == $option['value'] ? ' selected="selected"' : '', ' value="'.esc_attr($option['value']).'">'.$option['label'].'</option>';
				                        		}
				                        		echo '</select><br /><span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // Datepicker
				                        case 'date':
				                        	echo '<input type="text" class="datepicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.esc_attr($meta).'" size="30" />
				                        		<br /><span class="description">'.$field['desc'].'</span>';
				                        break;

				                        // Image
				                        case 'image':
				                        	$image = plugins_url( 'images/image.png', dirname( __FILE__ ) );
				                        	echo '<div class="custom-img-container">';
				                        	echo '<span class="custom-preview-image" style="display:none">'.$image.'</span>';
				                        	if ( empty($meta) ) {
				                        		echo '<img class="custom_media_image '.$field['id'].'" src="'.esc_url($image).'" /><br />';
				                        	}  else {

					                        	echo '<img class="custom_media_image '.$field['id'].'" src="'.esc_url($meta).'" /><br />';
											}
											echo '<a href="#" class="custom_media_upload upload-custom-img button-primary '.$field['id'].'">Upload</a><br /><br />
													<input class="custom_media_url '.$field['id'].'" type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" value="'.esc_attr($meta).'" /><br />
													<small><a class="custom_clear_image_button '.$field['id'].'" href="#">Remove Image</a></small><br />
													<span class="description">'.$field['desc'].'</span>';
											echo '</div>';
				                        break;

				                        // Repeatable List
				                        case 'list':
				                        	echo '<a class="repeatable-add button" href="#">+</a>
				                        			<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
				                        	$i = 0;
				                        	if ($meta) {
				                        		foreach($meta as $row) {
				                        			echo '<li><input type="text" name="'.$field['id'].'['.$i.']" class="'.$field['id'].'" value="'.esc_attr($row).'" size="30">
				                        						<a class="repeatable-remove button" href="#">x</a></li>';
				                        			$i++;
				                        		} // end foreach
				                        	} else {
				                        		echo '<li><input type="text" name="'.$field['id'].'['.$i.']" class="'.$field['id'].'" value="" size="30" />
				                        				<a class="repeatable-remove button" href="#">x</a></li>';
				                        	}
				                        	echo '</ul>
				                        		<span class="description">'.$field['desc'].'</span>';
				                        break;


				                        // WordPress Editor
				                        case 'editor':
				                        	$args = array_merge(
				                        				// Default options
				                        				array(
				                        					'media_buttons' => true,
				                        				), $field['options']
				                        			);
				                        		wp_editor($meta, $field['id'],$args);
				                        break;
			                    	} // end of switch
			                    	echo '</td></tr>';
			                    }// end foreach
			                    echo '</table>';
			                }
			            },
						$post_type_name,
						$box_context,
						$box_priority,
						array( $fields )
					);
				}
			);
    	}

    } // end of add_meta_box()

    /* Listens for when the post type being saved */
    public function save() {

    	// Need the post type name
    	$post_type_name = $this->post_type_name;

    	add_action( 'save_post', function() use( $post_type_name ) {

    			// Deny the WordPress autosave function
    			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

    			// Check if our nonce is set.
				if ( !isset( $_POST['tcu_custom_wpnoncename'] ) || !wp_verify_nonce( $_POST['tcu_custom_wpnoncename'], 'tcu_custom_post_types_action' ) ) {
					return;
				}

    			global $post;

    			if ( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == $post_type_name ) {

    				// Checking user permissions
    				if (!current_user_can('edit_page', $post->ID)) {
    					return $post->ID;
    				} elseif (!current_user_can('edit_post', $post->ID)) {
    					return $post->ID;
    				}

    				global $custom_fields;

    				// Loop through each meta box
    				foreach( $custom_fields as $title => $fields ) {

    					foreach ($fields as $field) {

    						$prefix = '_tcu_';
			                $field_id_name  = $prefix . call_user_func("TCU_Register_Post_Type::uglify", $field['name']);

			                $field['id'] = $field_id_name;

			                array_push($field, $field['id']);

			                $new = '';

							if ( ! empty( $_POST[$field['id']] ) ) {

								if( $field['type'] == 'editor' ) {

									$new = wpautop( wptexturize( $_POST[$field['id']] ) );

								} elseif( $field['type'] == 'checkbox' ) {

									if( isset( $_POST[$field['id']] ) && $_POST[$field['id']] == 'on' ) { $new = sanitize_text_field($_POST[$field['id']]); }

								} elseif( $field['type'] == 'checkbox_group' ) {

									if( !empty( $_POST[$field['id']] ) ) { $new = sanitize_text_field($_POST[$field['id']]); }

								} else {

									$new = sanitize_text_field($_POST[$field['id']]);

								}
							}

							$old = get_post_meta($post->ID, $field['id'], true);

							if( $new && $new != $old ) {

								update_post_meta($post->ID, $field['id'], $new);

							} elseif ( '' == $new && $old ) {

								delete_post_meta($post->ID, $field['id'], $old);

							}

						} // end foreach

    				} // end foreach
    			}
    		}
    	);

    } // end of save()

     public function add_custom_js_css() {

    	// add media uploader to our custom post type
    	//
    	wp_enqueue_media();
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( 'jquery_ui', plugins_url('tcu_custom_post_types/js/min/jquery-ui-min.js'), array('jquery'),'1.11.4', true );
		wp_enqueue_script( 'jquery-maskedinput', plugins_url('tcu_custom_post_types/js/jquery.maskedinput.min.js'), array('jquery'),'1.3.1', true );
		wp_enqueue_script('scripts-helper', plugins_url('tcu_custom_post_types/js/min/scripts-min.js'), array('jquery', 'jquery-maskedinput', 'jquery_ui'), '1.10.4', 'all');
		wp_enqueue_style('jquery-ui-css', plugins_url('tcu_custom_post_types/css/jquery-ui.css'), false, '1.11.4', 'all');
		wp_enqueue_style('tcu-css', plugins_url('tcu_custom_post_types/css/custom-post-types-styles.css'), false, false, 'all');

    }

    public function add_jquery_scripts() {
    	global $custom_fields, $post;

    	// Lets check if we have any custom fields in place
    	if( is_array( $custom_fields ) ) {

	    	foreach( $custom_fields as $title => $fields) {

	    		foreach( $fields as $field ) {

	    			if ( $field['type'] == 'date' ) {

	    				// Lets make sure the script hasn't been loaded yet
						$handle = 'date.js';
						if( wp_script_is($handle, 'done') ) {
							return;
						} else {
							wp_register_script( 'date-helper', plugins_url('tcu_custom_post_types/js/date.js'));
							wp_enqueue_script( 'date-helper' );
						}
	    			}

	    		}

	    	}
	    }

    } // end add_custom_js_css()

    public static function beautify( $string ) {

    	return ucwords( str_replace('_', ' ', $string) );

    } // end of beautify()

    public static function uglify( $string ) {

    	return strtolower( str_replace(' ', '_', $string) );

    } // end of uglify()


} // end of .TCU_Register_Post_Types

?>