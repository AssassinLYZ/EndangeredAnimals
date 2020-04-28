<?php
/**
 * GroundWP\GroundWP\Base_Support\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Base_Support;

use WP_Customize_Manager;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use function add_action;
use function add_filter;
use function add_theme_support;
use function is_admin;
use function is_singular;
use function pings_open;
use function esc_url;
use function get_bloginfo;
use function GroundWP\GroundWP\groundwp_schema;
use function wp_scripts;
use function wp_get_theme;
use function get_template;
use const get_theme_mod;

/**
 * Class for adding basic theme support, most of which is mandatory to be implemented by all themes.
 *
 * Exposes template tags:
 * * `groundwp()->get_version()`
 * * `groundwp()->get_asset_version( string $filepath )`
 */
class Component implements Component_Interface, Templating_Component_Interface {
	use OwnSchema;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'base_support';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_essential_theme_support' ] );
		add_action( 'wp_head', [ $this, 'action_add_pingback_header' ] );
		add_action( 'wp_head', [ $this, 'cache_control' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes_add_hfeed' ] );
		add_filter( 'excerpt_length', [ $this, 'filter_custom_excerpt_length' ] );
		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more_tag' ] );
		add_filter( 'embed_defaults', [ $this, 'filter_embed_dimensions' ] );
		add_filter( 'theme_scandir_exclusions', [ $this, 'filter_scandir_exclusions_for_optional_templates' ] );
		add_filter( 'script_loader_tag', [ $this, 'filter_script_loader_tag' ], 10, 2 );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * Browser side cache control
	 */
	public function cache_control() {
		$is_cache_enabled = filter_var( get_theme_mod( $this->schema( 'settings-enable_cache-slug' ), $this->schema( 'settings-enable_cache-default' ) ), FILTER_VALIDATE_BOOLEAN );

		$cache_max_age = get_theme_mod( $this->schema( 'settings-cache_duration-slug' ), $this->schema( 'settings-cache_duration-default' ) );

		$expires = esc_attr( gmdate( 'D, d M Y H:i:s', time() + $cache_max_age ) );

		$cache_control = esc_attr( 'max-age=' . $is_cache_enabled ? $cache_max_age : '0' );

		// Cache-Control meta header.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sprintf( '<meta http-equiv="Cache-Control" content="%1$s" >', $cache_control );
		// Expires meta header.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sprintf( '<meta http-equiv="Expires" content="%1$s" >', $expires );
	}

	/**
	 * Cast a value into a number
	 *
	 * @param mixed $value value to be sanitized.
	 *
	 * @return int casted number
	 */
	public function cast_to_number( $value ) {
		return (int) $value;
	}

	/**
	 * Cache customize options and controls
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->add_setting(
			$this->schema( 'settings-enable_cache-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-enable_cache-default' ),
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'transport'         => 'refresh',

			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-cache_duration-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-cache_duration-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => [ $this, 'cast_to_number' ],

			]
		);

		$wp_customize->add_section(
			$this->schema( 'section-slug' ),
			[
				'title' => esc_html__( 'Cache', 'groundwp' ),
				'panel' => groundwp_schema()->get_schema( 'customizer-panels-performance_optimizations-slug' ),
			]
		);

		// Enable cache control.
		$wp_customize->add_control(
			$this->schema( 'settings-enable_cache-slug' ),
			[
				'label'       => esc_html__( 'Enable browser caching of assets.', 'groundwp' ),
				'description' => esc_html__( 'Will enable caching of theme/site assets. Disable it to force browsers to ignore cached assets.', 'groundwp' ),
				'type'        => 'checkbox',
				'default'     => $this->schema( 'settings-enable_cache-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// Cache duration control.
		$wp_customize->add_control(
			$this->schema( 'settings-cache_duration-slug' ),
			[
				'label'       => esc_html__( 'Cache duration', 'groundwp' ),
				'description' => esc_html__( 'Amount of seconds  needed to be passed to invalidate the cached assets', 'groundwp' ),
				'type'        => 'number',
				'default'     => $this->schema( 'settings-cache_duration-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

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
			'get_version'       => [ $this, 'get_version' ],
			'get_asset_version' => [ $this, 'get_asset_version' ],
		];
	}

	/**
	 * Adds theme support for essential features.
	 */
	public function action_essential_theme_support() {
		// Add default RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Ensure WordPress manages the document title.
		add_theme_support( 'title-tag' );

		// Ensure WordPress theme features render in HTML5 markup.
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);

		// Add support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

	}

	/**
	 * Adds a pingback url auto-discovery header for singularly identifiable articles.
	 */
	public function action_add_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Adds a 'hfeed' class to the array of body classes for non-singular pages.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes_add_hfeed( array $classes ) {
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Changes the excerpt length to 30 words instead of the default 55.
	 *
	 * @param int $length Excerpt length.
	 *
	 * @return int Modified excerpt length.
	 */
	public function filter_custom_excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		return 30;
	}

	/**
	 * Adds a "Continue Reading" link to the end of the excerpts.
	 *
	 * @param string $more "Read more" excerpt string.
	 *
	 * @return string Modified "read more" excerpt string.
	 */
	public function filter_excerpt_more_tag( $more ) {
		if ( is_admin() ) {
			return $more;
		}
		if ( ! is_single() ) {
			$more = sprintf(
				'...<a class="read-more" href="%1$s">%2$s</a>',
				esc_url( get_permalink( get_the_ID() ) ),
				esc_html__( 'Continue Reading &hellip;', 'groundwp' )
			);
		}

		return $more;
	}

	/**
	 * Sets the embed width in pixels, based on the theme's design and stylesheet.
	 *
	 * @param array $dimensions An array of embed width and height values in pixels (in that order).
	 *
	 * @return array Filtered dimensions array.
	 */
	public function filter_embed_dimensions( array $dimensions ) {
		$dimensions['width'] = 720;

		return $dimensions;
	}

	/**
	 * Excludes any directory named 'optional' from being scanned for theme template files.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/theme_scandir_exclusions/
	 *
	 * @param array $exclusions the default directories to exclude.
	 *
	 * @return array Filtered exclusions.
	 */
	public function filter_scandir_exclusions_for_optional_templates( array $exclusions ) {
		return array_merge(
			$exclusions,
			[ 'optional' ]
		);
	}

	/**
	 * Adds async/defer attributes to enqueued / registered scripts.
	 *
	 * If #12009 lands in WordPress, this function can no-op since it would be handled in core.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12009
	 *
	 * @param string $tag The script tag.
	 * @param string $handle The script handle.
	 *
	 * @return string Script HTML string.
	 */
	public function filter_script_loader_tag( $tag, $handle ) {

		foreach ( [ 'async', 'defer' ] as $attr ) {
			if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
				continue;
			}

			// Prevent adding attribute when already added in #12009.
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
			}

			// Only allow async or defer, not both.
			break;
		}

		return $tag;
	}

	/**
	 * Gets the theme version.
	 *
	 * @return string Theme version number.
	 */
	public function get_version() {
		static $theme_version = null;

		if ( null === $theme_version ) {
			$theme_version = wp_get_theme( get_template() )->get( 'Version' );
		}

		return $theme_version;
	}

	/**
	 * Gets the version for a given asset.
	 *
	 * Returns filemtime when WP_DEBUG is true, otherwise the theme version.
	 *
	 * @param string $filepath Asset file path.
	 *
	 * @return string Asset version number.
	 */
	public function get_asset_version( $filepath ) {
		if ( WP_DEBUG ) {
			return (string) filemtime( $filepath );
		}

		return $this->get_version();
	}
}
