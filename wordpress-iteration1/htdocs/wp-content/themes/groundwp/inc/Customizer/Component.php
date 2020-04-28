<?php
/**
 * GroundWP\GroundWP\Customizer\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Customizer;

use InvalidArgumentException;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use function wp_enqueue_style;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use WP_Customize_Manager;
use function add_action;
use function bloginfo;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function sanitize_key;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for managing Customizer integration.
 *
 * Exposes template tags:
 * * `groundwp()->sanitize_select()`
 * * `groundwp()->add_customizer_group_ruler()`
 * * `groundwp()->add_customizer_control_group()`
 * * `groundwp()->customize_data()`
 */
class Component implements Component_Interface, Templating_Component_Interface {
	use OwnSchema;

	/**
	 * Main data holder for previews and controls
	 *
	 * @var array
	 */
	private $main_customize_data = [
		'preview' => [],
		'control' => [],
	];

	/**
	 * Main deps holder for previews and controls
	 *
	 * @var array
	 */
	private $main_customize_deps = [
		'preview' => [],
		'control' => [],
	];

	/**
	 * Main customize data types
	 *
	 * @var array
	 */
	private $data_types = [ 'preview', 'control' ];

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'customizer';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', [ $this, 'action_customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'action_enqueue_customize_preview_js' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'startup_customize_controls' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'main_customize_controls' ], 999 );
		add_action( 'customize_preview_init', [ $this, 'main_customize_previews' ], 999 );
	}

	/**
	 * WordPress action hook callback for main previews related to customize api
	 */
	public function main_customize_previews() {
		$handler = groundwp()->ez_enqueue_script( '/assets/js/' . $this->schema( 'scripts-main_preview' ), true, $this->main_customize_deps['preview'] );

		$this->batch_localize( $handler, $this->main_customize_data['preview'] );
	}

	/**
	 * WordPress action hook callback for main previews related to customize api
	 */
	public function main_customize_controls() {
		$handler = groundwp()->ez_enqueue_script( '/assets/js/' . $this->schema( 'scripts-main_control' ), true, $this->main_customize_deps['control'] );

		$this->batch_localize( $handler, $this->main_customize_data['control'] );
	}

	/**
	 * Batch localize an array of data
	 *
	 * @param string $handler_name script handler name.
	 * @param array  $data_holder an array of key/value paired tobe localized data.
	 */
	private function batch_localize( $handler_name, $data_holder ) {
		foreach ( $data_holder as $data ) {
			foreach ( $data as $key => $value ) {
				wp_localize_script( $handler_name, $key, $value );
			}
		}
	}

	/**
	 * WordPress customizer controls enqueue scripts hook callback
	 */
	public function startup_customize_controls() {
		$path     = '/assets/css/' . $this->schema( 'styles-file' );
		$path_dir = get_theme_file_path( $path );
		$path_url = get_theme_file_uri( $path );
		$version  = groundwp()->get_asset_version( $path_dir );

		wp_enqueue_style( groundwp()->get_default_prefix( 'customizer_control_styles' ), $path_url, [], $version );

		$startup_data                                 = [
			'elements' => $this->schema( 'elements' ),
		];
		$startup_data['elements']['horizontal_ruler'] = [ 'id' => 'customize-control-' . $this->schema( 'settings-horizontal_ruler-slug' ) ];
		$startup_data['elements']['control_group']    = [ 'id' => 'customize-control-' . $this->schema( 'settings-control_group-slug' ) ];

		// switches to show/hide certain controls depending on the on/off state of the checkbox.
		$startup_data['switches'] = [
			groundwp_schema()->get_schema( 'call_to_action-settings-enable-slug' )   => [
				groundwp_schema()->get_schema( 'call_to_action-settings-url-slug' ),
				groundwp_schema()->get_schema( 'call_to_action-settings-text-slug' ),
			],
			groundwp_schema()->get_schema( 'scroll_to_top-options-enable-slug' )     => [
				groundwp_schema()->get_schema( 'scroll_to_top-options-position_side-slug' ),
				groundwp_schema()->get_schema( 'scroll_to_top-options-x_offset-slug' ),
				groundwp_schema()->get_schema( 'scroll_to_top-options-y_offset-slug' ),
				groundwp_schema()->get_schema( 'scroll_to_top-options-smooth_scroll-slug' ),
			],
			groundwp_schema()->get_schema( 'selection_popup-settings-enabled-slug' ) => [
				groundwp_schema()->get_schema( 'selection_popup-settings-table_settings-slug' ),
			],
			groundwp_schema()->get_schema( 'related_posts-settings-enable-slug' )    => [
				groundwp_schema()->get_schema( 'related_posts-settings-title-slug' ),
				groundwp_schema()->get_schema( 'related_posts-settings-number_of_posts-slug' ),
				groundwp_schema()->get_schema( 'related_posts-settings-show_date-slug' ),
				groundwp_schema()->get_schema( 'related_posts-settings-show_excerpt-slug' ),
				groundwp_schema()->get_schema( 'related_posts-settings-excerpt_length-slug' ),
				groundwp_schema()->get_schema( 'related_posts-settings-show_readmore-slug' ),
			],
		];

		$this->customize_data( 'control', [ 'groundwpCustomizerStartup' => $startup_data ], [ 'jquery', 'customize-controls' ] );
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
			'sanitize_select'              => [ $this, 'sanitize_select' ],
			'add_customizer_group_ruler'   => [ $this, 'add_customizer_group_ruler' ],
			'add_customizer_control_group' => [ $this, 'add_customizer_control_group' ],
			'customize_data' => [ $this, 'customize_data' ],
		];
	}

	/**
	 * Add a control group to customizer screen
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @param string               $id control id.
	 * @param string               $section section id.
	 * @param string               $title group title.
	 */
	public function add_customizer_control_group( $wp_customize, $id, $section, $title ) {
		$formed_id = $this->schema( 'settings-control_group-slug' ) . '-' . $id;

		$wp_customize->add_setting(
			$formed_id,
			[
				'type'      => 'theme_mod',
				'transport' => 'postMessage',
				'default'   => true,
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_control(
			$formed_id,
			[
				'type'    => 'hidden',
				'section' => $section,
				'label'   => $title,
			]
		);
	}

	/**
	 * Adds postMessage support for site title and description, plus a custom Theme Options section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'        => '.site-title a',
					'render_callback' => function () {
						bloginfo( 'name' );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => function () {
						bloginfo( 'description' );
					},
				]
			);
		}

		/**
		 * Theme options.
		 */
		$wp_customize->add_panel(
			'theme_options',
			[
				'title'    => esc_html__( 'Theme Options', 'groundwp' ),
				'priority' => 11, // Before Additional CSS.
			]
		);

		/**
		 * Layout options panel
		 */
		$wp_customize->add_panel(
			$this->schema( 'panels-layout_options-slug' ),
			[
				'title'    => esc_html__( 'Layout Options', 'groundwp' ),
				'priority' => 1,
			]
		);

		/**
		 * Blog options panel
		 */
		$wp_customize->add_panel(
			$this->schema( 'panels-blog_options-slug' ),
			[
				'title'    => esc_html__( 'Blog Options', 'groundwp' ),
				'priority' => 1,
			]
		);

		/**
		 * Extra options panel
		 */
		$wp_customize->add_panel(
			$this->schema( 'panels-extra_options-slug' ),
			[
				'title'    => esc_html__( 'Extra Options', 'groundwp' ),
				'priority' => 1,
			]
		);

		/**
		 * Performance optimizations panel
		 */
		$wp_customize->add_panel(
			$this->schema( 'panels-performance_optimizations-slug' ),
			[
				'title'    => esc_html__( 'Performance Optimizations', 'groundwp' ),
				'priority' => 1,
			]
		);
	}

	/**
	 * Add a horizontal ruler to customizer screen
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @param string               $id control id.
	 * @param string               $section section id.
	 */
	public function add_customizer_group_ruler( $wp_customize, $id, $section ) {
		$formed_id = $this->schema( 'settings-horizontal_ruler-slug' ) . '-' . $id;
		$wp_customize->add_setting(
			$formed_id,
			[
				'type'      => 'theme_mod',
				'transport' => 'postMessage',
				'default'   => true,
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_control(
			$formed_id,
			[
				'type'    => 'hidden',
				'section' => $section,
				'label'   => 'hr',
			]
		);
	}

	/**
	 * Enqueues JavaScript to make Customizer preview reload changes asynchronously.
	 */
	public function action_enqueue_customize_preview_js() {
		wp_enqueue_script(
			'groundwp-customizer',
			get_theme_file_uri( '/assets/js/customizer.min.js' ),
			[ 'customize-preview' ],
			groundwp()->get_asset_version( get_theme_file_path( '/assets/js/customizer.min.js' ) ),
			true
		);
	}

	/**
	 * Sanitization function for select dropdowns.
	 *
	 * @param string $input The chosen value.
	 * @param object $setting The control.
	 *
	 * @return string The sanitized input.
	 */
	public function sanitize_select( $input, $setting ) {

		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		if ( array_key_exists( $input, $choices ) ) {
			return $input;
		}

		return $setting->default;

	}

	/**
	 * Add data to customizer
	 *
	 * @param string $type customize type, available options are preview and control.
	 * @param array  $data $localize_data script localized data, keys for global object name and values for data.
	 * @param array  $deps dependencies.
	 *
	 * @throws InvalidArgumentException Will throw exception upon wrong type.
	 */
	public function customize_data( $type, $data, $deps = [] ) {
		if ( in_array( $type, $this->data_types ) ) {
			$this->main_customize_data[ $type ][] = $data;

			foreach ( $deps as $dep ) {
				if ( ! in_array( $dep, $this->main_customize_deps[ $type ] ) ) {
					$this->main_customize_deps[ $type ][] = $dep;
				}
			}
		} else {
			throw new InvalidArgumentException( 'Supplied type ' . $type . ' does not match the required ones' );
		}
	}
}
