<?php
/**
 * GroundWP\GroundWP\Base_Support\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Base_Support;

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
			'base_support' => [
				'section' => [
					'slug' => groundwp()->get_default_prefix( 'cache_options_section' ),
				],
				'settings' => [
					'enable_cache' => [
						'slug' => groundwp()->get_default_prefix( 'enable_cache' ),
						'default' => false,
					],
					'cache_duration' => [
						'slug' => groundwp()->get_default_prefix( 'cache_duration' ),
						'default' => 31536000,
					],
				],
			],
		];
	}

}
