<?php

namespace Toolset\DynamicSources\Integrations;

class Views {
	/** @var string */
	private $content_template_post_type;

	/** @var string */
	private $wpa_helper_post_type;

	/** @var Views\Internals */
	private $integration_internals;

	public function __construct(
		$content_template_post_type,
		$wpa_helper_post_type,
		Views\Internals $integration_internals
	) {
		if ( ! is_string( $content_template_post_type ) ) {
			throw new \InvalidArgumentException( 'The Content Template post type argument ($content_template_post_type) has to be a string.' );
		}

		if ( ! is_string( $wpa_helper_post_type ) && null !== $wpa_helper_post_type ) {
			throw new \InvalidArgumentException( 'The WordPress Archive post type argument ($wpa_helper_post_type) has to be a string or null.' );
		}

		$this->content_template_post_type = $content_template_post_type;
		$this->wpa_helper_post_type = $wpa_helper_post_type;
		$this->integration_internals = $integration_internals;
	}

	/**
	 * Class initialization
	 */
	public function initialize() {
		add_filter( 'toolset/dynamic_sources/filters/get_dynamic_sources_data', array( $this, 'integrate_views_info_for_dynamic_sources' ) );

		add_action( 'rest_api_init', array( $this, 'register_content_template_preview_post' ) );

		add_action( 'toolset/dynamic_sources/filters/post_type_for_source_context', array( $this, 'adjust_post_types_for_source_context_in_cts' ), 10, 2 );

		add_action( 'toolset/dynamic_sources/filters/post_type_for_source_context', array( $this, 'adjust_post_types_for_source_context_in_view' ), 10, 2 );

		add_filter( 'toolset/dynamic_sources/filters/shortcode_post', array( $this, 'maybe_get_preview_post_id_for_ct_with_post_content_source' ), 10, 4 );

		add_filter( 'toolset/dynamic_sources/filters/shortcode_post', array( $this, 'maybe_get_preview_post_id_for_wpa_with_post_content_source' ), 10, 4 );

		add_filter( 'toolset/dynamic_sources/filters/post_sources', array( $this, 'maybe_exclude_post_content_source_from_post_sources' ) );
	}

	public function adjust_post_types_for_source_context_in_cts( $post_type, $post_id ) {
		if ( $this->content_template_post_type === $post_type ) {
			$post_type = $this->integration_internals->get_assigned_post_types( $post_id );
		}

		return $post_type;
	}

	public function adjust_post_types_for_source_context_in_view( $post_type, $post_id ) {
		if ( is_admin() || ! $post_id ) {
			return $post_type;
		}

		$view_block_name = 'toolset-views/view-editor';

		$post = get_post( $post_id );

		if (
			! has_blocks( $post->post_content ) ||
			false === strpos( $post->post_content, $view_block_name )
		) {
			return $post_type;
		}

		$view_post_types = array();

		$blocks = parse_blocks( $post->post_content );
		foreach ( $blocks as $block ) {
			if ( $view_block_name === $block['blockName'] ) {
				$view_post_types = array_merge( $view_post_types, $this->integration_internals->maybe_get_view_block_post_types( $block ) );
			}
		}

		if ( ! empty( $view_post_types ) ) {
			if ( ! is_array( $post_type ) ) {
				$post_type = array( $post_type );
			}

			$post_type = array_merge( $post_type, $view_post_types );
		}

		return $post_type;
	}

	public function register_content_template_preview_post() {
		register_meta(
			'post',
			'tb_preview_post',
			array(
				'object_subtype' => $this->content_template_post_type,
				'show_in_rest' => true,
				'single' => true,
				'type' => 'number',
			)
		);
	}

