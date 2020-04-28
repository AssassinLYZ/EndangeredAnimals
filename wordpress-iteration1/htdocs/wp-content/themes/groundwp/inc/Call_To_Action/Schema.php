<?php
/**
 * GroundWP\GroundWP\Call_To_Action\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Call_To_Action;

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
			'call_to_action' => [
				'settings' => [
					'enable' => [
						'slug' => groundwp()->get_default_prefix( 'call_to_action_enable' ),
						'default' => false,
					],
					'url' => [
						'slug' => groundwp()->get_default_prefix( 'call_to_action_url' ),
						'default' => '',
					],
					'text' => [
						'slug' => groundwp()->get_default_prefix( 'call_to_action_text' ),
						'default' => 'call us',
					],
				],

			],
		];
	}

}
