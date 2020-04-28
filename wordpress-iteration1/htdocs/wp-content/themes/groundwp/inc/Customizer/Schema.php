<?php
/**
 * GroundWP\GroundWP\Customizer\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Customizer;

use GroundWP\GroundWP\SchemaBase_Interface;
use function GroundWP\GroundWP\groundwp;

/**
 * Schema for Customizer
 */
class Schema implements SchemaBase_Interface {
	/**
	 * Add schemas to Schema component
	 *
	 * @return array schemas
	 */
	public static function add_schema() {
		return [
			'customizer' => [
				'scripts' => [
					'main_preview' => 'groundwp-main-customize-preview.min.js',
					'main_control' => 'groundwp-main-customize-control.min.js',
				],
				'panels' => [
					'theme_options' => [
						'slug' => 'theme_options',
					],
					'extra_options' => [
						'slug' => groundwp()->get_default_prefix( 'extra_options' ),
					],
					'layout_options' => [
						'slug' => groundwp()->get_default_prefix( 'layout_options' ),
					],
					'blog_options' => [
						'slug' => groundwp()->get_default_prefix( 'blog_options' ),
					],
					'performance_optimizations' => [
						'slug' => groundwp()->get_default_prefix( 'performance_optimizations' ),
					],
				],
				'styles' => [
					'file' => 'groundwp-customizer.min.css',
				],
				'elements' => [
					'panel' => [
						'id' => 'accordion-panel-' . groundwp()->get_default_prefix( '' ),
					],
					'control' => [
						'id' => 'customize-control-' . groundwp()->get_default_prefix( '' ),
					],
				],
				'settings' => [
					'horizontal_ruler' => [
						'slug' => groundwp()->get_default_prefix( 'horizontal_ruler' ),
					],
					'control_group' => [
						'slug' => groundwp()->get_default_prefix( 'control_group' ),
					],
				],
			],
		];
	}

}
