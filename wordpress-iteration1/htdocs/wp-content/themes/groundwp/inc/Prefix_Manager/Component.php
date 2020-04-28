<?php
/**
 * GroundWP\GroundWP\Prefix_Manager\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Prefix_Manager;

use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\Slug;

/**
 * Class for managing theme wide prefixes for options/keys/mods etc...
 *
 * Exposes template tags:
 * * `groundwp()->get_prefix()`
 * * `groundwp()->get_default_prefix()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	use Slug;

	/**
	 * Default glue string
	 *
	 * @var string
	 */
	protected $glue;

	/**
	 * Holder for prefixes
	 *
	 * @var array
	 */
	protected $prefixes;

	/**
	 * Component constructor.
	 *
	 * @param string $glue Glue to apply between prefix and target string.
	 * @param array  $prefixes Array of prefixes with key=>value pairs.
	 */
	public function __construct( $glue, $prefixes ) {
		$this->glue     = $glue;
		$this->prefixes = $prefixes;
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		// nothing here...
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `groundwp()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() {
		return [
			'get_prefix'         => [ $this, 'get_prefix' ],
			'get_default_prefix' => [ $this, 'get_default_prefix' ],
		];
	}

	/**
	 * Combine supplied prefix with the default glue string
	 *
	 * @param string $target Target string.
	 * @param string $prefix Supplied prefix.
	 *
	 * @return string Combined prefix string.
	 */
	private function combine_prefix( $target, $prefix ) {
		return $prefix . $this->glue . $target;
	}

	/**
	 * Get prefixed target from defined prefixes array
	 *
	 * @param string $target Target string.
	 * @param string $prefix_key ='default' Key of prefix value.
	 *
	 * @return string Target string prefixed with prefix.
	 */
	public function get_prefix( $target, $prefix_key = 'default' ) {
		if ( isset( $this->prefixes[ $prefix_key ] ) ) {
			return $this->combine_prefix( $target, $this->prefixes[ $prefix_key ] );
		} else {
			return $this->combine_prefix( $target, $this->prefixes['default'] );
		}
	}

	/**
	 * Shortcut function to get prefixed target based on default value of prefixes array.
	 *
	 * @param string $target Target string to be prefixed.
	 * @return string Target string prefixed with default prefix.
	 */
	public function get_default_prefix( $target ) {
		return $this->get_prefix( $target, 'default' );
	}
}
