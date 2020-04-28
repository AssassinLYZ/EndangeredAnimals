<?php
/**
 * GroundWP\GroundWP\Editor\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Editor;

use GroundWP\GroundWP\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'editor';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_editor_support' ] );
	}

	/**
	 * Adds support for various editor features.
	 */
	public function action_add_editor_support() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for wide-aligned images.
		add_theme_support( 'align-wide' );

		/**
		 * Add support for color palettes.
		 *
		 * To preserve color behavior across themes, use these naming conventions:
		 * - Use primary and secondary color for main variations.
		 * - Use `theme-[color-name]` naming standard for standard colors (red, blue, etc).
		 * - Use `custom-[color-name]` for non-standard colors.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-colors' );
		 */
		add_theme_support(
			'editor-color-palette',
			[
				[
					'name'  => __( 'Blue', 'groundwp' ),
					'slug'  => 'theme-blue',
					'color' => '#339af0',
				],
				[
					'name'  => __( 'Black', 'groundwp' ),
					'slug'  => 'theme-black',
					'color' => '#212529',
				],
				[
					'name'  => __( 'Red', 'groundwp' ),
					'slug'  => 'theme-red',
					'color' => '#ff6b6b',
				],
				[
					'name'  => __( 'Grape', 'groundwp' ),
					'slug'  => 'theme-grape',
					'color' => '#cc5de8',
				],
				[
					'name'  => __( 'Orange', 'groundwp' ),
					'slug'  => 'theme-orange',
					'color' => '#ff922b',
				],
				[
					'name'  => __( 'Lime', 'groundwp' ),
					'slug'  => 'theme-lime',
					'color' => '#94d82d',
				],
				[
					'name'  => __( 'Green', 'groundwp' ),
					'slug'  => 'theme-green',
					'color' => '#51cf66',
				],
				[
					'name'  => __( 'Cyan', 'groundwp' ),
					'slug'  => 'theme-cyan',
					'color' => '#22b8cf',
				],
				[
					'name'  => __( 'Pink', 'groundwp' ),
					'slug'  => 'theme-pink',
					'color' => '#f06595',
				],
				[
					'name'  => __( 'White', 'groundwp' ),
					'slug'  => 'theme-white',
					'color' => '#ffffff',
				],
			]
		);

		/*
		 * Add support custom font sizes.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-font-sizes' );
		 */
		add_theme_support(
			'editor-font-sizes',
			[
				[
					'name'      => __( 'Small', 'groundwp' ),
					'shortName' => __( 'S', 'groundwp' ),
					'size'      => 16,
					'slug'      => 'small',
				],
				[
					'name'      => __( 'Medium', 'groundwp' ),
					'shortName' => __( 'M', 'groundwp' ),
					'size'      => 18,
					'slug'      => 'medium',
				],
				[
					'name'      => __( 'Large', 'groundwp' ),
					'shortName' => __( 'L', 'groundwp' ),
					'size'      => 28,
					'slug'      => 'large',
				],
				[
					'name'      => __( 'Larger', 'groundwp' ),
					'shortName' => __( 'XL', 'groundwp' ),
					'size'      => 38,
					'slug'      => 'larger',
				],
			]
		);
	}
}
