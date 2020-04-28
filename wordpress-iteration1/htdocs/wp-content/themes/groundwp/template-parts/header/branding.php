<?php
/**
 * Template part for displaying the header branding
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

?>

<div class="site-branding">
	<div>
	<?php
	/**
	 * Header will only be displaying the logo or site title and tagline, not altogether
	 * Because of this, choose your logo design or site title/tagline according to this design condition
	 */
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		if ( is_front_page() && is_home() ) {
			?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
										  rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
		} else {
			?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
										 rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
		}
		?>

		<?php
		$groundwp_description = get_bloginfo( 'description', 'display' );
		if ( $groundwp_description || is_customize_preview() ) {
			?>
				<p class="site-description">
				<?php echo $groundwp_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
				</p>
				<?php
		}
	}
	?>
		</div>
</div><!-- .site-branding -->
