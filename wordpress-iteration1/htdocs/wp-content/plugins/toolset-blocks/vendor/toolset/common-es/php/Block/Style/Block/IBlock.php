<?php

namespace ToolsetCommonEs\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\IAttribute;

/**
 * Interface IBlock
 * @package ToolsetCommonEs\Block\Style\Block
 *
 * @since 1.0.1
 */
interface IBlock {
	/**
	 * Id of the block.
	 *
	 * @return string
	 */
	public function get_id();


	/**
	 * Production ready css string. This string includes selectors and is not intended to be used for inline css.
	 *
	 * @return string
	 */
	public function get_css();

	/**
	 * The block config array provided by the WP core 'render_block' filter.
	 *
	 * @return array
	 */
	public function get_block_config();

	/**
	 * Appends a style to the block.
	 *
	 * @param IAttribute $style_attribute
	 *
	 * @return mixed
	 */
	public function add_style_attribute( IAttribute $style_attribute );

	/**
	 * Allows the block to inject own style attributes. This is useful for styles not covered by common or some
	 * custom way of storing styles.
	 *
	 * @param \ToolsetCommonEs\Block\Style\Attribute\Factory $factory
	 *
	 * @return mixed
	 */
	public function load_block_specific_style_attributes( \ToolsetCommonEs\Block\Style\Attribute\Factory $factory );

	/**
	 * Allows to change the block content output.
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function filter_content( $content );

	/**
	 * Returns used font. Currently this only supports to have ONE font per block.
	 *
	 * @param array $devices
	 *
	 * @return array
	 */
	public function get_font( $devices = [] );

	/**
	 * This is called before collecting CSS, for the case the block needs content information for building the
	 * css. This happens on the TB Heading block, which collects the tagname (h1/h2/..) from the content to add it
	 * to the css selector.
	 *
	 * @param $inner_html
	 *
	 * @return mixed
	 */
	public function make_use_of_inner_html( $inner_html );

	/**
	 * Root class of the block. For example the heading block of TB returns '.tb-heading'. This is not required
	 * but helps to have a more specific selector. More specific selector means higher priority to get the style
	 * applied.
	 *
	 * @return string
	 */
	public function get_css_block_class();


	/**
	 * Currently all blocks have only one html root element, EXCEPT the countdown block, which has
	 * 4 root html elements (days / hours / minutes / seconds). This is important for the style id generation.
	 *
	 * @return int
	 */
	public function get_html_root_element_count();
}
