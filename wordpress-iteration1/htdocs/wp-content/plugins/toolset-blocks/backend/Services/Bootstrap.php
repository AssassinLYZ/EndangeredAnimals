<?php

namespace OTGS\Toolset\Views\Services;

use OTGS\Toolset\Views\Blocks as Blocks;
use OTGS\Toolset\Views\Controller\Compatibility\EditorBlocks\View\Block;
use OTGS\Toolset\Views\Services\ViewParsingService;

use const OTGS\Toolset\Views\UserCapabilities\EDIT_VIEWS;

class Bootstrap {
	const BLOCK_NAME = 'toolset/view';
	protected $toolset_ajax_manager;

	/** @var array */
	private $view_get_instance;

	/**
	 * Bootstrap constructor.
	 *
	 * @param array $view_get_instance The instance of the WPV_View static class.
	 */
	public function __construct( array $view_get_instance ) {
		$this->view_get_instance = $view_get_instance;
	}

	/**
	 * Initialize stuff for new view editor
	 */
	public function initialize() {
		$this->toolset_ajax_manager = \WPV_Ajax::get_instance();
		/*
		 * Initialize REST API endpoints
		 */
		$this->create_rest_endpoints();
		/*
		 * Register block categories
		 */
		add_filter( 'block_categories', array($this, 'register_block_categories'), 20, 2);
		/*
		 * Register render callback which will be used for view rendering
		 * using template from the Gutenberg "modern" mode
		 */
		register_block_type('toolset-views/view-editor', array(
			'render_callback' => array($this, 'view_render_callback')
		));

		/*
		 * Register render callback which will be used for view rendering
		 * using template from the Gutenberg "modern" mode
		 */
		register_block_type(
			'toolset-views/wpa-editor',
			array(
				'render_callback' => array(
					$this,
					'view_render_callback',
				),
			)
		);

		/**
		 * register Gutenberg Views editor assets
		 */
		add_action( 'enqueue_block_editor_assets', array($this, 'register_view_editor_assets') );
		add_action( 'enqueue_block_assets', array($this, 'register_view_general_assets') );

		/**
		 * Render footer templates for some Views components, like query and frontend filters.
		 */
		add_action( 'admin_footer', array( $this, 'render_footer_templates' ) );
		/*
		 *
		 */
		$parser = new ViewParsingService();
		$parser->init();

		add_filter( 'wpv_filter_get_view_parent_post_id', array( $this, 'get_view_parent_post_id' ), 10, 2 );
		/**
		 * Add a handler to automatically replace old view block markup with a new one
		 */
		add_action( 'the_post', array( $this, 'convert_legacy_block_markup' ) );
		/**
		 * Create a dedicated tab for custom capabilities of Views
		 */
		add_filter( 'wpcf_access_custom_capabilities', array( $this, 'access_custom_capabilities' ), 50 );

		$wpml = new WPMLService();
		$wpml->init();

		// init blocks
		$sorting = new Blocks\Sorting();
		$sorting->initialize();

		$pagination = new Blocks\Pagination();
		$pagination->initialize();
	}

	public function access_custom_capabilities( $data )
    {
        $wp_roles['label'] = __( 'Views capabilities', 'wpv-views' );
        $wp_roles['capabilities'] = array( EDIT_VIEWS => __( 'Edit Views', 'wpv-views' ) );
        $data[] = $wp_roles;
        return $data;
    }

