<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

get_header();

//groundwp()->print_styles( 'groundwp-content' );

$blog_layout = '';
if ( groundwp()->is_blog_layout_applicable() ) {
	$blog_layout = groundwp()->get_blog_layout();
}

?>
	<main id="primary"
		  class="site-main 
			<?php
			echo $blog_layout; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		  ">
		<?php
		if ( have_posts() ) {

			get_template_part( 'template-parts/content/page_header' );

			if ( groundwp()->is_blog_layout_applicable() && 'masonry-grid' === groundwp()->get_blog_layout() ) {
				?>
				<div class="masonry-container">
				<?php
			}

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content/entry', get_post_type() );

			}

			if ( groundwp()->is_blog_layout_applicable() && 'masonry-grid' === groundwp()->get_blog_layout() ) {
				?>
				</div><!-- .masonry-container -->
				<?php
			}

			if ( ! is_singular() || is_archive() ) {
				if ( groundwp()->is_infinite_scroll_enabled() ) {
					get_template_part( 'template-parts/components/infinite_scroll' );
				} else {
					get_template_part( 'template-parts/content/pagination' );
				}
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}

		// scroll_to_top component.
		groundwp()->render_scroll_to_top();
		?>
	</main><!-- #primary -->
	<?php
	get_sidebar();
	get_footer();
