<?php

namespace OTGS\Toolset\Views\Models\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\Common;
use ToolsetBlocks\Block\Style\Block\Grid;
/**
 * Loop Item Styles
 *
 * @package OTGS\Toolset\Views\Models\Block\Style\Block
 */
class WPA extends View {
	public function get_css_block_class() {
		return '.wp-block-toolset-views-wpa-editor';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		$css = parent::get_css( $this->css_config(), $force_apply, $responsive_device );
		$css = preg_replace(
			'/\[(data-toolset-views-wpa-editor)=\"([^\"]*)\"\]/',
			'',
			$css
		);
		return $css;
	}
}
