<?php
/**
 * Template part for displaying the footer info
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

?>

<div class="site-info">
	<a href="
	<?php
	use const wp_get_theme;

	echo esc_url( __( 'https://wordpress.org/', 'groundwp' ) );
	?>
	">
		<?php
		/* translators: %s: CMS name, i.e. WordPress. */
		printf( esc_html__( 'Proudly powered by %s', 'groundwp' ), 'WordPress' );
		?>
	</a>
	<span class="sep"> | </span>
	<?php
	$theme_uri = wp_get_theme()->get( 'ThemeURI' );
	/* translators: Theme name. */
	printf( esc_html__( 'Theme: %s by the contributors.', 'groundwp' ), '<a href="' . esc_url( $theme_uri ) . '">GroundWP</a>' );

	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '<span class="sep"> | </span>' );
	}
	?>
</div><!-- .site-info -->
