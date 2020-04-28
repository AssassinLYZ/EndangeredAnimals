<?php
/**
 * GroundWP functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package groundwp
 */

define( 'GROUNDWP_MINIMUM_WP_VERSION', '4.5' );
define( 'GROUNDWP_MINIMUM_PHP_VERSION', '5.6' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], GROUNDWP_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), GROUNDWP_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * Changed function signature to match WordPress theme guidelines
	 * (all global space theme functions should be prefixed with theme slug)
	 *
	 * @access private
	 *
	 * @since groundwp
	 *
	 * @param string $class_name Class name to load.
	 * @return bool True if the class was loaded, false otherwise.
	 */
	function groundwp_autoload( $class_name ) {
		$namespace = 'GroundWP\GroundWP';

		if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
			return false;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

		$path = get_template_directory() . '/inc';
		foreach ( $parts as $part ) {
			$path .= '/' . $part;
		}
		$path .= '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		require_once $path;

		return true;
	}
	spl_autoload_register( 'groundwp_autoload' );
}

// Load the `groundwp()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'GroundWP\GroundWP\groundwp' );
