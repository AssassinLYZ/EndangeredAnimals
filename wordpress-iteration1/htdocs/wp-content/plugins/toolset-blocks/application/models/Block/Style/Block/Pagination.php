<?php

namespace OTGS\Toolset\Views\Models\Block\Style\Block;

use OTGS\Toolset\Views\Models\Block\Style\Block\PaginationType\IPaginationType;
use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\Common;

/**
 * Pagination Styles
 *
 * @package OTGS\Toolset\Views\Models\Block\Style\Block
 */
class Pagination extends Common {
	/** @var IPaginationType[] */
	private $types = [];

	/** @var IPaginationType */
	private $_active_type;

	/**
	 * @param IPaginationType $type
	 */
	public function add_type( IPaginationType $type ) {
		$this->types[] = $type;
	}

	public function __construct( $block_config ) {
		$this->set_id( $block_config );
		$this->set_block_config( $block_config );
	}

	/**
	 * @return string
	 */
	public function get_css_block_class() {
		if( $type = $this->get_active_type() ) {
			return $type->get_css_block_class();
		}
		return '';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		if( ! $type = $this->get_active_type() ) {
			return;
		}

		return parent::get_css( $type->get_css_config(), $force_apply, $responsive_device );
	}

	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();

		// 'left' is the default value, defined in the attributes.js of pagination.
		// This is needed for all PaginationTypes.
		$align = isset( $config[ 'align' ] ) ? $config['align'] : 'left';
		$text_align_possibilites = [ 'left', 'center', 'right'];

		// 'align' can also hold flex values, e.g. for the Next/Prev option there's 'spaceBetween' as option.
		if( in_array( $align, $text_align_possibilites ) ) {
			if( $style = $factory->get_attribute( 'text-align', $align ) ) {
				$this->add_style_attribute( $style, parent::KEY_STYLES_FOR_COMMON_STYLES );
			}
		}

		// Load other PaginationType specific attributes.
		if( $type = $this->get_active_type() ) {
			$type->get_specific_style_attributes( $this, $factory, $config );
		}
	}

	private function get_active_type() {
		if( $this->_active_type !== null ) {
			// Active type already known.
			return $this->_active_type;
		}

		$this->_active_type = false;

		// Determine active type.
		$config = $this->get_block_config();

		// 'link' is the default type, defined on the attributes.js.
		$used_type = isset( $config['type'] ) ? $config['type'] : 'link';

		foreach( $this->types as $type ) {
			if ( $used_type === $type->get_type_name() ) {
				$this->_active_type = $type;
				break;
			}
		}

		return $this->_active_type;
	}
}
