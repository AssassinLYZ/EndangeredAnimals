<?php
/**
 * Template Name: Page Builder Template
 */

get_header();
?>

<main id="site-main">

	<?php
	while (have_posts()) : the_post();
	?>

	<div class="site-page-content site-page-fullwidth">
		<div class="site-section-wrapper site-section-wrapper-main clearfix">

			<?php
			// Function to display the START of the content column markup
			ilovewp_helper_display_page_content_wrapper_start();

				ilovewp_helper_display_content($post);

			// Function to display the END of the content column markup
			ilovewp_helper_display_page_content_wrapper_end();

			?>

		</div><!-- .site-section-wrapper .site-section-wrapper-main -->
	</div><!-- .site-page-content -->

	<?php
	endwhile;
	?>

</main><!-- #site-main -->
	
<?php get_footer(); ?>