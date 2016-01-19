<?php
/*

SINGLE TEMPLATE: VIEW OF ONE POSTS

This is the custom post type SINGLE PAGE TEMPLATE.
If you edit the post type name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom post type is called
expert, then your single template should be
single-expert.php

This file needs to be styled according to
your theme. This is ONLY an example on how to pull
meta data from the database for display.

IMPORTANT: This file should be saved in the root of
your theme directory. For example, if your theme is called
coe_kinderfrogs then this file should be within the root
of that directory. You should ALWAYS flush rewrites by
going to Settings > Permalinks > Save Changes.

*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">

									<h1 class="single-title custom-post-type-title"><?php the_title(); ?></h1>
									<p class="byline vcard"><?php
										printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time>', 'bonestheme' ), get_the_time( 'Y-m-j' ), get_the_time( __( 'F jS, Y', 'bonestheme' ) ), bones_get_the_author_posts_link(), get_the_term_list( $post->ID, 'custom_cat', ' ', ', ', '' ) );
									?></p>

								</header> <!-- end article header -->

								<section class="entry-content clearfix">

									<?php
										/* Notice how we are using the name given when we register the meta data */

										$text_ie         = get_post_meta($post->ID, '_tcu_text_name', true);
										$email_ie        = get_post_meta($post->ID, '_tcu_email_name', true);
										$phone_ie        = get_post_meta($post->ID, '_tcu_telephone_name', true);
										$website_ie      = get_post_meta($post->ID, '_tcu_website_name', true);
										$textarea_ie     = get_post_meta($post->ID, '_tcu_textarea_name', true);
										$image_ie        = get_post_meta($post->ID, '_tcu_image_name', true);
										$single_checkbox = get_post_meta($post->ID, '_tcu_single_checkbox', true);
										$group_checkbox  = get_post_meta($post->ID, '_tcu_group_checkbox', true);
										$radio_ie        = get_post_meta($post->ID, '_tcu_radio', true);
										$date_ie         = get_post_meta($post->ID, '_tcu_date', true);
										$select_ie       = get_post_meta($post->ID, '_tcu_select', true);
										$list_ie         = get_post_meta($post->ID, '_tcu_list', true);
										$wp_editor       = get_post_meta($post->ID, '_tcu_wordpress_editor', true);

									?>

									<p>First Name: <?php echo $text_ie; ?></p>
									<p>Email Address: <?php echo $email_ie; ?></p>
									<p>Phone Number: <?php echo $phone_ie; ?></p>

									<!-- You can use this data inside a link tag -->
									<p><a href="<?php echo $website_ie; ?>">Email Address Link</a></p>
									<p>Website Address: <?php echo $website_ie; ?></p>
									<!-- end of link example -->

									<!-- You can use this URL inside an img tag -->
									<p><img src="<?php echo $image_ie; ?>" alt="Image Title" /></p>
									<p>Image url: <?php echo $image_ie; ?></p>
									<!-- end of img tag example -->

									<!-- Textarea -->
									<p><?php echo $textarea_ie; ?></p>

									<!-- example on viewing if checkbox is checked -->
									<?php if( $single_checkbox == 'on' ) { ?>
									<p>Single Checkbox: <?php echo $single_checkbox; ?></p>
									<?php } ?>
									<!-- end of checkbox -->

									<!-- must loop through each option -->
									<p>Group Checkbox</p>
									<ul>
										<?php foreach( $group_checkbox as $option ) { ?>
											<li>Group Checkbox Option: <?php echo $option; ?></li>
										<?php } ?>
									</ul>
									<!-- end of loop -->

									<p>Radio Checkbox: <?php echo $radio_ie; ?></p>

									<!-- to format the date differently then check date() documentation -->
									<p>Date: <?php echo date("F j, Y", strtotime($date_ie)); ?></p>
									<!-- end of date -->

									<p>Select: <?php echo $select_ie; ?></p>

									<!-- must loop through each option -->
									<p>List</p>
									<ol>
										<?php foreach( $list_ie as $item ) { ?>
											<li>Item on List: <?php echo $item; ?></li>
										<?php } ?>
									</ol>
									<!-- end of loop -->

									<div>WP Editor: <?php echo $wp_editor; ?></div>

									<!-- to pull the content -->
									<?php the_content(); ?>

								</section> <!-- end article section -->

							</article> <!-- end article -->

							<?php endwhile; ?>

							<?php else : ?>

								<article id="post-not-found" class="hentry clearfix">
									<header class="article-header">
										<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
									</header>
									<section class="entry-content">
										<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
									</section>
									<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single-custom_type.php template.', 'bonestheme' ); ?></p>
									</footer>
								</article>

							<?php endif; ?>

						</div> <!-- end #main -->

						<?php get_sidebar(); ?>

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