	/**
	 * the_post filter handler to convert old view blocks to new (view-editor)
	 * needed to completely get rid of having registered toolset/view block
	 * @param \WP_Post $post Post to convert markup from.
	 */
	public function convert_legacy_block_markup( $post ) {
		// run this on admin page only
		if ( ! is_admin() ) {
			return;
		}
		$service = new ViewParsingService();
		do {
			$data = $service->find_block_in_text( $post->post_content, 'toolset/view' );
			if ( null === $data ) {
				break;
			}
			$markup = substr( $post->post_content, $data['start'], $data['end'] - $data['start'] );
			$blocks = parse_blocks( $markup );
			// if parse_blocks found nothing, but $data is not null - this means data corruption
			if ( count( $blocks ) === 0 ) {
				break;
			}
			$block = $blocks[0];
			$new_attributes = $block['attrs'];
			// if no view attribute is set, this means corrupted block and we're not able to convert it
			if ( ! isset( $new_attributes['view'] ) ) {
				break;
			}
			// if view attribute is just view ID, we can retrieve everything from the DB and go ahead
			if ( is_numeric( $new_attributes['view'] ) ) {
				$view_data = \WP_Post::get_instance( $new_attributes['view'] );
				$new_attributes['view'] = [
					'ID' => ( string ) $new_attributes['view'],
					'post_title' => $view_data->post_title,
					'post_name' => $view_data->post_name,
				];
			} else {
				// otherwise let's do JSON decode
				$view_data = json_decode( $new_attributes['view'] );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					$new_attributes['view'] = $view_data;
				}
			}
			// set missing attributes
			$new_attributes['insertExisting'] = '1';
			$new_attributes['wizardDone'] = true;
			$new_attributes['viewName'] = $view_data->post_title;
			// create new markup
			$new_markup = '<!-- wp:toolset-views/view-editor ' . wp_json_encode( $new_attributes ) . ' -->' .
				'<div class="wp-block-toolset-views-view-editor ">[wpv-view name="' . $view_data->post_name . '"]</div>' .
				'<!-- /wp:toolset-views/view-editor -->';
			// and use it to replace the old markup
			$post->post_content = substr( $post->post_content, 0, $data['start'] ) .
				$new_markup .
				substr( $post->post_content, $data['end'] );
		} while ( null !== $data );
	}

	/**
	 * Creates REST API endpoints for view editor
	 */
	protected function create_rest_endpoints() {
		add_action('rest_api_init', function () {
			/**
			 * @var \OTGS\Toolset\Common\Auryn\Injector
			 */
			$dic = apply_filters( 'toolset_dic', false );

			$view_ordering_fields_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewOrderingFields();
			$view_ordering_fields_controller->register_routes();
			$post_types_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewPostTypes();
			$post_types_controller->register_routes();
			$taxonomies_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewTaxonomies();
			$taxonomies_controller->register_routes();
			$user_groups_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewUserGroups();
			$user_groups_controller->register_routes();
			$view_fields_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewFields();
			$view_fields_controller->register_routes();
			$views_controller = new \OTGS\Toolset\Views\Controllers\V1\Views();
			$views_controller->register_routes();
			$view_query_filter_controller = new \OTGS\Toolset\Views\Controllers\V1\ViewQueryFilter();
			$view_query_filter_controller->register_routes();
			$custom_search_fields_controller = new \OTGS\Toolset\Views\Controllers\V1\CustomSearchFields();
			$custom_search_fields_controller->register_routes();

			$wpa_controller = $dic->make(
				'\OTGS\Toolset\Views\Controllers\V1\Wpa',
				array(
					':views_controller' => $views_controller,
				)
			);
			$wpa_controller->register_routes();
		});
	}

	/**
	 * Register Gutenberg block categories
	 * @param $categories
	 * @param $post
	 * @return array
	 */
	public function register_block_categories($categories, $post) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'toolset-views',
					'title' => __( 'Toolset Views Elements', 'wpv-views' ),
				),
			)
		);
	}

	/**
	 * Callback to render the view editor block as view shortcode ignoring Gutenberg output
	 * @param $attributes
	 * @param $content
	 * @return string
	 */
	public function view_render_callback( $attributes, $content ) {
		if ( ! empty( $attributes['view'] ) ) {
			return $content;
		}
		if ( empty( $attributes['viewId'] ) && empty( $attributes['viewSlug'] ) ) {
			return '';
		}
		$outputClass = " wpv-view-output";
		$class = isset( $attributes['align'] ) && $attributes['align'] ? 'class="' . esc_attr( 'align' . $attributes['align'] . $outputClass ) . '"' : '';
		if ( empty( $class ) ) {
			$class .= 'class="' . esc_attr( $outputClass ) . '"';
		}

		$view_post = get_post( $attributes['viewId'] );
		if (
			! empty( $attributes['viewSlug'] ) &&
			$view_post->ID === $attributes['viewId']
		) {
			return '<div ' . $class . ' data-toolset-views-view-editor="1">[wpv-view name="' . esc_attr( $attributes['viewSlug'] ) . '"]</div>';
		}

		return '<div ' . $class . ' data-toolset-views-view-editor="1">[wpv-view id="' . esc_attr( $attributes['viewId'] ) . '"]</div>';
	}

	/**
	 * Register View editor JS file and frontend CSS
	 */
	public function register_view_editor_assets()
	{
		wp_enqueue_script(
			'register_view_editor_assets', // Unique handle.
			WPV_URL . '/public/js/view-editor.js',
			array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'toolset-common-es', 'views-filters-js', 'wpv-parametric-admin-script'),
			WPV_VERSION
		);
		wp_enqueue_style(
			'register_view_editor_css',
			WPV_URL . '/public/css/view-editor.css',
			array( 'toolset-common-es' ),
			WPV_VERSION
		);
		$default_loop_template = get_user_meta( get_current_user_id(), '_wpv_default_template' );
		if (empty($default_loop_template)) {
			$default_loop_template = null;
		}
		else {
			$default_loop_template = $default_loop_template[0];
		}
		$loop_item_template_on_top = get_user_meta( get_current_user_id(), '_wpv_default_loop_item_on_top' );
		if (empty($loop_item_template_on_top)) {
			$loop_item_template_on_top = 0;
		}
		else {
			$loop_item_template_on_top = $loop_item_template_on_top[0];
		}
		$shortcode_settings = array_map( function( $item ) {
			return is_callable( $item['callback'] ) ? call_user_func( $item['callback'] ) : [];
		}, apply_filters( 'wpv_filter_wpv_shortcodes_gui_data', [] ) );

		/**
		 * Filters the assets of Views related to the block editor.
		 *
		 * @param array $assets
		 *
		 * @return array
		 */
		$view_editor_assets = apply_filters(
			'wpv_filter_localize_view_editor_assets',
			array(
				'currentUser' => wp_get_current_user(),
				'defaultLoopTemplate' => $default_loop_template,
				'loopItemTemplateOnTop' => $loop_item_template_on_top,
				'bootstrapVersion' => \Toolset_Settings::get_instance()->bootstrap_version_numeric,
				'shortcodes_settings' => $shortcode_settings,
				'canEditViews' => $this->user_can_edit_views_as_blocks() ? '1' : '0',
			)
		);

		wp_localize_script(
			'register_view_editor_assets',
			'viewsInfo',
			$view_editor_assets
		);

		//ported from old "View" block
		$locale = null;
		if ( function_exists( 'wp_get_jed_locale_data' ) ) {
			$locale = wp_get_jed_locale_data( 'wpv-views' );
		} elseif ( function_exists( 'gutenberg_get_jed_locale_data' ) ) {
			$locale = gutenberg_get_jed_locale_data( 'wpv-views' );
		} else {
			$locale = array(
				array(
					'domain' => 'wpv-views',
					'lang' => 'en_US',
				),
			);
		}

		$post_types = array();
		$post_types_arr = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $post_types_arr as $post_type_object ) {
			$post_types[] = array(
				'value' => $post_type_object->name,
				'label' => $post_type_object->labels->name
			);
		}
		$rfg_post_types = get_post_types( array( \Toolset_Post_Type_From_Types::DEF_IS_REPEATING_FIELD_GROUP => true ), 'objects' );
		$rfgs = array();
		if ( ! empty( $rfg_post_types ) ) {
			foreach ( $rfg_post_types as $post_type_object ) {
				$rfgs[] = array(
					'value' => $post_type_object->name,
					'label' => $post_type_object->labels->name,
				);
			}
		}

		/**
		 * Filters the localization strings of the View block related to the block editor.
		 *
		 * @param array $assets
		 *
		 * @return array
		 */
		$view_block_strings = apply_filters(
			'wpv_filter_localize_view_block_strings',
			array(
				'blockName' => self::BLOCK_NAME,
				'blockCategory' => \Toolset_Blocks::TOOLSET_GUTENBERG_BLOCKS_CATEGORY_SLUG,
				'publishedViews' => apply_filters( 'wpv_get_available_views', array() ),
				'wpnonce' => wp_create_nonce( \WPV_Ajax::CALLBACK_GET_VIEW_BLOCK_PREVIEW ),
				'actionName' => $this->toolset_ajax_manager->get_action_js_name( \WPV_Ajax::CALLBACK_GET_VIEW_BLOCK_PREVIEW ),
				'locale' => $locale,
				'contentSelectionOptions' => array(
					'post_types' => $post_types,
					'rfgs' => $rfgs,
				),
			)
		);

		wp_localize_script(
			'register_view_editor_assets',
			'toolset_view_block_strings',
			$view_block_strings
		);
	}

	/**
	 * Check whether the current user can create or edit Views as blocks,
	 * either because the selected editing experience does not support that
	 * or because the user does not have the proper capabilities.
	 *
	 * @return bool
	 * @since 3.0
	 */
	private function user_can_edit_views_as_blocks() {
		if ( 'classic' === wpv_get_views_editing_experience() ) {
			return false;
		}

		if ( ! current_user_can( EDIT_VIEWS ) ) {
			return false;
		}

		return true;
	}

	public function register_view_general_assets() {
		/**
		 * register style for frontend
		 */
		wp_enqueue_style(
			'view_editor_gutenberg_frontend_assets',
			WPV_URL . '/public/css/views-frontend.css',
			array( 'toolset-common-es' ),
			WPV_VERSION
		);
	}

	/**
	 * Render footer templates for some Views components, like query and frontend filters.
	 *
	 * @since 2.9
	 */
	public function render_footer_templates() {
		$template_repository = \WPV_Output_Template_Repository::get_instance();
		$renderer = \Toolset_Renderer::get_instance();

		// Template for the ancestor selector on the frontend filter by post relationships
		$renderer->render(
			$template_repository->get( \WPV_Output_Template_Repository::ADMIN_FILTERS_POST_RELATIONSHIP_ANCESTOR_NODE ),
			null
		);
	}

	/**
	 * Gets the parent post id of a View, if any, for the cases that a View is used inside a post.
	 *
	 * @param null|string|int $view_parent_post_id
	 * @param null|string|int $view_id
	 *
	 * @return mixed
	 */
	public function get_view_parent_post_id( $view_parent_post_id, $view_id ) {
		$view = call_user_func( $this->view_get_instance, $view_id );

		if ( null === $view ) {
			return $view_parent_post_id;
		}

		$parent_view_id = (int) $view->get_parent_post_id();

		return $parent_view_id ? $parent_view_id : $view_parent_post_id;
	}
}
