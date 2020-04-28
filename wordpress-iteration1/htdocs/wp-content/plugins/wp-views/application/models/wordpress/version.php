<?php

namespace OTGS\Toolset\Views\Model\Wordpress;

/**
 * Wrapper for WordPress WP_Error class interaction
 *
 * @since 2.8.1
 */
class Version {

	/**
	 * Generate a \WP_Error instance
	 *
	 * @param string $version Version to compare against
	 * @return int Returns -1 if current version is owe than $version,
	 *     0 if equals,
	 *     1 if current version is higher than $version.
	 */
	public function compare( $version ) {
		global $wp_version;
		return version_compare( $wp_version, $version );
	}

}
