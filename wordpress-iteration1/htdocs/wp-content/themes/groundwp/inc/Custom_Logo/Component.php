<?php
/**
 * GroundWP\GroundWP\Custom_Logo\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Custom_Logo;

use GroundWP\GroundWP\Component_Interface;
use function add_action;
use function add_theme_support;
use function apply_filters;

/**
 * Class for adding custom logo support.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'custom_logo';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_custom_logo_support' ] );
	}

	/**
	 * Adds support for the Custom Logo feature.
	 */
	public function action_add_custom_logo_support() {
		add_theme_support(
			'custom-logo',
			apply_filters(
				'groundwp_custom_logo_args',
				[
					'width'       => 180,
					'height'      => 60,
					'flex-width'  => true,
					'flex-height' => true,
					'header-text' => [ 'site-title', 'site-description' ],
				]
			)
		);
	}
}
