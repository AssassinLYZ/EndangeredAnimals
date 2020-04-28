<?php
/**
 * GroundWP\GroundWP\Traits\OwnSchema class.
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Traits;

use InvalidArgumentException;
use GroundWP\GroundWP\Schema;

trait OwnSchema {

	/**
	 * Get component schema
	 *
	 * @param string $path path to schema value where array keys are glued with hyphen.
	 *
	 * @return mixed schema
	 */
	public function schema( $path ) {
		$classname_to_path = str_replace( '\\', '/', __CLASS__ );
		$calling_class_namespace = str_replace( '/', '\\', dirname( $classname_to_path ) );

		$component_schema = call_user_func( [ $calling_class_namespace . '\Schema', 'add_schema' ] );

		return Schema::get_path( $path, array_values( $component_schema )[0] );
	}
}

