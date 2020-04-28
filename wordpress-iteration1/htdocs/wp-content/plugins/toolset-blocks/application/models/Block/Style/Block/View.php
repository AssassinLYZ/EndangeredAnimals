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
class View extends Grid {
	public function get_css_block_class() {
		return '.wpv-view-output';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->css_config(), $force_apply, $responsive_device );
	}

	public function css_config() {
		$config = $this->get_config_with_grid();
		$result = parent::css_config();
		// modify grid output to attach styles not to the root selector, but to nested .tb-grid
		$result[ '.js-wpv-loop-wrapper > .tb-grid' ] = $result[ parent::CSS_SELECTOR_ROOT ];
		unset( $result[ parent::CSS_SELECTOR_ROOT ] );
		return $result;
	}
}
