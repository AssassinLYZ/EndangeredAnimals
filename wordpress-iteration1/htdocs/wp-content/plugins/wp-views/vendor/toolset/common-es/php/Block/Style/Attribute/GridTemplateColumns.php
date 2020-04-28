<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

class GridTemplateColumns extends AAttribute {
	private $grid_template_columns;

	public function __construct( $value ) {
		$this->grid_template_columns = $value;
	}

	public function get_name() {
		return 'grid-template-columns';
	}

	/**
	 * @return string
	 */
	public function get_css() {
		if( empty( $this->grid_template_columns ) ) {
			return '';
		}
		$columns = implode(
			' ',
			array_map(
				function ( $v ) {
					return 'minmax(0, ' . $v . 'fr)';
				},
				$this->grid_template_columns
			)
		);

		return "grid-template-columns: $columns;";
	}
}
