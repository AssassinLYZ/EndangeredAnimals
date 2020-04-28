<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

class GridColumnGap extends AAttribute {
	private $grid_column_gap;

	public function __construct( $value ) {
		$this->grid_column_gap = $value;
	}

	public function get_name() {
		return 'grid-column-gap';
	}

	/**
	 * @return string
	 */
	public function get_css() {
		if ( 0 !== $this->grid_column_gap && empty( $this->grid_column_gap ) ) {
			return '';
		}
		return 'grid-column-gap: ' . $this->grid_column_gap . 'px;';
	}
}
