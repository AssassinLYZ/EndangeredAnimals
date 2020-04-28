<?php
/**
 * GroundWP\GroundWP\Related_Posts\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Related_Posts;

use WP_Customize_Manager;
use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\OwnSchema;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function add_filter;
use function register_rest_field;
use function remove_filter;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;
use function GroundWP\GroundWP\groundwp_schema;
use function wp_trim_excerpt;

/**
 * Class for adding related posts to singular post display
 *
 *  Exposes template tags:
 * * `groundwp()->is_related_posts_enabled()`
 */
class Component implements Component_Interface, Templating_Component_Interface {
	use Slug;
	use OwnSchema;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ], 999 );
	}

	/**
	 * Filter excerpt length according to the theme mod setting of related posts
	 *
	 * @param number $number passed excerpt length.
	 *
	 * @return number excerpt length
	 */
	public function excerpt_length( $number ) {
		return get_theme_mod( $this->schema( 'settings-excerpt_length-slug' ), $this->schema( 'settings-excerpt_length-default' ) );
	}

	/**
	 * Filter excerpt more button according to the theme mod setting of related posts
	 *
	 * @param string $more passed excerpt more string.
	 *
	 * @return string excerpt more content
	 */
	public function excerpt_more( $more ) {
		if ( filter_var( get_theme_mod( $this->schema( 'settings-show_readmore-slug' ), $this->schema( 'settings-show_readmore-default' ) ), FILTER_VALIDATE_BOOLEAN ) === false ) {
			return '';
		} else {
			return $more;
		}
	}

	/**
	 * WordPress rest_api_init hook callback function
	 */
	public function rest_api_init() {
		register_rest_field(
			'post',
			'groundwp_related_posts',
			[
				'get_callback' => function ( $rest_post ) {
					add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 999 );
					add_filter( 'excerpt_more', [ $this, 'excerpt_more' ], 999 );
					$new_excerpt = wp_trim_excerpt( $rest_post['excerpt']['raw'], $rest_post['id'] );
					remove_filter( 'excerpt_length', [ $this, 'excerpt_length' ] );
					remove_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );

					return [ 'excerpt' => $new_excerpt ];
				},
				'schema'       => [
					'description' => __( 'GroundWP related posts', 'groundwp' ),
					'type'        => 'object',
				],
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
		return [ 'is_related_posts_enabled' => [ $this, 'is_enabled' ] ];
	}


	/**
	 * Whether component is enabled or not
	 *
	 * @return boolean enabled or not.
	 */
	public function is_enabled() {
		return get_theme_mod( $this->schema( 'settings-enable-slug' ), $this->schema( 'settings-enable-default' ) );

	}

	/**
	 * WordPress customize_register hook callback
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customizer_Manager instance.
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->add_section(
			$this->schema( 'section-slug' ),
			[
				'panel'    => groundwp_schema()->get_schema( 'customizer-panels-blog_options-slug' ),
				'title'    => esc_html__( 'Related Posts', 'groundwp' ),
				'priority' => 1,
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-enable-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-enable-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-show_excerpt-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-show_excerpt-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-show_readmore-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-show_readmore-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-show_date-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-show_date-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'rest_sanitize_boolean',
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-title-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-title-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_html',
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-number_of_posts-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-number_of_posts-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_html',
			]
		);

		$wp_customize->add_setting(
			$this->schema( 'settings-excerpt_length-slug' ),
			[
				'type'              => 'theme_mod',
				'default'           => $this->schema( 'settings-excerpt_length-default' ),
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_html',
			]
		);

		// enable control.
		$wp_customize->add_control(
			$this->schema( 'settings-enable-slug' ),
			[
				'label'       => esc_html__( 'Enable Related Posts Display', 'groundwp' ),
				'description' => esc_html__( 'Display a related posts box under single post page.', 'groundwp' ),
				'type'        => 'checkbox',
				'default'     => $this->schema( 'settings-enable-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// title control.
		$wp_customize->add_control(
			$this->schema( 'settings-title-slug' ),
			[
				'label'       => esc_html__( 'Title', 'groundwp' ),
				'description' => esc_html__( 'Title to be displayed at related posts box.', 'groundwp' ),
				'type'        => 'text',
				'default'     => $this->schema( 'settings-title-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// show date control.
		$wp_customize->add_control(
			$this->schema( 'settings-show_date-slug' ),
			[
				'label'       => esc_html__( 'Show Date', 'groundwp' ),
				'description' => esc_html__( 'Show date of fetched related posts.', 'groundwp' ),
				'type'        => 'checkbox',
				'default'     => $this->schema( 'settings-show_date-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// number of posts control.
		$wp_customize->add_control(
			$this->schema( 'settings-number_of_posts-slug' ),
			[
				'label'       => esc_html__( 'Number of Posts', 'groundwp' ),
				'description' => esc_html__( 'How many posts should the component show.', 'groundwp' ),
				'type'        => 'number',
				'default'     => $this->schema( 'settings-number_of_posts-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// excerpt top ruler.
		groundwp()->add_customizer_group_ruler( $wp_customize, 'related_posts_excerpt_ruler_top', $this->schema( 'section-slug' ) );

		// show excerpt control.
		$wp_customize->add_control(
			$this->schema( 'settings-show_excerpt-slug' ),
			[
				'label'       => esc_html__( 'Show Excerpt', 'groundwp' ),
				'description' => esc_html__( 'Show excerpt of fetched related posts.', 'groundwp' ),
				'type'        => 'checkbox',
				'default'     => $this->schema( 'settings-show_excerpt-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// excerpt length control.
		$wp_customize->add_control(
			$this->schema( 'settings-excerpt_length-slug' ),
			[
				'label'       => esc_html__( 'Excerpt Length', 'groundwp' ),
				'description' => esc_html__( 'Length of Post Excerpt.', 'groundwp' ),
				'type'        => 'number',
				'default'     => $this->schema( 'settings-excerpt_length-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// show read more control.
		$wp_customize->add_control(
			$this->schema( 'settings-show_readmore-slug' ),
			[
				'label'       => esc_html__( 'Show Read More', 'groundwp' ),
				'description' => esc_html__( 'Show Read More link at the end of excerpt.', 'groundwp' ),
				'type'        => 'checkbox',
				'default'     => $this->schema( 'settings-show_readmore-default' ),
				'section'     => $this->schema( 'section-slug' ),
			]
		);

		// excerpt bottom ruler.
		groundwp()->add_customizer_group_ruler( $wp_customize, 'related_posts_excerpt_ruler_bottom', $this->schema( 'section-slug' ) );
	}

	/**
	 * WordPress wp_enqueue_scripts hook callback
	 */
	public function enqueue_scripts() {
		if ( is_single() && $this->is_enabled() ) {
			$data_array            = [];
			$data_array['ajaxUrl'] = get_site_url( null, 'index.php?rest_route=/wp/v2/posts' );

			$cat_ids = [];
			foreach ( get_the_category() as $cat ) {
				$cat_ids[] = $cat->term_id;

			}

			$data_array['category']      = $cat_ids;
			$data_array['currentPostId'] = get_the_ID();
			$data_array['fields']        = [ 'title', 'excerpt', 'date', 'id', 'link', 'groundwp_related_posts' ];

			$data_array['settings'] = [
				'numberOfPosts' => esc_html( get_theme_mod( $this->schema( 'settings-number_of_posts-slug' ), $this->schema( 'settings-number_of_posts-default' ) ) ),
				'title'         => esc_html( get_theme_mod( $this->schema( 'settings-title-slug' ), $this->schema( 'settings-title-default' ) ) ),
				'showExcerpt'   => get_theme_mod( $this->schema( 'settings-show_excerpt-slug' ), $this->schema( 'settings-show_excerpt-default' ) ),
				'showDate'      => get_theme_mod( $this->schema( 'settings-show_date-slug' ), $this->schema( 'settings-show_date-default' ) ),
			];

			groundwp()->enqueue_main( 'relatedPosts', [], [ 'relatedPostsData' => $data_array ] );
		}
	}
}
