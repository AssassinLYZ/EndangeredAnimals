<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\Common;

/**
 * Class Heading
 *
 * @package ToolsetBlocks\Block\Style\Block
 */
class Container extends Common {
	const KEY_STYLES_FOR_INNER = 'inner';

	/**
	 * @return string
	 */
	public function get_css_block_class() {
		return '.tb-container';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		$config = $this->get_block_config();

		if( isset( $config['style'] ) ) {
			// Padding
			$factory->apply_style_to_block_for_all_devices(
				$this,
				$config['style'],
				'padding',
				self::KEY_STYLES_FOR_COMMON_STYLES,
				null,
				function( $settings, $is_desktop ) {
					$padding_default = $is_desktop ?
						array(
							'enabled' => true,
							'paddingTop' => '25px',
							'paddingLeft' => '25px',
							'paddingRight' => '25px',
							'paddingBottom' => '25px',
						) :
						array(
							'enabled' => true
						);

					if( $settings === null ) {
						return $padding_default;
					}

					if( is_array( $settings ) ) {
						return array_merge( $padding_default, $settings );
					}
					return $padding_default;
				}
			);

			// Margin
			$factory->apply_style_to_block_for_all_devices(
				$this,
				$config['style'],
				'margin',
				self::KEY_STYLES_FOR_COMMON_STYLES,
				null,
				function( $settings ) {
					if( $settings === null ) {
						return null;
					}

					$margin_defaults = array(
						'enabled' => true
					);

					if( is_array( $settings ) ) {
						return array_merge( $margin_defaults, $settings );
					}
					return $margin_defaults;
				}
			);

			// Is Image Background
			$is_background_image = isset( $config['style']['background'] ) &&
								   isset( $config['style']['background']['type'] ) &&
								   $config['style']['background']['type'] === 'image';

			// Dynamic Image Background
			$is_dynamic_background = $is_background_image &&
									 isset( $config['dynamic'] ) &&
									 isset( $config['dynamic']['backgroundImage'] ) &&
									 isset( $config['dynamic']['backgroundImage']['isActive'] ) &&
									 $config['dynamic']['backgroundImage']['isActive'];

			if( $is_dynamic_background ) {
				$background_settings = isset( $config['style']['background'] ) ? $config['style']['background'] : array();
				$background_settings['image']['url'] = isset( $config['backgroundImage'] ) ?
					$config['backgroundImage'] :
					null;

				if( $style = $factory->get_attribute( 'background', $background_settings ) ) {
					$this->add_style_attribute( $style, self::KEY_STYLES_FOR_COMMON_STYLES );
				}
			}
		}

		$inner_styles = isset( $config['inner'] ) && is_array( $config['inner'] ) ? $config['inner'] : false;

		if( $inner_styles ) {
			// Max Wdith
			$factory->apply_style_to_block_for_all_devices(
				$this,
				$inner_styles,
				'max-width',
				self::KEY_STYLES_FOR_INNER,
				'root',
				function( $settings ) {
					if( ! is_array( $settings ) ) {
						return $settings;
					}
					$settings['widthUnit'] = isset( $settings['widthUnit'] ) ? $settings['widthUnit'] : '%';
					return $settings;
				}
			);
		}
	}

	/**
	 * Dynamic background image is applied via data-bg.
	 * @param $content
	 *
	 * @return mixed|string|string[]|null
	 */
	public function filter_content( $content ) {
		$content = parent::filter_content( $content );

		$style_attributes = $this->get_style_attributes();
		$background = isset( $style_attributes[self::KEY_STYLES_FOR_COMMON_STYLES]['background'] ) ?
			$style_attributes[self::KEY_STYLES_FOR_COMMON_STYLES]['background']->get_css() :
			'';

		$content = preg_replace_callback(
			'/(\<[^\>]*'.$this->get_id().'[^\>]*)(data-bg)=\"([^\"]*)\"/',
			function( $matches ) use ( $background ) {
				$new_background = $background;
				if( strpos( $background, 'url' ) !== false ) {
					// When the style attribute already provides a background url, we just need to replace that.
					$new_background = preg_replace( '/url\([^\)]*\)/', 'url(' . $matches[3] . ')', $new_background );
				}
				return $matches[1] . 'style="' . $new_background . '"';
			},
			$content
		);

		return $content;
	}

	private function get_css_config() {
		return array(
			parent::CSS_SELECTOR_ROOT => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'background-color', 'border-radius', 'background', 'padding', 'margin', 'box-shadow', 'border',
					'min-height', 'vertical-align', 'display'
				)
			),
			'> .tb-container-inner' => array(
				self::KEY_STYLES_FOR_INNER => array( 'max-width' )
			),
			'p' => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array(
					'font-size', 'font-family', 'font-style', 'font-weight', 'line-height', 'letter-spacing',
					'text-decoration', 'text-shadow', 'text-transform', 'color'
				)
			),
		);
	}
}
