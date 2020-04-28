<?php
/**
 * GroundWP\GroundWP\Related_Posts\Schema class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Related_Posts;

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
			'related_posts' => [
				'section' => [
					'slug' => groundwp()->get_default_prefix( 'related_posts_section' ),
				],
				'settings' => [
					'enable' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_enable' ),
						'default' => false,
					],
					'show_excerpt' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_show_excerpt' ),
						'default' => false,
					],
					'show_date' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_show_date' ),
						'default' => true,
					],
					'title' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_title' ),
						'default' => esc_html__( 'Related Posts:', 'groundwp' ),
					],
					'number_of_posts' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_number_of_posts' ),
						'default' => 3,
					],
					'excerpt_length' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_excerpt_length' ),
						'default' => 55,
					],

					'show_readmore' => [
						'slug'    => groundwp()->get_default_prefix( 'related_posts_show_readmore' ),
						'default' => true,
					],
				],
			],
		];

	}

}
