<?php
/**
 * GroundWP\GroundWP\Header\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Header;

use WP_Customize_Manager;
use WP_Customize_Setting;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function get_theme_mod;
use function sanitize_key;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use function GroundWP\GroundWP\groundwp_schema;
use function wp_script_add_data;

/**
 * Class for managing header and logo options and styles.
 *
 * Exposes template tags:
 * * `groundwp()->header_width_class()`
 * * `groundwp()->logo_position_class()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	use Slug;
	use OwnSchema;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', [ $this, 'header_customization' ] );
		add_action( 'customize_preview_init', [ $this, 'customize_preview_init' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'sticky_header_scripts' ] );
	}

	/**
	 * Enqueue necessary script(s) for sticky header functionality
	 */
	public function sticky_header_scripts() {
		if ( $this->is_sticky_header_enabled() ) {
			groundwp()->enqueue_main( $this->schema( 'script_id' ) );
		}
	}

	/**
	 * Get status of sticky header functionality
	 *
	 * @return boolean enabled or not
	 */
	public function is_sticky_header_enabled() {
		return get_theme_mod( $this->schema( 'settings-sticky_enable-slug' ), $this->schema( 'settings-sticky_enable-default' ) );
	}

	/**
	 * WordPress customize_register hook callback.
	 *
	 * @param WP_Customize_Manager $wp_customizer Customize manager object.
	 */
	public function header_customization( $wp_customizer ) {
		$wp_customizer->add_section(
			$this->schema( 'sections-header_section-slug' ),
			[
				'title'    => __( 'Header Options', 'groundwp' ),
				'panel'    => groundwp_schema()->get_schema( 'customizer-panels-layout_options-slug' ),
				'priority' => 1,
			]
		);

		$wp_customizer->add_setting(
			groundwp()->get_default_prefix( 'header_options_width' ),
			[
				'type'              => 'theme_mod',
				'default'           => 'full',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => [ $this, 'sanitize_select' ],
			]
		);

		$wp_customizer->add_setting(
			groundwp()->get_default_prefix( 'logo_options_position' ),
			[
				'type'              => 'theme_mod',
				'default'           => 'left',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => [ $this, 'sanitize_select' ],
			]
		);

		$wp_customizer->add_setting(
			$this->schema( 'settings-sticky_enable-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-sticky_enable-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		// sticky header enable control.
		$wp_customizer->add_control(
			$this->schema( 'settings-sticky_enable-slug' ),
			[
				'label'       => esc_html__( 'Enable Sticky Header', 'groundwp' ),
				'description' => esc_html__( 'Sticky Header hides your header at scroll down and makes it sticky and visible at scroll up.', 'groundwp' ),
				'type'        => 'checkbox',
				'default'     => $this->schema( 'settings-sticky_enable-default' ),
				'section'     => $this->schema( 'sections-header_section-slug' ),
			]
		);

		// width control.
		$wp_customizer->add_control(
			groundwp()->get_default_prefix( 'header_options_width' ),
			[
				'label'       => esc_html__( 'Header Width', 'groundwp' ),
				'description' => esc_html__( 'Controls header width. "Full" for full page width, "Content" for letting header be sized according to its contents.', 'groundwp' ),
				'type'        => 'select',
				'choices'     => [
					'full'    => __( 'Full', 'groundwp' ),
					'content' => __( 'Content', 'groundwp' ),
				],
				'section'     => $this->schema( 'sections-header_section-slug' ),
			]
		);

		// logo position control.
		$wp_customizer->add_control(
			groundwp()->get_default_prefix( 'logo_options_position' ),
			[
				'label'       => esc_html__( 'Logo Position', 'groundwp' ),
				'description' => esc_html__( 'Position of the Logo/Site Title and Tagline. Default is "Left"', 'groundwp' ),
				'type'        => 'select',
				'choices'     => [
					'top'   => __( 'Top', 'groundwp' ),
					'left'  => __( 'Left', 'groundwp' ),
					'right' => __( 'Right', 'groundwp' ),
				],
				'section'     => $this->schema( 'sections-header_section-slug' ),
			]
		);

	}

	/**
	 * Sanitization callback for select elements.
	 *
	 * @param string               $input Selected input value.
	 * @param WP_Customize_Setting $wp_customize_setting Customize setting object.
	 *
	 * @return string Selected/sanitized key or default value for the control object.
	 */
	public function sanitize_select( $input, $wp_customize_setting ) {
		$sanitized_key = sanitize_key( $input );

		$options = $wp_customize_setting->manager->get_control( $wp_customize_setting->id )->choices;

		// Check for the availability of the current input against defined options in customizer setting, if contains, sent input.
		if ( array_key_exists( $sanitized_key, $options ) ) {
			return $sanitized_key;
		}

		// Else return default element for the current control object.
		return $wp_customize_setting->default;
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
			'header_width_class'  => [ $this, 'header_width_class' ],
			'logo_position_class' => [ $this, 'logo_position_class' ],
		];
	}

	/**
	 * Width class depending on customizer setting.
	 *
	 * @return string Header class.
	 */
	public function header_width_class() {
		$header_width_option = get_theme_mod( groundwp()->get_default_prefix( 'header_options_width', 'full' ) );

		// @codingStandardsIgnoreStart
		return ( $header_width_option === 'full' ) ? 'header-full' : '';
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Logo position class depending on customizer setting.
	 *
	 * @return string Appropriate logo position class.
	 */
	public function logo_position_class() {
		$logo_position_option = get_theme_mod( groundwp()->get_default_prefix( 'logo_options_position' ), 'left' );

		return 'logo-' . $logo_position_option;
	}

	/**
	 * WordPress customize_preview_init callback
	 */
	public function customize_preview_init() {
		$localized_array = [
			'settings_ids' => [
				'logo_options_position' => groundwp()->get_default_prefix( 'logo_options_position' ),
				'header_options_width'  => groundwp()->get_default_prefix( 'header_options_width' ),
			],
			'extras'       => [
				'logo_class_prefix'       => 'logo-',
				'header_width_full_class' => 'header-full',
			],
		];

		groundwp()->customize_data( 'preview', [ 'customizeSettings' => $localized_array ], [ 'jquery', 'customize-preview' ] );
	}
}
