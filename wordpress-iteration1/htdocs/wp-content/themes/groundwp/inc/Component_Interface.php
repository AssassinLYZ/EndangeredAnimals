<?php
/**
 * GroundWP\GroundWP\Component_Interface interface
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

/**
 * Interface for a theme component.
 */
interface Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug();

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize();
}
