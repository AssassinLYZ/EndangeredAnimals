<?php
/**
 * GroundWP\GroundWP\ScrollTop\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\ScrollTop;

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
			'scroll_to_top' => [
				'script_id' => 'scrollToTop',
				'section' => groundwp()->get_default_prefix( 'scroll_to_top_section' ),
				'element_id' => groundwp()->get_default_prefix( 'scroll_to_top' ),
				'options' => [
					'enable' => [
						'slug' => groundwp()->get_default_prefix( 'scroll_to_top_enable' ),
						'default' => false,
					],
					'position_side' => [
						'slug' => groundwp()->get_default_prefix( 'scroll_to_top_position_side' ),
						'default' => 'right',
					],
					'x_offset' => [
						'slug' => groundwp()->get_default_prefix( 'scroll_to_top_x_offset' ),
						'default' => '5',
					],
					'y_offset' => [
						'slug' => groundwp()->get_default_prefix( 'scroll_to_top_y_offset' ),
						'default' => '5',
					],
					'smooth_scroll' => [
						'slug' => groundwp()->get_default_prefix( 'scroll_to_top_smooth_scroll' ),
						'default' => true,
					],
				],
			],
		];
	}

}
