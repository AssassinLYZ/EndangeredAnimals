<?php
/**
 * GroundWP\GroundWP\Sidebars\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Sidebars;

use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use function esc_attr;
use function GroundWP\GroundWP\groundwp;
use WP_Customize_Manager;
use function add_action;
use function add_filter;
use function register_sidebar;
use function get_theme_mod;
use function is_active_sidebar;
use function dynamic_sidebar;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for managing sidebars.
 *
 * Exposes template tags:
 * * `groundwp()->is_primary_sidebar_active()`
 * * `groundwp()->is_footer_widget_area_active()`
 * * `groundwp()->display_primary_sidebar()`
 * * `groundwp()->get_sidebar_style_choices()`
 * * `groundwp()->get_sidebar_style()`
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const PRIMARY_SIDEBAR_SLUG = 'sidebar-1';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'sidebars';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', [ $this, 'action_register_sidebars' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
		add_action( 'customize_register', [ $this, 'action_customize_register_sidebar_styles' ] );
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
			'is_primary_sidebar_active'    => [ $this, 'is_primary_sidebar_active' ],
			'is_footer_widget_area_active' => [ $this, 'is_footer_widget_area_active' ],
			'display_primary_sidebar'      => [ $this, 'display_primary_sidebar' ],
			'get_sidebar_style_choices'    => [ $this, 'get_sidebar_style_choices' ],
			'get_sidebar_style'            => [ $this, 'get_sidebar_style' ],
		];
	}

	/**
	 * Returns the different available sidebar styles.
	 *
	 * @param boolean $default Whether to add the default option or not.
	 *
	 * @return array Sidebar styles.
	 */
	public function get_sidebar_style_choices( $default = false ) {

		$sidebar_style_choices = [
			'sidebar-none'  => __( 'No Sidebar', 'groundwp' ),
			'sidebar-right' => __( 'Right Sidebar', 'groundwp' ),
			'sidebar-left'  => __( 'Left Sidebar', 'groundwp' ),
		];

		if ( $default ) {
			$sidebar_style_choices = [ 'default' => __( 'Default', 'groundwp' ) ] + $sidebar_style_choices;
		}

		return $sidebar_style_choices;
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {

		// Primary Sidebar.
		register_sidebar(
			[
				'name'          => \esc_html__( 'Sidebar', 'groundwp' ),
				'id'            => static::PRIMARY_SIDEBAR_SLUG,
				'description'   => \esc_html__( 'Add widgets here.', 'groundwp' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			]
		);

		/**
		 * Footer Sidebars
		 */

		register_sidebar(
			[
				'name'          => \esc_html__( 'Footer Area 1', 'groundwp' ),
				'id'            => 'footer-1',
				'description'   => \esc_html__( 'Add widgets here.', 'groundwp' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			]
		);

		register_sidebar(
			[
				'name'          => \esc_html__( 'Footer Area 2', 'groundwp' ),
				'id'            => 'footer-2',
				'description'   => \esc_html__( 'Add widgets here.', 'groundwp' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			]
		);

		register_sidebar(
			[
				'name'          => \esc_html__( 'Footer Area 3', 'groundwp' ),
				'id'            => 'footer-3',
				'description'   => \esc_html__( 'Add widgets here.', 'groundwp' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			]
		);

	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ) {
		if ( $this->is_primary_sidebar_active() && 'sidebar-none' !== $this->get_sidebar_style() ) {
			global $template;

			if ( ! in_array(
				basename( $template ),
				[
					'front-page.php',
					'404.php',
					'500.php',
					'offline.php',
				],
				true
			) ) {
				$classes[] = 'has-sidebar';
				$classes[] = $this->get_sidebar_style();
			}
		}

		return $classes;
	}

	/**
	 * Adds a setting and control for lazy loading the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_sidebar_styles( WP_Customize_Manager $wp_customize ) {

		// Layout Section.
		$wp_customize->add_section(
			'section_sidebar_style',
			[
				'title' => __( 'Sidebar Style', 'groundwp' ),
				'panel'      => groundwp_schema()->get_schema( 'customizer-panels-layout_options-slug' ),
			]
		);

		// Default Layout.
		$wp_customize->add_setting(
			'sidebar_style_default',
			[
				'default'           => 'sidebar-right',
				'sanitize_callback' => [ $this, 'sanitize_sidebar_styles' ],
			]
		);

		$wp_customize->add_control(
			'sidebar_style_default',
			[
				'label'   => __( 'Default Sidebar', 'groundwp' ),
				'section' => 'section_sidebar_style',
				'type'    => 'select',
				'choices' => $this->get_sidebar_style_choices(),
			]
		);

		// Post Layout.
		$wp_customize->add_setting(
			'sidebar_style_post',
			[
				'default'           => 'default',
				'sanitize_callback' => [ $this, 'sanitize_sidebar_styles' ],
			]
		);

		$wp_customize->add_control(
			'sidebar_style_post',
			[
				'label'   => __( 'Single Post Sidebar', 'groundwp' ),
				'section' => 'section_sidebar_style',
				'type'    => 'select',
				'choices' => $this->get_sidebar_style_choices( true ),
			]
		);

		// Page Layout.
		$wp_customize->add_setting(
			'sidebar_style_page',
			[
				'default'           => 'default',
				'sanitize_callback' => [ $this, 'sanitize_sidebar_styles' ],
			]
		);

		$wp_customize->add_control(
			'sidebar_style_page',
			[
				'label'   => __( 'Pages Sidebar', 'groundwp' ),
				'section' => 'section_sidebar_style',
				'type'    => 'select',
				'choices' => $this->get_sidebar_style_choices( true ),
			]
		);

		// Archive Layout.
		$wp_customize->add_setting(
			'sidebar_style_archive',
			[
				'default'           => 'default',
				'sanitize_callback' => [ $this, 'sanitize_sidebar_styles' ],
			]
		);

		$wp_customize->add_control(
			'sidebar_style_archive',
			[
				'label'   => __( 'Archives Sidebar', 'groundwp' ),
				'section' => 'section_sidebar_style',
				'type'    => 'select',
				'choices' => $this->get_sidebar_style_choices( true ),
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
	public function sanitize_sidebar_styles( $input, $setting ) {
		return groundwp()->sanitize_select( $input, $setting );
	}

	/**
	 * Determines which Container Layout should be chosen depending upon the template in action.
	 * If no value is set, returns the default "sidebar-right" value.
	 *
	 * @return string The applicable container layout.
	 */
	public function get_sidebar_style() {

		// Check for meta box overrides.
		if ( is_singular() ) {
			$meta = get_post_meta( get_the_ID(), 'groundwp-sidebar-style', true );
			// If not default and is a valid choice, proceed.
			if ( array_key_exists( $meta, $this->get_sidebar_style_choices() ) && 'default' !== $meta ) {
				return $meta;
			}
		}

		if ( is_single() ) {
			$sidebar_style = get_theme_mod( 'sidebar_style_post', 'default' );
			if ( 'default' === $sidebar_style ) {
				return $this->get_default_sidebar_style();
			}

			return esc_attr( $sidebar_style );
		}

		if ( is_page() ) {
			$sidebar_style = get_theme_mod( 'sidebar_style_page', 'default' );
			if ( 'default' === $sidebar_style ) {
				return $this->get_default_sidebar_style();
			}

			return esc_attr( $sidebar_style );
		}

		if ( is_archive() ) {
			$sidebar_style = get_theme_mod( 'sidebar_style_archive', 'default' );
			if ( 'default' === $sidebar_style ) {
				return $this->get_default_sidebar_style();
			}

			return esc_attr( $sidebar_style );
		}

		// Return the default if the template does not match any of it.
		return $this->get_default_sidebar_style();
	}

	/**
	 * The default sidebar Customizer value.
	 *
	 * @return string The default sidebar.
	 */
	public function get_default_sidebar_style() {
		return esc_attr( get_theme_mod( 'sidebar_style_default', 'sidebar-right' ) );
	}

	/**
	 * Checks whether the primary sidebar is active.
	 *
	 * @return bool True if the primary sidebar is active, false otherwise.
	 */
	public function is_primary_sidebar_active() {
		return (bool) is_active_sidebar( static::PRIMARY_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether any of the footer widget area is active or not.
	 *
	 * @return bool True if the primary sidebar is active, false otherwise.
	 */
	public function is_footer_widget_area_active() {

		if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-2' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Displays the primary sidebar.
	 */
	public function display_primary_sidebar() {
		dynamic_sidebar( static::PRIMARY_SIDEBAR_SLUG );
	}
}
