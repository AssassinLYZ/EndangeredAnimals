<?php
/**
 * GroundWP\GroundWP\Image_Sizes\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Image_Sizes;

use GroundWP\GroundWP\Component_Interface;
use function GroundWP\GroundWP\groundwp;
use WP_Post;
use function add_filter;
use function is_active_sidebar;

/**
 * Class for managing responsive image sizes.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'image_sizes';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'wp_calculate_image_sizes', [ $this, 'filter_content_image_sizes_attr' ], 10, 2 );
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'filter_post_thumbnail_sizes_attr' ], 10, 3 );
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for content images.
	 *
	 * @param string $sizes A source size value for use in a 'sizes' attribute.
	 * @param array  $size  Image size. Accepts an array of width and height
	 *                      values in pixels (in that order).
	 * @return string A source size value for use in a content image 'sizes' attribute.
	 */
	public function filter_content_image_sizes_attr( $sizes, array $size ) {
		$width = $size[0];

		if ( 740 <= $width ) {
			$sizes = '100vw';
		}

		if ( groundwp()->is_primary_sidebar_active() ) {
			$sizes = '(min-width: 960px) 75vw, 100vw';
		}

		return $sizes;
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for post thumbnails.
	 *
	 * @param array        $attr       Attributes for the image markup.
	 * @param WP_Post      $attachment Attachment post object.
	 * @param string|array $size       Registered image size or flat array of height and width dimensions.
	 * @return array The filtered attributes for the image markup.
	 */
	public function filter_post_thumbnail_sizes_attr( array $attr, WP_Post $attachment, $size ) {
		$attr['sizes'] = '100vw';

		if ( groundwp()->is_primary_sidebar_active() ) {
			$attr['sizes'] = '(min-width: 960px) 75vw, 100vw';
		}

		return $attr;
	}
}
