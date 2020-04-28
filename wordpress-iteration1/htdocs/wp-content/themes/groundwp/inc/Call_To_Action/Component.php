<?php
/**
 * GroundWP\GroundWP\Call_To_Action\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Call_To_Action;

use WP_Customize_Manager;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function apply_filters;
use function get_theme_mod;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for maintaining call-to-action button
 * Exposes template tags:
 * * `groundwp()->call_to_action_url()`
 * * `groundwp()->call_to_action_text()`
 * * `groundwp()->call_to_action_enable()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	use Slug;
	use OwnSchema;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'call_to_action_text', [ $this, 'call_to_action_text_filter' ] );
	}

	/**
	 * Apply filter to call_to_action_text filter hook.
	 *
	 * @param string $current_text current state of the text in filter.
	 *
	 * @return string Filtered text.
	 */
	public function call_to_action_text_filter( $current_text ) {
		return strtoupper( $current_text );
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
			'call_to_action_url'    => [ $this, 'call_to_action_url' ],
			'call_to_action_text'   => [ $this, 'call_to_action_text' ],
			'call_to_action_enable' => [ $this, 'call_to_action_enable' ],
		];

	}

	/**
	 * Get status of call-to-action button
	 *
	 * @return string enabled or not
	 */
	public function call_to_action_enable() {
		return get_theme_mod( $this->schema( 'settings-enable-slug' ), $this->schema( 'settings-enable-default' ) );
	}

	/**
	 * Get url of call-to-action button
	 *
	 * @return string call-to-action url
	 */
	public function call_to_action_url() {
		return get_theme_mod( groundwp()->get_default_prefix( 'call_to_action_url' ), '' );
	}

	/**
	 * Get text of call-to-action button
	 *
	 * @return string call-to-action text
	 */
	public function call_to_action_text() {
		$text = get_theme_mod( groundwp()->get_default_prefix( 'call_to_action_text' ), 'call us' );

		return apply_filters( 'groundwp_call_to_action_text', $text );
	}

	/**
	 * WordPress customize_register hook callback.
	 *
	 * @param WP_Customize_Manager $wp_customizer Customize manager object.
	 */
	public function customize_register( $wp_customizer ) {
		$section = groundwp_schema()->get_schema( 'header-sections-header_section-slug' );

		$wp_customizer->add_setting(
			$this->schema( 'settings-enable-slug' ),
			[
				'type'       => 'theme_mod',
				'default'    => $this->schema( 'settings-enable-default' ),
				'capability' => 'edit_theme_options',
				'transport'  => 'postMessage',
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customizer->add_setting(
			$this->schema( 'settings-url-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-url-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);

		$wp_customizer->add_setting(
			$this->schema( 'settings-text-slug' ),
			[
				'type'              => 'theme_mod',
				// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
				'default'           => __( 'call us', 'groundwp' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_html',
			]
		);

		// call to action control group.
		groundwp()->add_customizer_control_group( $wp_customizer, 'call_to_action_control_group', $section, 'Call to Action' );

		// enable control.
		$wp_customizer->add_control(
			$this->schema( 'settings-enable-slug' ),
			[
				'type'        => 'checkbox',
				'label'       => __( 'Enable Call To Action Button', 'groundwp' ),
				'description' => __( 'Enable Call To Action Component.', 'groundwp' ),
				'section'     => $section,
			]
		);

		// url control.
		$wp_customizer->add_control(
			$this->schema( 'settings-url-slug' ),
			[
				'label'       => __( 'Call To Action Button URL', 'groundwp' ),
				'description' => __( 'Url to redirect with Call To Action button, leave it empty for hiding the button', 'groundwp' ),
				'type'        => 'text',
				'section'     => $section,
			]
		);

		// button text control.
		$wp_customizer->add_control(
			$this->schema( 'settings-text-slug' ),
			[
				'label'       => __( 'Call To Action button text', 'groundwp' ),
				'description' => __( 'Visible text on Call To Action button.', 'groundwp' ),
				'type'        => 'text',
				'section'     => $section,
			]
		);

		groundwp()->add_customizer_group_ruler( $wp_customizer, 'call_to_action_group_bottom', $section );

		$wp_customizer->selective_refresh->add_partial(
			$this->schema( 'settings-text-slug' ),
			[
				'selector'        => '.call-to-action-anchor',
				'render_callback' => function () {
					return groundwp()->call_to_action_text();
				},
			]
		);

		$wp_customizer->selective_refresh->add_partial(
			$this->schema( 'settings-enable-slug' ),
			[
				'selector'            => '.call-to-action',
				'container_inclusive' => true,
				'render_callback'     => function ( $partial ) {
					if ( get_theme_mod( groundwp()->get_default_prefix( 'call_to_action_enable' ) ) ) {
						return $partial;
					}

					return '';
				},
			]
		);
	}


}
