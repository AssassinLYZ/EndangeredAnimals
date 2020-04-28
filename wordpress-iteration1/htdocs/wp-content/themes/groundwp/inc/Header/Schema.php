<?php
/**
 * GroundWP\GroundWP\Header\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Header;

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
			'header' => [
				'script_id' => 'stickyHeader',
				'sections' => [
					'header_section' => [
						'slug' => groundwp()->get_default_prefix( 'header_section' ),
					],
				],
				'settings' => [
					'sticky_enable' => [
						'slug'    => groundwp()->get_default_prefix( 'sticky_enable' ),
						'default' => false,
					],
				],
			],
		];

	}

}
