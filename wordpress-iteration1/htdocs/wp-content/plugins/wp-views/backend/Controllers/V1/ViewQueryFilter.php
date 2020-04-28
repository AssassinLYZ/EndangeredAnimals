<?php

namespace OTGS\Toolset\Views\Controllers\V1;

/**
 * Class ViewQueryFilter
 * @package OTGS\Toolset\Views\Controllers\V1
 *
 * @since views-1800
 */
class ViewQueryFilter extends Base {
	/**
	 * Register Route /get_rendered_query_filter
	 */
	public function register_routes() {
		register_rest_route($this->namespace, '/get_rendered_query_filter', array(
			array(
				'methods' => 'POST',
				'callback' => array($this, 'get_rendered_query_filter'),
				'permission_callback' => array( $this, 'can_edit_view' ),
			)
		));
		register_rest_route($this->namespace, '/get_filter_preview', array(
			array(
				'methods' => 'POST',
				'callback' => array($this, 'get_filter_preview'),
				'permission_callback' => array( $this, 'can_edit_view' ),
			)
		) );
	}

	public function get_filter_preview( $request ) {
		$params = $request->get_params();
		do_action( 'wpv_action_wpv_set_current_view', $params['view_id'] );
		$html = wpv_do_shortcode( $params['content'] );
		return new \WP_REST_Response( $html, 200);
	}

	/**
	 * Returns the rendered query filter with all filters applied to the view.
	 *
	 * @param $request
	 *
	 * @return \WP_REST_Response
	 */
	public function get_rendered_query_filter( $request ) {
		$response = $request->get_params();
		$view_id = $response['id'];
		$view_settings_stored = get_post_meta( $response['id'], '_wpv_settings', true );
		$wpv_filter_wpv_get_view_settings_args = array(
			'override_view_settings' => false,
			'extend_view_settings' => false,
			'public_view_settings' => false
		);
		$view_settings = apply_filters( 'wpv_filter_wpv_get_view_settings', array(), $view_id, $wpv_filter_wpv_get_view_settings_args );

		$view_layout_settings_stored = get_post_meta( $view_id, '_wpv_layout_settings', true );

		$view_layout_settings = apply_filters( 'wpv_view_layout_settings', $view_layout_settings_stored, $view_id );
		ob_start();
		\WPV_Editor_Query_Filter::wpv_editor_section_query_filter( $view_settings_stored, null, $display_header = false );
		$html = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'wpv_action_view_editor_section_hidden', array(
				'settings'					=> $view_settings,
				'settings_stored'			=> $view_settings_stored,
				'layout_settings'			=> $view_layout_settings,
				'layout_settings_stored'	=> $view_layout_settings_stored,
				'id'						=> $view_id,
				'user_id'					=> get_current_user_id()
			)
		);
		$html .= ob_get_contents();
		ob_end_clean();

		return new \WP_REST_Response( $html, 200);
	}
}
