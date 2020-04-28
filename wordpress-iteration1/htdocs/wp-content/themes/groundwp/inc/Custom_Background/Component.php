<?php
/**
 * GroundWP\GroundWP\Custom_Background\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Custom_Background;

use GroundWP\GroundWP\Component_Interface;
use function add_action;
use function add_theme_support;
use function apply_filters;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'custom_background';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_custom_background_support' ] );
	}

	/**
	 * Adds support for the Custom Background feature.
	 */
	public function action_add_custom_background_support() {
		add_theme_support(
			'custom-background',
			apply_filters(
				'groundwp_custom_background_args',
				[
					'default-color' => 'f8f9fa', // Gray 0.
					'default-image' => '',
				]
			)
		);
	}
}
