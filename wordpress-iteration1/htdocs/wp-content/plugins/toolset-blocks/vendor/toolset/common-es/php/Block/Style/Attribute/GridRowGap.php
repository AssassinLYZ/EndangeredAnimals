<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

class GridRowGap extends AAttribute {
	private $grid_row_gap;

	public function __construct( $value ) {
		$this->grid_row_gap = $value;
	}

	public function get_name() {
		return 'grid-row-gap';
	}

	/**
	 * @return string
	 */
	public function get_css() {
		if ( 0 !== $this->grid_row_gap && empty( $this->grid_row_gap ) ) {
			return '';
		}

		return 'grid-row-gap: ' . $this->grid_row_gap . 'px;';
	}
}
