<?php

namespace OTGS\Toolset\Views\Models\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\Common;

/**
 * Custom Search Styles
 *
 * @package OTGS\Toolset\Views\Models\Block\Style\Block
 */
class CustomSearch extends Common {
	const KEY_STYLES_LABEL = 'label';
	const KEY_STYLES_INPUT = 'input';

	/**
	 * @return string
	 */
	public function get_css_block_class() {
		return '.wpv-custom-search-filter';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();

		// Label styles.
		$factory->apply_common_styles_to_block( $this, $config, 'styleLabel', null, self::KEY_STYLES_LABEL );

		// Input styles.
		$factory->apply_common_styles_to_block( $this, $config, 'styleInput', null, self::KEY_STYLES_INPUT );
	}

	private function get_css_config() {
		return [
			'label' .
			'!.editor-rich-text__editable' => [
				self::KEY_STYLES_LABEL => 'all'
			],
			'input' .
			'!button' .
			'!select' .
			'!textarea' => [
				self::KEY_STYLES_INPUT => 'all'
			]
		];
	}
}
