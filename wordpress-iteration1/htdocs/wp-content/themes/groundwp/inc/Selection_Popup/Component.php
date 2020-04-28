<?php
/**
 * GroundWP\GroundWP\Selection_Popup\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Selection_Popup;

use WP_Customize_Manager;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function esc_url_raw;
use function get_option;
use function sanitize_text_field;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for adding popup menu functionality for single posts at text selection
 */
class Component implements Component_Interface {

	use Slug;
	use OwnSchema;

	/**
	 * Selection class name
	 *
	 * @var string
	 */
	public $selection_class = 'groundwp-selection-pop-parent';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'post_class', [ $this, 'post_class' ], 10, 3 );
	}

	/**
	 * WordPress customize_register hook callback
	 *
	 * @param WP_Customize_Manager $wp_customizer WP Customizer Object.
	 */
	public function customize_register( $wp_customizer ) {
		$section_id = $this->schema( 'section-slug' );
		$setting_id = $this->schema( 'settings-table_settings-slug' );

		$wp_customizer->add_section(
			$section_id,
			[
				'title' => esc_html__( 'Selection Pop-up', 'groundwp' ),
				'panel' => groundwp_schema()->get_schema( 'customizer-panels-extra_options-slug' ),
			]
		);

		$wp_customizer->add_setting(
			$setting_id,
			[
				'type'              => 'option',
				'transport'         => 'refresh',
				'capability'        => 'edit_theme_options',
				'default'           => $this->schema( 'settings-table_settings-default' ),
				'sanitize_callback' => [ $this, 'sanitize_options' ],
			]
		);

		$wp_customizer->add_setting(
			$this->schema( 'settings-enabled-slug' ),
			[
				'type'              => 'theme_mod',
				'transport'         => 'refresh',
				'capability'        => 'edit_theme_options',
				'default'           => $this->schema( 'settings-enabled-default' ),
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		// enable control.
		$wp_customizer->add_control(
			$this->schema( 'settings-enabled-slug' ),
			[
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable', 'groundwp' ),
				'section'     => $section_id,
				'description' => esc_html__( 'Enable/disable Selection Pop-up Component.', 'groundwp' ),
			]
		);

		// items group
		// items top ruler.
		groundwp()->add_customizer_group_ruler( $wp_customizer, 'selection_popup_items_ruler_top', $section_id );

		// custom selection item control.
		$wp_customizer->add_control(
			new Selection_Custom_Control(
				$wp_customizer,
				$setting_id,
				[
					'section'     => $section_id,
					'label'       => esc_html__( 'items', 'groundwp' ),
					'description' => esc_html__( 'Items that are available on Selection Pop-up component.', 'groundwp' ),
				]
			)
		);

		// items bottom ruler.
		groundwp()->add_customizer_group_ruler( $wp_customizer, 'selection_popup_items_ruler_bottom', $section_id );
	}

	/**
	 * Component enabled or not
	 *
	 * @return boolean enabled or not
	 */
	public function is_enabled() {
		return get_theme_mod( $this->schema( 'settings-enabled-slug' ), $this->schema( 'settings-enabled-default' ) );
	}

	/**
	 * WordPress post_class filter callback
	 *
	 * @param array $classes array of post classes.
	 * @param array $class additional post classes to be added.
	 * @param int   $post_id current post if.
	 *
	 * @return array classes to be added to the post
	 */
	public function post_class( $classes, $class, $post_id ) {
		if ( is_single() && $this->is_enabled() ) {
			$classes[] = $this->selection_class;
		}

		return $classes;
	}

	/**
	 * WordPress wp_enqueue_scripts callback hook
	 */
	public function enqueue_scripts() {
		if ( is_single() && $this->is_enabled() ) {
			$data                    = [];
			$data['class']           = $this->selection_class;
			$data['items']           = $this->sanitizer(
				get_option( groundwp()->get_default_prefix( 'selection_popup_settings' ), $this->schema( 'settings-table_settings-default' ) ),
				[
					'title' => 'esc_html',
					'url'   => 'esc_url_raw',
				]
			);
			$data['copyIcon']        = get_theme_file_uri( '/assets/images/copy_icon.svg' );
			$data['strings']['copy'] = esc_html__( 'Copy', 'groundwp' );

			groundwp()->enqueue_main(
				$this->schema( 'script_id' ),
				[],
				[
					'selectionPopupData' => $data,
				]
			);
		}
	}

	/**
	 * WordPress Customizer setting sanitization callback
	 *
	 * @param array $option options array.
	 *
	 * @return array sanitized option array
	 */
	public function sanitize_options( $option ) {
		return $this->sanitizer(
			$option,
			[
				'title' => 'sanitize_text_field',
				'url'   => 'esc_url_raw',
			]
		);
	}


	/**
	 * Batch sanitization
	 *
	 * @param array $source source array to be sanitized.
	 * @param array $rules array of rules with keys as source keys and callback function for sanitization as values.
	 *
	 * @return array sanitized array
	 */
	public function sanitizer( $source, $rules ) {
		$sanitized_array = [];

		foreach ( $source as $s ) {
			$sanitized_item = [];
			foreach ( $s as $key => $value ) {
				if ( isset( $rules[ $key ] ) ) {
					$sanitized_field        = call_user_func( $rules[ $key ], $value );
					$sanitized_item[ $key ] = $sanitized_field;
				}
			}
			if ( ! empty( $sanitized_item ) ) {
				$sanitized_array[] = $sanitized_item;
			}
		}

		return $sanitized_array;
	}
}

