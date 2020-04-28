<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

class FontStyle extends AAttribute {
	private $font_style;

	public function __construct( $value ) {
		$this->font_style = is_array( $value ) ? implode( ' ', $value ) : $value;
	}

	public function get_name() {
		return 'font-style';
	}

	/**
	 * @return string
	 */
	public function get_css() {
		if( empty( $this->font_style ) ) {
			return '';
		}

		return "font-style: $this->font_style;";
	}
}
