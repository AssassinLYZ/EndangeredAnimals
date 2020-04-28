<?php
/**
 * GroundWP\GroundWP\Schema\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

use InvalidArgumentException;

/**
 * Class for managing component schemas
 */
class Schema {

	/**
	 * Schemas
	 *
	 * @var array
	 */
	protected $schemas = [];

	/**
	 * Component constructor.
	 *
	 * @throws InvalidArgumentException Throws exception at invalid interface.
	 */
	public function __construct() {
		foreach ( $this->get_default_schemas() as $schema ) {
			if ( is_callable( array( $schema, 'add_schema' ) ) ) {
				$this->schemas = array_merge( $this->schemas, $schema::add_schema() );
			} else {
				throw new InvalidArgumentException(
					sprintf(
						/* translators: 1: classname/type of the variable, 2: interface name */
						__( 'Schema file %1$s does not implement the %2$s interface', 'groundwp' ),
						gettype( $schema ),
						SchemaBase_Interface::class
					)
				);
			}
		}
	}

	/**
	 * Get registered schema path
	 *
	 * @param string $path schema path.
	 *
	 * @return mixed schema value
	 */
	public function get_schema( $path ) {
		return static::get_path( $path, $this->schemas );
	}

	/**
	 * Prepare and get schema path value
	 *
	 * @param string $path path to schema.
	 *
	 * @param mixed  $target target to check for.
	 *
	 * @return array|mixed
	 */
	public static function get_path( $path, $target ) {
		$parts = explode( '-', $path );

		$current = $target;
		foreach ( $parts as $part ) {
			if ( isset( $current[ $part ] ) ) {
				$current = $current[ $part ];
			}
		}

		return $current;
	}

	/**
	 * Get default schemas for components
	 *
	 * @return array default schemas
	 */
	protected function get_default_schemas() {
		return [
			Customizer\Schema::class,
			ScrollTop\Schema::class,
			Header\Schema::class,
			Call_To_Action\Schema::class,
			Selection_Popup\Schema::class,
			Related_Posts\Schema::class,
			Styles\Schema::class,
			Base_Support\Schema::class,
		];
	}
}
