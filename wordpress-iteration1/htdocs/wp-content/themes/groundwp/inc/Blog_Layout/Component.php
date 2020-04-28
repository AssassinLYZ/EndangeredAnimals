<?php
/**
 * GroundWP\GroundWP\Blog_Layout\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Blog_Layout;

use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use function GroundWP\GroundWP\groundwp;
use WP_Customize_Manager;
use function add_action;
use function get_theme_mod;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for adding Container Layouts.
 *
 * Exposes template tags:
 * * `groundwp()->get_blog_layout()`
 * * `groundwp()->is_blog_layout_applicable()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'blog_layout';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', [ $this, 'action_customize_register_blog_layout' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `groundwp()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() {
		return [
			'get_blog_layout'           => [ $this, 'get_blog_layout' ],
			'is_blog_layout_applicable' => [ $this, 'is_blog_layout_applicable' ],
		];
	}

	/**
	 * Adds a setting and control for lazy loading the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_blog_layout( WP_Customize_Manager $wp_customize ) {
		$blog_layout_choices = [
			'vertical-list' => __( 'Vertical List', 'groundwp' ),
			'masonry-grid'  => __( 'Masonry Grid', 'groundwp' ),
		];

		// Layout Section.
		$wp_customize->add_section(
			'section_blog_layout',
			[
				'title' => __( 'Blog Layout', 'groundwp' ),
				'panel'      => groundwp_schema()->get_schema( 'customizer-panels-blog_options-slug' ),
			]
		);

		// Default Layout.
		$wp_customize->add_setting(
			'blog_layout_default',
			[
				'default'           => 'vertical-list',
				'sanitize_callback' => [ $this, 'sanitize_blog_layout' ],
			]
		);

		$wp_customize->add_control(
			'blog_layout_default',
			[
				'label'   => __( 'Blog Layout', 'groundwp' ),
				'section' => 'section_blog_layout',
				'type'    => 'radio',
				'choices' => $blog_layout_choices,
			]
		);
	}

	/**
	 * Runs the select dropdown sanitization function.
	 *
	 * @param string $input The chosen value.
	 * @param object $setting The control.
	 *
	 * @return string The sanitized input.
	 */
	public function sanitize_blog_layout( $input, $setting ) {
		return groundwp()->sanitize_select( $input, $setting );
	}

	/**
	 * Determines whether the blog layout is applicable or not.
	 *
	 * @return bool The applicable container layout.
	 */
	public function is_blog_layout_applicable() {

		if ( ! is_singular( get_post_type() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines which Container Layout should be chosen depending upon the template in action.
	 *
	 * @return string The applicable container layout.
	 */
	public function get_blog_layout() {
		return esc_attr( get_theme_mod( 'blog_layout_default', 'vertical-list' ) );
	}

}
