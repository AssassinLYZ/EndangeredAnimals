<?php
/**
 * GroundWP\GroundWP\Selection_Popup\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Selection_Popup;

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
			'selection_popup' => [
				'script_id' => 'singleSelectionPopup',
				'section'  => [
					'slug' => groundwp()->get_default_prefix( 'selection_popup_section' ),
				],
				'settings' => [
					'table_settings' => [
						'slug'    => groundwp()->get_default_prefix( 'selection_popup_settings' ),
						'default' => [
							[
								'title' => 'Twitter',
								'url'   => 'https://twitter.com/intent/tweet?text=',
							],
							[
								'title' => 'Google',
								'url'   => 'https://www.google.com/search?q=',
							],
						],
					],
					'enabled'        => [
						'slug'    => groundwp()->get_default_prefix( 'selection_popup_enabled' ),
						'default' => false,
					],
				],
			],
		];
	}

}
