<?php
/**
 * The template for displaying 500 pages (internal server errors)
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

get_header();

//groundwp()->print_styles( 'groundwp-content' );

?>
	<main id="primary" class="site-main">
		<?php get_template_part( 'template-parts/content/error', '500' ); ?>
	</main><!-- #primary -->
<?php
get_footer();
