<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php
	if ( ! groundwp()->is_amp() ) {
		?>
		<script>document.documentElement.classList.remove('no-js');</script>
		<?php
	}
	?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'groundwp' ); ?></a>

	<header id="masthead" class="site-header <?php echo esc_attr( groundwp()->logo_position_class() ); ?>">
		<div class="header-container <?php echo esc_attr( groundwp()->header_width_class() ); ?>">
			<?php get_template_part( 'template-parts/header/branding' ); ?>
			<div class="header-sub-container"><?php get_template_part( 'template-parts/header/navigation' ); ?>
				<?php get_template_part( 'template-parts/header/call-to-action' ); ?></div>
		</div><!-- .header-container -->
	</header><!-- #masthead -->

	<div class="content-container">
