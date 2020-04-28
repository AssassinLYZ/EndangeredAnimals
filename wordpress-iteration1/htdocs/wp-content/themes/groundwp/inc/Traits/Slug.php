<?php
/**
 * GroundWP\GroundWP\Traits\Slug class.
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Traits;

/**
 * Easy slug Generation for Component_Interface.
 */
trait Slug {
	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		$classname_to_path = str_replace( '\\', '/', __CLASS__ );

		return strtolower( basename( dirname( $classname_to_path ) ) );
	}
}

