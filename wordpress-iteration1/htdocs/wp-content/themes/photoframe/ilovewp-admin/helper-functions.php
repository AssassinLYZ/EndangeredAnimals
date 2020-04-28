<?php

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_breadcrumbs' ) ) {
	function ilovewp_helper_display_breadcrumbs() {

		// CONDITIONAL FOR "Breadcrumb NavXT" plugin OR Yoast SEO Breadcrumbs
		// https://wordpress.org/plugins/breadcrumb-navxt/

		if ( function_exists('bcn_display') ) { ?>
		<div class="site-breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
			<p class="site-breadcrumbs-p"><?php bcn_display(); ?></p>
		</div><!-- .site-breadcrumbs--><?php }

		// CONDITIONAL FOR "Yoast SEO" plugin, Breadcrumbs feature
		// https://wordpress.org/plugins/wordpress-seo/
		if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb('<div class="site-breadcrumbs"><p class="site-breadcrumbs-p">','</p></div>');
		}

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_title' ) ) {
	function ilovewp_helper_display_title($post) {

		if( ! is_object( $post ) ) return;
		the_title( '<h1 class="page-title"><span class="page-title-span">', '</span></h1>' );
	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_datetime' ) ) {
	function ilovewp_helper_display_datetime($post) {
		
		if( ! is_object( $post ) ) return;

		return '<p class="entry-descriptor"><span class="entry-descriptor-span"><time class="entry-date published" datetime="' . esc_attr(get_the_date('c')) . '">' . get_the_date() . '</time></span></p>';

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_excerpt' ) ) {
	function ilovewp_helper_display_excerpt($post) {

		if( ! is_object( $post ) ) return;

		return '<p class="entry-excerpt">' . get_the_excerpt() . '</p>';

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_readmore' ) ) {
	function ilovewp_helper_display_readmore($post) {

		if( ! is_object( $post ) ) return;

		return '<span class="site-readmore-span"><a href="' . esc_url( get_permalink($post) ) . '" class="site-readmore-anchor">' . __('Read More','photoframe') . '</a></span>';

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_comments' ) ) {
	function ilovewp_helper_display_comments($post) {

		if( ! is_object( $post ) ) return;

		if ( comments_open() || get_comments_number() ) :

			comments_template();

		endif;

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_content' ) ) {
	function ilovewp_helper_display_content($post) {

		if( ! is_object( $post ) ) return;

		echo '<div class="entry-content">';
			
			the_content();
			
			wp_link_pages(array('before' => '<p class="page-navigation"><strong>'.__('Pages', 'photoframe').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number'));

		echo '</div><!-- .entry-content -->';

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_tags' ) ) {
	function ilovewp_helper_display_tags($post) {

		if( ! is_object( $post ) ) return;

		if ( get_post_type($post->ID) == 'post' ) { 
			the_tags( '<p class="post-meta post-tags"><strong>'.__('Tags', 'photoframe').':</strong> ', ', ', '</p>');
		}

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_postmeta' ) ) {
	function ilovewp_helper_display_postmeta($post) {

		if( ! is_object( $post ) ) return;

		if ( get_post_type($post->ID) == 'post' ) { 

			echo '<p class="entry-tagline">';
			echo '<span class="post-meta-span post-meta-span-category">'; the_category(', '); echo '</span>';
			echo '<span class="post-meta-span post-meta-span-time"><time datetime="' . esc_attr(get_the_time("Y-m-d")) . '" pubdate>' . esc_html(get_the_time(get_option('date_format'))) . '</time></span>';
			echo '</p><!-- .entry-tagline -->';

		}

	}
}

// Content Column Wrapper Start
if( ! function_exists( 'ilovewp_helper_display_page_content_wrapper_start' ) ) {
	function ilovewp_helper_display_page_content_wrapper_start() {

		?><div class="site-column site-column-content"><div class="site-column-wrapper clearfix"><!-- .site-column .site-column-1 .site-column-aside --><?php

	}
}

// Content Column Wrapper Start
if( ! function_exists( 'ilovewp_helper_display_page_content_wrapper_end' ) ) {
	function ilovewp_helper_display_page_content_wrapper_end() {

		?></div><!-- .site-column-wrapper .clearfix --></div><!-- .site-column .site-column-content --><?php

	}
}

// Page/Post Title
if( ! function_exists( 'ilovewp_helper_display_hero_header' ) ) {
	function ilovewp_helper_display_hero_header() {

		if ( is_singular() && has_post_thumbnail() ) {
			
			$themeoptions_display_featured_hero = esc_attr(get_theme_mod( 'photoframe-display-featured-hero', '0' ));

			if ( $themeoptions_display_featured_hero != '1' ) {
				return;
			}

			while (have_posts()) : the_post(); 

				global $post;

				$img_url = get_the_post_thumbnail_url($post,'photoframe-thumb-featured');
		?>
		<div id="site-hero-image">
			<div class="site-hero-slideshow-wrapper">
				<ul class="site-hero-slideshow-list">
					<?php
					echo '<li class="site-hero-slideshow-item" style="background-image: url(' . esc_url($img_url) . '); background-position: 50%;"></li>';
					?>
				</ul><!-- .site-hero-slideshow-list -->
			</div><!-- .site-hero-slideshow-wrapper -->
		</div><!-- #site-hero-image -->
		<?php
			endwhile;
		} // if is_singular()
		elseif ( is_home() ) {
			$header_images = get_uploaded_header_images();

			if ( $header_images && count( $header_images ) > 0 ) {
				echo '<div id="site-hero-image"';
				
				if ( count($header_images) >1 ) { echo 'class="site-hero-slideshow"'; }
				
				echo '><div class="site-hero-slideshow-wrapper"><ul class="site-hero-slideshow-list">';
				foreach ( $header_images as $header_image ) {
					$img_url = wp_get_attachment_image_url( $header_image['attachment_id'], 'photoframe-thumb-featured' );
					echo '<li class="site-hero-slideshow-item" style="background-image: url(' . esc_url($img_url) . '); background-position: 50%;"></li><!-- .site-hero-slideshow-item -->';
				}
				echo '</ul><!-- .site-hero-slideshow-list --></div><!-- .site-hero-slideshow-wrapper --></div><!-- #site-hero-image -->';
			}
		}

	}
}

// Get Page Slideshow Status
if( ! function_exists( 'ilovewp_helper_get_page_slideshow' ) ) {
	function ilovewp_helper_get_page_slideshow() {

		$default_class = 'page-without-slideshow';
		if ( is_singular() && has_post_thumbnail() ) {
			$themeoptions_display_featured_hero = esc_attr(get_theme_mod( 'photoframe-display-featured-hero', '0' ));
			if ( $themeoptions_display_featured_hero == '1' ) {
				$default_class = 'page-with-slideshow';
			}
		} elseif ( is_singular() && !has_post_thumbnail() ) {
			$default_class = 'page-without-slideshow';
		} elseif ( is_home() ) {
			
			$header_images = get_uploaded_header_images();
			if ( $header_images && count( $header_images ) > 0 ) {
				$default_class = 'page-with-slideshow';
			}

		}

		return $default_class;
	}
}

// Get Header Style
if( ! function_exists( 'ilovewp_helper_get_header_style' ) ) {
	function ilovewp_helper_get_header_style() {

		$themeoptions_header_style = esc_attr(get_theme_mod( 'theme-header-style', 'default' ));

		if ( $themeoptions_header_style == 'default' ) {
			$default_position = 'page-header-default';
		} elseif ( $themeoptions_header_style == 'centered' ) {
			$default_position = 'page-header-centered';
		}

		return $default_position;
	}
}