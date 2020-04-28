<?php
/**
 * GroundWP\GroundWP\ScrollTop\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\ScrollTop;

use WP_Customize_Manager;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function get_template_part;
use function get_theme_mod;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for adding easy scroll to top component
 * Exposes template tags:
 * * `groundwp()->render_scroll_to_top()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	use Slug;
	use OwnSchema;

	/**
	 * Template part location
	 *
	 * @var string
	 */
	public $template_part = 'template-parts/components/scroll_to_top';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'customize_register', [ $this, 'customizer_register' ] );
		add_action( 'customize_preview_init', [ $this, 'customize_preview_init' ] );
	}

	/**
	 * WordPress customize_preview_init hook callback
	 */
	public function customize_preview_init() {
		$data = [
			'elementId' => 'groundwp_scroll_to_top',
			'settings' => [
				'xOffset' => $this->schema( 'options-x_offset-slug' ),
				'yOffset' => $this->schema( 'options-y_offset-slug' ),
				'side' => $this->schema( 'options-position_side-slug' ),
			],
		];

		groundwp()->customize_data( 'preview', [ 'groundwpScrollToTopCustomizeData' => $data ], [ 'jquery', 'customize-preview' ] );
	}

	/**
	 * WordPress customize_register hook callback
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	public function customizer_register( $wp_customize ) {
		$options_enable   = $this->schema( 'options-enable-slug' );
		$options_side     = $this->schema( 'options-position_side-slug' );
		$options_x_offset = $this->schema( 'options-x_offset-slug' );
		$options_y_offset = $this->schema( 'options-y_offset-slug' );
		$smooth_scroll    = $this->schema( 'options-smooth_scroll-slug' );
		$section          = $this->schema( 'section' );

		$wp_customize->add_section(
			$section,
			[
				'title' => esc_html__( 'Scroll to Top', 'groundwp' ),
				'panel' => groundwp_schema()->get_schema( 'customizer-panels-extra_options-slug' ),
			]
		);

		$wp_customize->add_setting(
			$options_enable,
			[
				'type'              => 'theme_mod',
				'transport'         => 'refresh',
				'capability'        => 'edit_theme_options',
				'default'           => $this->schema( 'options-enable-default' ),
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_setting(
			$options_x_offset,
			[
				'type'              => 'theme_mod',
				'transport'         => 'postMessage',
				'capability'        => 'edit_theme_options',
				'default'           => $this->schema( 'options-x_offset-default' ),
				'sanitize_callback' => 'esc_html',
			]
		);

		$wp_customize->add_setting(
			$options_y_offset,
			[
				'type'              => 'theme_mod',
				'transport'         => 'postMessage',
				'capability'        => 'edit_theme_options',
				'default'           => $this->schema( 'options-y_offset-default' ),
				'sanitize_callback' => 'esc_html',
			]
		);

		$wp_customize->add_setting(
			$options_side,
			[
				'type'       => 'theme_mod',
				'transport'  => 'refresh',
				'capability' => 'edit_theme_options',
				'default'    => $this->schema( 'options-position_side-default' ),
				'sanitize_callback' => 'esc_html',
			]
		);

		$wp_customize->add_setting(
			$smooth_scroll,
			[
				'type'              => 'theme_mod',
				'transport'         => 'refresh',
				'capability'        => 'edit_theme_options',
				'default'           => $this->schema( 'options-smooth_scroll-default' ),
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		// component enable.
		$wp_customize->add_control(
			$options_enable,
			[
				'type'        => 'checkbox',
				'section'     => $section,
				'label'       => esc_html__( 'Enable', 'groundwp' ),
				'description' => esc_html__( 'Enable/disable Scroll to Top button for pages.', 'groundwp' ),
			]
		);

		// side.
		$wp_customize->add_control(
			$options_side,
			[
				'type'        => 'select',
				'choices'     => [
					'left'  => esc_html__( 'Left', 'groundwp' ),
					'right' => esc_html__( 'Right', 'groundwp' ),
				],
				'section'     => $section,
				'label'       => esc_html__( 'Position', 'groundwp' ),
				'description' => esc_html__( 'Page side of the component', 'groundwp' ),
			]
		);

		// offset group
		// horizontal ruler.
		groundwp()->add_customizer_group_ruler( $wp_customize, 'scroll_top_offset_ruler_top', $section );

		// x offset.
		$wp_customize->add_control(
			$options_x_offset,
			[
				'type'        => 'range',
				'section'     => $section,
				'input_attrs' => [
					'min' => '1',
					'max' => '50',
				],
				'label'       => esc_html__( 'X offset', 'groundwp' ),
				'description' => esc_html__( 'Horizontal distance from page window', 'groundwp' ),
			]
		);

		// y offset.
		$wp_customize->add_control(
			$options_y_offset,
			[
				'type'        => 'range',
				'section'     => $section,
				'input_attrs' => [
					'min' => '1',
					'max' => '50',
				],
				'label'       => esc_html__( 'Y offset', 'groundwp' ),
				'description' => esc_html__( 'Vertical distance from page window', 'groundwp' ),
			]
		);

		// horizontal ruler.
		groundwp()->add_customizer_group_ruler( $wp_customize, 'scroll_top_offset_ruler_bottom', $section );

		// TODO [erdembircan] bug with sticky header, check that out
		// $wp_customize->add_control(
		// $smooth_scroll,
		// [
		// 'type'        => 'checkbox',
		// 'section'     => $section,
		// 'label'       => esc_html__( 'Smooth scrolling', 'groundwp' ),
		// 'description' => esc_html__( 'Enable/disable smooth scrolling for pages', 'groundwp' ),
		// ]
		// );
	}

	/**
	 * Checks if component is enabled
	 *
	 * @return boolean enabled or not
	 */
	public function is_enabled() {
		return filter_var( get_theme_mod( $this->schema( 'options-enable-slug' ), $this->schema( 'options-enable-default' ) ), FILTER_VALIDATE_BOOLEAN );

	}

	/**
	 * Enqueue component related scripts
	 */
	public function enqueue_script() {
		if ( $this->is_enabled() ) {
			$data    = [
				'elementId' => $this->schema( 'element_id' ),
				'options'   => [
					'smoothScroll' => get_theme_mod( $this->schema( 'options-smooth_scroll-slug' ), $this->schema( 'options-smooth_scroll-default' ) ),
				],
			];

			groundwp()->enqueue_main( $this->schema( 'script_id' ), [], [ 'groundwpScrollToTopData' => $data ] );
		}
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
			'render_scroll_to_top' => [ $this, 'render_scroll_to_top' ],
			'scroll_to_top_styles' => [ $this, 'scroll_to_top_styles' ],
		];
	}

	/**
	 * Render scroll_to_top component to page.
	 */
	public function render_scroll_to_top() {
		if ( $this->is_enabled() ) {
			get_template_part( $this->template_part );
		}
	}

	/**
	 * Echo out scroll to top component styles
	 */
	public function scroll_to_top_styles() {
		$position = get_theme_mod( $this->schema( 'options-position_side-slug' ), $this->schema( 'options-position_side-default' ) );
		$x_offset = get_theme_mod( $this->schema( 'options-x_offset-slug' ), $this->schema( 'options-x_offset-default' ) );
		$y_offset = get_theme_mod( $this->schema( 'options-y_offset-slug' ), $this->schema( 'options-y_offset-default' ) );

		echo esc_attr(
			$position . ': ' . $x_offset . '%; bottom: ' . $y_offset . '%;'
		);
	}
}
