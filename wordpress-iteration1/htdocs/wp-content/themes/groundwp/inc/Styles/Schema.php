<?php
/**
 * GroundWP\GroundWP\Styles\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Styles;

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
			'styles' => [
				'section' => [
					'slug' => groundwp()->get_default_prefix( 'performance_css_section' ),
				],
				'settings' => [
					'inline_css' => [
						'slug' => groundwp()->get_default_prefix( 'inline_css' ),
						'default' => false,
					],
				],
			],
		];
	}

}
