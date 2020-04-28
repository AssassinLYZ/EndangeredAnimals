<?php

namespace OTGS\Toolset\Views\Services;

class WPMLService {
	public function init() {
		add_filter( 'wpv_filter_override_view_layout_settings', array( $this, 'adapt_settings_for_translation' ), 10, 2 );
		add_filter( 'wpv_filter_localize_view_block_strings', array( $this, 'add_if_is_translated_content' ) );
	}

	/**
	 * Modifies View settings if WPML is installed, but only for Views created as blocks, in the frontend.
	 *
	 * @param array $settings View settings.
	 * @param int   $id View/WPA ID.
	 * @return array
	 */
	public function adapt_settings_for_translation( $settings, $id ) {
		// If WPML is active.
		$wpml_active_and_configured = apply_filters( 'wpml_setting', false, 'setup_complete' );
		if ( ! is_admin() && $wpml_active_and_configured && ! \WPV_View_Base::is_archive_view( $id ) && isset( $settings['layout_meta_html'] ) ) {
			$view_data = get_post_meta( $id, '_wpv_view_data', true );
			if ( empty( $view_data ) ) {
				// This View does not hold block data:
				// it was probably created with the legacy editor.
				return $settings;
			}
			// sometimes ID of helper post used for preview generation is passed here
			// but we need to always use correct view ID
			$id = $view_data['general']['id'];
			$helper_id = $view_data['general']['initial_parent_post_id'];
			$post = \WP_Post::get_instance( $helper_id );
			if ( ! $post ) {
				// Maybe the initial post where the View was created has been removed.
				return $settings;
			}
			$translated_helper_id = apply_filters( 'wpml_object_id', $helper_id, $post->post_type, true );
			if ( $helper_id !== $translated_helper_id ) {
				// if post is translated we need to extract the view markup
				// and replace content using it
				// we're extracting view markup because we can have more
				// than one view on a page
				$translated_helper = get_post( $translated_helper_id );
				$service = new ViewParsingService();
				$html = $service->get_view_markup( $translated_helper->ID, $id );
				$settings = self::updateSettingsFromHtml( $html, $settings );
			}
		}
		return $settings;
	}

	public static function updateSettingsFromHtml($html, $settings) {
		$loop_type = $settings['style'];
		switch ( $loop_type ) {
			case 'ordered_list':
			case 'un_ordered_list':
				$loop_start = '<wpv-loop><li>';
				$loop_end = '</li></wpv-loop>';
				break;
			case 'wp_columns':
				$loop_start = '<wpv-loop pad="true"><div class="wpv-grid-column">';
				$loop_end = '</div></wpv-loop>';
				break;
			default:
				$loop_start = '<wpv-loop>';
				$loop_end = '</wpv-loop>';
		}
		// Main content.
		$translated_loop = preg_replace(
			'#^.*(<!-- wp:toolset-views/view-template-block.*-->.*<!-- /wp:toolset-views/view-template-block -->).*$#Us',
			'$1',
			$html
		);
		$settings['layout_meta_html'] = preg_replace(
			'#' . $loop_start . '(.*)' . $loop_end . '#Us',
			$loop_start . do_blocks( $translated_loop ) . $loop_end,
			$settings['layout_meta_html']
		);

		// Top content.
		$translated_loop = preg_replace(
			'#^.*<!-- /wp:toolset-views/view-template-block .*-->(.*)<!-- /wp:toolset-views/view-layout-block -->.*$#Us',
			'$1',
			$html
		);
		$settings['layout_meta_html'] = preg_replace(
			'#\[\/wpv-no-items-found\](.*)\[wpv-layout-end\]#Us',
			'[/wpv-no-items-found]' . do_blocks( $translated_loop ) . '[wpv-layout-end]',
			$settings['layout_meta_html']
		);

		// Bottom content.
		$translated_loop = preg_replace(
			'#^.*<!-- wp:toolset-views/view-layout-block .*-->(.*)<!-- wp:toolset-views/view-template-block.*$#Us',
			'$1',
			$html
		);
		$settings['layout_meta_html'] = preg_replace(
			'#\[wpv-layout-start\](.*)\[wpv-items-found\]#Us',
			'[wpv-layout-start]' . do_blocks( $translated_loop ) . '[wpv-items-found]',
			$settings['layout_meta_html']
		);
		return $settings;
	}

	/**
	 * Stores in `toolset_view_block_strings` if it is translated content
	 *
	 * @param array $data Actual toolset_view_block_strings data.
	 * @return array
	 */
	public function add_if_is_translated_content( $data ) {
		global $post;
		$default_language = apply_filters( 'wpml_default_language', null );
		$translated_id = apply_filters( 'wpml_object_id', $post->ID, $post->post_type, true, $default_language );
		$source_lang = toolset_getget( 'source_lang' );
		$lang = toolset_getget( 'lang' );
		$data['isTranslatedContent'] = $translated_id !== $post->ID || ( $source_lang && $lang && $source_lang !== $lang ) ? 1 : 0;
		return $data;
	}
}
