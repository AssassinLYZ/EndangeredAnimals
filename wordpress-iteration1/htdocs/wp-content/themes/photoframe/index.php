<?php get_header(); ?>

<main id="site-main">

	<?php if ( ( is_front_page() || is_home() ) && !is_paged() ) {
	
	if ( is_active_sidebar('homepage-welcome-widgets-left') || is_active_sidebar('homepage-welcome-widgets-right') ) { ?>

		<div id="site-home-welcome">

			<div class="site-section-wrapper site-section-page-welcome-wrapper clearfix">

				<div class="site-columns site-columns-2 clearfix">
					<div class="site-column site-column-1">
						<div class="site-column-wrapper clearfix">

							<?php dynamic_sidebar( 'homepage-welcome-widgets-left' ); ?>

						</div><!-- .site-column-wrapper .clearfix -->
					</div><!-- .site-column .site-column-1 --><!-- ws fix
					--><div class="site-column site-column-2">
						<div class="site-column-wrapper clearfix">

							<?php dynamic_sidebar( 'homepage-welcome-widgets-right' ); ?>

						</div><!-- .site-column-wrapper .clearfix -->
					</div><!-- .site-column .site-column-2 -->
				</div><!-- .site-columns .site-columns-2 .clearfix -->

			</div><!-- .site-section-wrapper .site-section-page-welcome-wrapper .clearfix -->

		</div><!-- #site-home-welcome --><?php }

		if ( 1 == get_theme_mod( 'photoframe-display-pages', 0 ) ) {
			get_template_part( 'template-parts/content', 'home-featured' );
		} // if featured pages are activated

		if ( is_active_sidebar('homepage-welcome-above-recent-posts') ) { ?>
		<div id="site-home-pre-posts">

			<div class="site-section-wrapper site-section-pre-posts-wrapper clearfix">

			<?php dynamic_sidebar( 'homepage-welcome-above-recent-posts' ); ?>

			</div><!-- .site-section-wrapper .site-section-pre-posts-wrapper .clearfix -->

		</div><!-- #site-home-pre-posts -->

		<?php }

	} // if is frontpage 
	if ( 1 == get_theme_mod( 'photoframe-display-recentposts', 1 ) ) { ?>

	<div class="site-page-content">
		<div class="site-section-wrapper site-section-wrapper-main clearfix">

			<?php

			// Function to display the START of the content column markup
			ilovewp_helper_display_page_content_wrapper_start(); ?>

			<?php 
			if ( have_posts() ) { 
				$i = 0; 
			
				if ( is_home() && ! is_front_page() ) { ?>
				<h1 class="page-title archives-title"><?php single_post_title(); ?></h1>
				<?php } ?>

				<?php if ( is_home() ) { ?><p class="page-title archives-title"><span class="page-title-span"><?php esc_html_e('Recent Posts','photoframe'); ?></span></p><?php } ?>

				<?php get_template_part('loop');

			} else {
				if ( is_home() ) { ?><p class="page-title archives-title"><span class="page-title-span"><?php esc_html_e('Recent Posts','photoframe'); ?></span></p>
				<p><?php esc_html_e( 'You currently have no published posts.', 'photoframe' ); ?></p><?php }
			}

			// Function to display the END of the content column markup
			ilovewp_helper_display_page_content_wrapper_end();

			?>

		</div><!-- .site-section-wrapper .site-section-wrapper-main -->
	</div><!-- .site-page-content --><?php } ?>

</main><!-- #site-main -->

<?php get_footer(); ?>