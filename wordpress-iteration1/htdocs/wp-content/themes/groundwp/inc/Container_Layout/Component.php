<?php
/**
 * GroundWP\GroundWP\Container_Layout\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Container_Layout;

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
 * * `groundwp()->get_container_layout_choices()`
 * * `groundwp()->get_container_layout()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'container_layout';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
		add_action( 'customize_register', [ $this, 'action_customize_register_container_layout' ] );
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
			'get_container_layout_choices' => [ $this, 'get_container_layout_choices' ],
			'get_container_layout'         => [ $this, 'get_container_layout' ],
		];
	}

	/**
	 * Returns the different available container layouts.
	 *
	 * @param boolean $default Whether to add the default option or not.
	 * @return array Container layouts.
	 */
	public function get_container_layout_choices( $default = false ) {

		$container_layout_choices = [
			'boxed'                => __( 'Boxed', 'groundwp' ),
			'full-width-contained' => __( 'Full Width (Contained)', 'groundwp' ),
			'full-width-stretched' => __( 'Full Width (Stretched)', 'groundwp' ),
		];

		if ( $default ) {
			$container_layout_choices = [ 'default' => __( 'Default', 'groundwp' ) ] + $container_layout_choices;
		}

		return $container_layout_choices;
	}

	/**
	 * Adds custom classes to indicate the chosen layout to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ) {
		$classes[] = 'container-' . $this->get_container_layout();

		return $classes;
	}

	/**
	 * Adds a setting and control for lazy loading the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_container_layout( WP_Customize_Manager $wp_customize ) {

		// Layout Section.
		$wp_customize->add_section(
			'section_container_layout',
			[
				'title'      => __( 'Container Layout', 'groundwp' ),
				'panel'      => groundwp_schema()->get_schema( 'customizer-panels-layout_options-slug' ),
				'priority'   => 150,
			]
		);

		// Default Layout.
		$wp_customize->add_setting(
			'container_layout_default',
			[
				'default'           => 'full-width-contained',
				'sanitize_callback' => [ $this, 'sanitize_container_layout' ],
			]
		);

		$wp_customize->add_control(
			'container_layout_default',
			[
				'label'           => __( 'Default Layout', 'groundwp' ),
				'section'         => 'section_container_layout',
				'type'            => 'select',
				'choices'         => $this->get_container_layout_choices(),
			]
		);

		// Post Layout.
		$wp_customize->add_setting(
			'container_layout_post',
			[
				'default'           => 'default',
				'sanitize_callback' => [ $this, 'sanitize_container_layout' ],
			]
		);

		$wp_customize->add_control(
			'container_layout_post',
			[
				'label'           => __( 'Layout for Single Posts', 'groundwp' ),
				'section'         => 'section_container_layout',
				'type'            => 'select',
				'choices'         => $this->get_container_layout_choices( true ),
			]
		);

		// Page Layout.
		$wp_customize->add_setting(
			'container_layout_page',
			[
				'default'           => 'default',
				'sanitize_callback' => [ $this, 'sanitize_container_layout' ],
			]
		);

		$wp_customize->add_control(
			'container_layout_page',
			[
				'label'           => __( 'Layout for Pages', 'groundwp' ),
				'section'         => 'section_container_layout',
				'type'            => 'select',
				'choices'         => $this->get_container_layout_choices( true ),
			]
		);

		// Archive Layout.
		$wp_customize->add_setting(
			'container_layout_archive',
			[
				'default'           => 'default',
				'sanitize_callback' => [ $this, 'sanitize_container_layout' ],
			]
		);

		$wp_customize->add_control(
			'container_layout_archive',
			[
				'label'           => __( 'Layout for Archives', 'groundwp' ),
				'section'         => 'section_container_layout',
				'type'            => 'select',
				'choices'         => $this->get_container_layout_choices( true ),
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
	public function sanitize_container_layout( $input, $setting ) {
		return groundwp()->sanitize_select( $input, $setting );
	}

	/**
	 * Determines which Container Layout should be chosen depending upon the template in action.
	 * If no value is set, returns the default "full-width-contained" value.
	 *
	 * @return string The applicable container layout.
	 */
	public function get_container_layout() {

		// Check for meta box overrides.
		if ( is_singular() ) {
			$meta = get_post_meta( get_the_ID(), 'groundwp-container-layout', true );
			// If not default and is a valid choice, proceed.
			if ( array_key_exists( $meta, $this->get_container_layout_choices() ) && 'default' !== $meta ) {
				return $meta;
			}
		}

		/**
		 * Check for Customizer options.
		 */

		if ( is_single() ) {
			$container_layout = get_theme_mod( 'container_layout_post', 'default' );
			if ( 'default' === $container_layout ) {
				return $this->get_default_container_layout();
			}
			return esc_attr( $container_layout );
		}

		if ( is_page() ) {
			$container_layout = get_theme_mod( 'container_layout_page', 'default' );
			if ( 'default' === $container_layout ) {
				return $this->get_default_container_layout();
			}
			return esc_attr( $container_layout );
		}

		if ( is_archive() ) {
			$container_layout = get_theme_mod( 'container_layout_archive', 'default' );
			if ( 'default' === $container_layout ) {
				return $this->get_default_container_layout();
			}
			return esc_attr( $container_layout );
		}

		// Return the default if the template does not match any of it.
		return $this->get_default_container_layout();
	}

	/**
	 * The default Container layout Customizer value.
	 *
	 * @return string The default container layout.
	 */
	public function get_default_container_layout() {
		return esc_attr( get_theme_mod( 'container_layout_default', 'full-width-contained' ) );
	}

}
