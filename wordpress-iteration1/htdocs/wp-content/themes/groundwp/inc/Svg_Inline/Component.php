<?php
/**
 * GroundWP\GroundWP\Svg_Inline\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Svg_Inline;

use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\Slug;
use function get_template_part;

/**
 * Class for inlining svgs
 * Exposes template tags
 * * `groundwp()->inline_svg()`
 */
class Component implements Component_Interface, Templating_Component_Interface {
	use Slug;

	/**
	 * Template part path
	 *
	 * @var string
	 */
	public $template_path = 'template-parts/svg-inline/';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		// nothing here.
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
			'inline_svg' => [ $this, 'inline_svg' ],
		];
	}

	/**
	 * Inline a svg
	 *
	 * @param string $file_basename basename for the svg.
	 */
	public function inline_svg( $file_basename ) {
		get_template_part( $this->template_path . $file_basename );
	}
}
