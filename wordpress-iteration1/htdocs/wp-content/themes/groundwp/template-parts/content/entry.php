<?php
/**
 * Template part for displaying a post
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

$card_spaced = ( is_singular( get_post_type() ) ) ? 'card--spaced' : '';

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( [ 'entry', 'card', $card_spaced, 'card--2dp' ] ); ?>>
		<?php
		get_template_part( 'template-parts/content/entry_header', get_post_type() );

		if ( ! is_singular( get_post_type() ) ) {
			get_template_part( 'template-parts/content/entry_summary', get_post_type() );
		} else {
			get_template_part( 'template-parts/content/entry_content', get_post_type() );
			get_template_part( 'template-parts/content/entry_footer', get_post_type() );
		}
		?>
	</article><!-- #post-<?php the_ID(); ?> -->

	<?php
	// related posts.
	if ( is_singular( get_post_type() ) && groundwp()->is_related_posts_enabled() ) {
		get_template_part( 'template-parts/content/entry_related', get_post_type() );
	}

	if ( is_singular( get_post_type() ) ) {
		// Show post navigation only when the post type is 'post' or has an archive.
		if ( 'post' === get_post_type() || get_post_type_object( get_post_type() )->has_archive ) {
			the_post_navigation(
				[
					'prev_text' => '<div class="post-navigation-sub"><span>' . esc_html__( 'Previous:', 'groundwp' ) . '</span></div>%title',
					'next_text' => '<div class="post-navigation-sub"><span>' . esc_html__( 'Next:', 'groundwp' ) . '</span></div>%title',
				]
			);
		}

		// Show comments only when the post type supports it and when comments are open or at least one comment exists.
		if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
			comments_template();
		}
	}
