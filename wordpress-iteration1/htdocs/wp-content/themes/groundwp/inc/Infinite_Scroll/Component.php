<?php
/**
 * GroundWP\GroundWP\Infinite_Scroll\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Infinite_Scroll;

use WP_Customize_Manager;
use WP_Query;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function esc_url_raw;
use function get_theme_mod;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use function GroundWP\GroundWP\groundwp_schema;

/**
 * Class for adding infinite scrolling to post listing
 */
class Component implements Component_Interface, Templating_Component_Interface {
	use Slug;

	/**
	 * Current pagination index
	 *
	 * @var int
	 */
	public $current_pagination_index = 1;

	/**
	 * Theme mod slug
	 *
	 * @var string
	 */
	public $theme_mod_slug;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		$this->theme_mod_slug = groundwp()->get_default_prefix( 'infinite_scroll' );

		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * WordPress customize_register action hook callback
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize object instance.
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->add_setting(
			$this->theme_mod_slug . '_enabled',
			[
				'type'              => 'theme_mod',
				'transport'         => 'refresh',
				'capability'        => 'edit_theme_options',
				'default'           => false,
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_section(
			$this->theme_mod_slug,
			[
				'title' => esc_html__( 'Infinite Scroll', 'groundwp' ),
				'panel' => groundwp_schema()->get_schema( 'customizer-panels-extra_options-slug' ),
			]
		);

		$wp_customize->add_control(
			$this->theme_mod_slug . '_enabled',
			[
				'type'        => 'checkbox',
				'section'     => $this->theme_mod_slug,
				'label'       => esc_html__( 'Enable', 'groundwp' ),
				'description' => esc_html__( 'Enable/disable Infinite Scrolling for post listings.', 'groundwp' ),
			]
		);
	}

	/**
	 * WordPress pre_get_posts action hook callback
	 *
	 * @param WP_Query $wp_query WP query object.
	 */
	public function pre_get_posts( $wp_query ) {
		$this->current_pagination_index = $wp_query->get( 'paged', 1 );
	}

	/**
	 * Get feature enable status from theme_mod
	 *
	 * @return bool feature enabled or not
	 */
	public function is_enabled() {
		return get_theme_mod( $this->theme_mod_slug . '_enabled', false );
	}

	/**
	 * WordPress wp_enqueue_scripts action hook callback
	 */
	public function enqueue_scripts() {
		if ( ! is_singular() && $this->is_enabled() ) {
			$current_query = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( \wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';

			$data = [
				'paged'   => $this->current_pagination_index,
				'ajaxUrl' => get_site_url(
					null,
					'index.php?rest_route=/wp/v2/posts'
				),
				'query'   => $current_query,
			];

			groundwp()->enqueue_main( 'infiniteScroll', [], [ 'groundwpInfiniteScrollData' => $data ] );

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
			'is_infinite_scroll_enabled' => [ $this, 'is_enabled' ],
		];
	}
}
