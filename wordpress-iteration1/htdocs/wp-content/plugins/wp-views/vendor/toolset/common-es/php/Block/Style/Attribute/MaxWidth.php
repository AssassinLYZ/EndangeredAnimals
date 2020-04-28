<?php
namespace ToolsetCommonEs\Block\Style\Attribute;

/**
 * Class MaxWidth
 *
 * Factory has a helper method get_attribute_max_width( $width, $unit).
 * Or see Width class for the expected config keys.
 *
 * @package ToolsetCommonEs\Block\Style\Attribute
 */
class MaxWidth extends Width {
	public function get_name() {
		return 'max-width';
	}
}
