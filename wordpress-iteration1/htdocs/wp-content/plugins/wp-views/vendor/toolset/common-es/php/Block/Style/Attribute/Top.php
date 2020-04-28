<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

class Top extends AAttribute {
	private $top;
	private $unit;

	public function __construct( $settings ) {
		if(
			! is_array( $settings ) ||
			! array_key_exists( 'top', $settings )
		) {
			throw new \InvalidArgumentException( 'Invalid attribtue array.' );
		}

		$this->top = is_numeric( $settings['top'] ) ? intval( $settings['top'] ) : null;
		$this->unit = array_key_exists( 'unit', $settings ) && $settings['unit'] == '%' ? '%' : 'px';
	}

	public function get_name() {
		return 'top';
	}

	/**
	 * @return string
	 */
	public function get_css() {
		if( ! empty( $this->top ) || $this->top === 0 ) {
			return 'top: ' . $this->top . $this->unit . ';';
		}
		// no width defined
		return '';
	}
}
