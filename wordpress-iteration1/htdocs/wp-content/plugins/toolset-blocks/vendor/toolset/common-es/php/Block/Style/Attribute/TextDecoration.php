<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

class TextDecoration extends AAttribute {
	private $text_decoration;

	public function __construct( $value ) {
		$this->text_decoration = is_array( $value ) ? implode( ' ', $value ) : $value;
	}

	public function get_name() {
		return 'text-decoration';
	}

	/**
	 * @return string
	 */
	public function get_css() {
		if( empty( $this->text_decoration ) ) {
			return '';
		}

		return "text-decoration: $this->text_decoration;";
	}
}