	public function integrate_views_info_for_dynamic_sources( $localization_array ) {
		if ( get_post_type() !== $this->content_template_post_type ) {
			return $localization_array;
		}

		$assigned_post_types = $this->integration_internals->get_assigned_post_types();

		$preview_posts = $this->integration_internals->get_preview_posts( $assigned_post_types );

		if ( empty( $preview_posts ) ) {
			return $localization_array;
		}

		$preview_posts = array_map(
			function( $post ) {
				return array(
					'label' => $post->post_title,
					'value' => $post->ID,
					'guid' => $post->guid,
				);
			},
			$preview_posts
		);

		$post_preview = absint( get_post_meta( get_the_ID(), 'tb_preview_post', true ) );

		if ( $post_preview <= 0 ) {
			$post_preview = $preview_posts[0]['value'];
		} else {
			// Make sure we do include the selected pos to preview
			$preview_posts[] = array(
				'label' => get_the_title( $post_preview ),
				'value' => $post_preview,
				'guid' => get_the_guid( $post_preview ),
			);

			// Avoid duplicates
			$serialized = array_map( 'serialize', $preview_posts );
			$unique = array_unique( $serialized );
			$preview_posts = array_intersect_key( $preview_posts, $unique );
		}

		$localization_array['previewPosts'] = $preview_posts;

		$localization_array['previewPostTypes'] = implode( ',', $assigned_post_types );

		$localization_array['postPreview'] = $post_preview;

		$localization_array['cache'] = apply_filters( 'toolset/dynamic_sources/filters/cache', array(), $post_preview );

		return $localization_array;
	}

	/**
	 * Returns the preview post ID if the "post" is the ID of a Content Template and the selected "source" is "post-content".
	 * If for some reason there is no preview post ID meta for the Content Template, it returns null.
	 *
	 * @param $post
	 * @param $post_provider
	 * @param $source
	 * @param $field
	 *
	 * @return int|null
	 */
	public function maybe_get_preview_post_id_for_ct_with_post_content_source( $post, $post_provider, $source, $field ) {
		if (
			'post-content' !== $source ||
			get_post_type( $post ) !== $this->content_template_post_type
		) {
			return $post;
		}

		$preview_post_id = absint( get_post_meta( $post, 'tb_preview_post', true ) );

		if ( $preview_post_id <= 0 ) {
			$post = null;
		} else {
			$post = $preview_post_id;
		}

		return $post;
	}

	/**
	 * Filters the Post Sources by excluding the PostContent sources when not needed.
	 *
	 * @param array $post_sources The Post Sources.
	 *
	 * @return array The filtered Post Sources.
	 */
	public function maybe_exclude_post_content_source_from_post_sources( $post_sources ) {
		// Do not offer the PostContent source outside of Content Templates or in new post pages.
		global $pagenow;
		$post = (int) sanitize_text_field( isset( $_GET['post'] ) ? $_GET['post'] : 0 );
		$should_exclude_post_content_source = false;

		switch ( $pagenow ) {
			case 'post.php':
				if (
					! in_array(
						get_post_type( $post ),
						array(
							$this->content_template_post_type,
							$this->wpa_helper_post_type,
						)
					)
				) {
					$should_exclude_post_content_source = true;
				}
				break;
			case 'post-new.php':
				$should_exclude_post_content_source = true;
				break;
		}

		if ( $should_exclude_post_content_source ) {
			$post_sources = array_filter(
				$post_sources,
				function( $source ) {
					return ( 'PostContent' !== $source );
				}
			);
		}

		return $post_sources;
	}

	/**
	 * Returns the preview post ID if the "post" is the ID of a WordPress Archive and the selected "source" is "post-content".
	 * If for some reason there is no preview post ID meta for the Content Template, it returns null.
	 *
	 * @param $post
	 * @param $post_provider
	 * @param $source
	 * @param $field
	 *
	 * @return int|null
	 */
	public function maybe_get_preview_post_id_for_wpa_with_post_content_source( $post, $post_provider, $source, $field ) {
		if (
			'post-content' === $source &&
			get_post_type( $post ) === $this->wpa_helper_post_type
		) {
			return null;
		}

		return $post;
	}
}
