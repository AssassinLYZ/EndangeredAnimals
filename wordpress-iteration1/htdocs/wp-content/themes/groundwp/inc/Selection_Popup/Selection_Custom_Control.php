<?php
/**
 * GroundWP\GroundWP\Selection_Popup\Selection_Custom_Control class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Selection_Popup;

use WP_Customize_Control;
use function wp_enqueue_style;
use function wp_localize_script;
use function GroundWP\GroundWP\groundwp;

/**
 * Custom Popup selection component customizer control
 */
class Selection_Custom_Control extends WP_Customize_Control {
	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'selection_popup';

	/**
	 * Enqueue control scripts and styles
	 */
	public function enqueue() {
		$local_data = [
			'id'    => $this->id,
			'label' => $this->label,
			'items' => $this->value(),
		];

		groundwp()->customize_data( 'control', [ 'groundwpSelectionPopupCustomizerData' => $local_data ], [ 'customize-controls' ] );
	}

	/**
	 * Render control elements to customizer
	 */
	public function render_content() {
		// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require( __DIR__ . '/Selection_Custom_Control_View.php' );
	}
}
