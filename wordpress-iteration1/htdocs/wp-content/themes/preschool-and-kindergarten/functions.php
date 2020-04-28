<?php
/**
 * Preschool and Kindergarten functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Preschool_and_Kindergarten
 */

$theme_data = wp_get_theme();
if ( ! defined( 'PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION' ) ) define ( 'PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION', $theme_data->get( 'Version' ) );
if ( ! defined( 'PRESCHOOL_AND_KINDERGARTEN_THEME_NAME' ) ) define( 'PRESCHOOL_AND_KINDERGARTEN_THEME_NAME', $theme_data->get( 'Name' ) );

/**
 * * Custom template function for this theme.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Implement the WordPress Hooks.
 */
require get_template_directory() . '/inc/wp-hooks.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 ** Custom template functions for this theme.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Implement the template hooks.
 */
require get_template_directory() . '/inc/template-hooks.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/widgets/widgets.php';
/*
* custom function for metabox.
*/
require get_template_directory() . '/inc/metabox.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Info Section
 */
require get_template_directory() . '/inc/info.php';

/**
 * Getting Started
*/
require get_template_directory() . '/inc/getting-started/getting-started.php';

/**
 * WooCommerce Related funcitons
*/
if( preschool_and_kindergarten_is_woocommerce_activated() )
require get_template_directory() . '/inc/woocommerce-functions.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

