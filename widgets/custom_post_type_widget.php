<?php
/**
 * Widget that queries through the latest custom post type posts
 *
 * @author Mayra Perales <m.j.perales@tcu.edu>
 */

// Load our widget
add_action( 'widgets_init', function() {
	register_widget( 'TCU_Custom_Post_Type_Feed' );
});

class TCU_Custom_Post_Type_Feed extends WP_Widget
{
	/**
	 * Register widget with WordPress
	 */
	public function __construct() {
		parent::__construct(
				'tcu_custom_feed', // Base ID
				__('TCU Custom Feed'), // Name
				array( 'description' => __('Pull a custom feed') ) // Args
			);

	}

	/**
	 * Front-end display of widget
	 * @see WP_Widget::widget()
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		// these are the widget options
		$title          = apply_filters( 'widget_title', $instance['title'] );
		$post_type      = $instance['post_type'];
		$posts_per_page = $instance['posts_per_page'];
		$order          = $instance['order'];
		$display_date   = $instance['display_date'];

		global $post;

		// Before widget (defined by themes)
		echo $before_widget;

		// check if title is set
		if( $title ) {
			// Title of widget (before and after defined by themes)
			echo $before_title . $title . $after_title;
		}

		// Check post type
		if( $post_type == 'Select...' || $post_type == '' ) {
			echo '<p>Error! Please make sure you select a custom post type.</p>';
		}

 		// Check if a post type was selected
		if( $post_type ) {

			// Lets check if anything has been selected
			if( $order == 'Select...' || $order == '' ) {

				// WP_Query args
				$cpt_args = array(
					'post_type'      => $post_type,
					'posts_per_page' => $posts_per_page,
					'order'          => 'ASC'
				);
			} else {

				// WP_Query args
				$cpt_args = array(
					'post_type'      => $post_type,
					'posts_per_page' => $posts_per_page,
					'order'          => $order
				);
			}

			// Query our posts
			$tcu_query_feed_results = get_posts( $cpt_args );
			$output = '<ul class="tcu-cpt-feed">';

			if( $display_date == 'on') {

				foreach( $tcu_query_feed_results as $post ) {

					setup_postdata( $post );
					$output .= '<li>';
					$output .= '<div class="tcu-cpt-feed_date">'.get_the_date().'</div>';
					$output .= '<a class="tcu-cpt-feed_title" href="'.get_permalink().'">'.get_the_title().'</a>';
					$output .= '</li>';
				}

			} else {

				foreach( $tcu_query_feed_results as $post ) {

					setup_postdata( $post );
					$output .= '<li>';
					$output .= '<a class="tcu-cpt-feed_title" href="'.get_permalink().'">'.get_the_title().'</a>';
					$output .= '</li>';
				}

			}

			wp_reset_postdata();
			$output .= '</ul><!-- end of tcu-cpt-feed -->';
			echo $output;
		}

		// After widget (defined by themes)
		echo $after_widget;

	}

	/**
	 * Back-end widget form
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database
	 */
	public function form( $instance ) {

		// check our values
		if( $instance ) {
			$title          = esc_attr( $instance['title'] );
			$post_type      = esc_attr( $instance['post_type'] );
			$posts_per_page = esc_attr( $instance['posts_per_page'] );
			$order          = esc_attr( $instance['order'] );
			$display_date   = esc_attr( $instance['display_date'] );
		} else {
			$title          = '';
			$post_type      = '';
			$posts_per_page = '';
			$order          = '';
			$display_date   = '';
		} ?>

		<p>
		<label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title:') ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type Name:'); ?></label>
		<select name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_name('post_type'); ?>" class="widefat">
			<?php
				// Lets pull a list of our registered custom post types
				$reg_cpt_args = array(
					'public'   => true,
		   			'_builtin' => false
		   		);
				$list_cpts = get_post_types( $reg_cpt_args, 'names' );
				array_unshift( $list_cpts, 'Select...' );

				foreach ($list_cpts as $key => $value) {
					echo '<option value="'.$value.'" id="'.$value.'"',$post_type == $value ? ' selected="selected" ' : '','>',ucfirst($value),'</option>';
				}

			?>
		</select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input class="widefat" id="<?php $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" type="text" value="<?php echo $posts_per_page; ?>">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:'); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
			<?php
				$options = array( 'Select...', 'DESC', 'ASC' );
				foreach( $options as $option ) {
					echo '<option value="' . $option . '" id="' . $option . '"', $order == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
			?>
		</select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('display_date'); ?>"><?php _e('Display post date?'); ?></label>
		<?php echo '<input name="'.$this->get_field_name('display_date').'" id"'.$this->get_field_id('display_date').'" type="checkbox" ',$display_date == 'on' ? ' checked="checked" ' : '',' class="widefat">'; ?>
		</p>
	<?php }

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved
	 * @param array $old_instance Previously saved values from database
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// fields
		$instance['title']          = strip_tags($new_instance['title']);
		$instance['post_type']      = strip_tags($new_instance['post_type']);
		$instance['posts_per_page'] = strip_tags($new_instance['posts_per_page']);
		$instance['order']          = strip_tags($new_instance['order']);
		$instance['display_date']   = strip_tags($new_instance['display_date']);
		return $instance;

	}

}


?>