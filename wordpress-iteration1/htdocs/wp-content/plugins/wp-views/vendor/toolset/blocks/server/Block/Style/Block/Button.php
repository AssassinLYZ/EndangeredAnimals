<?php

namespace ToolsetBlocks\Block\Style\Block;

use ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Block\ABlock;

/**
 * Class Button
 *
 * @package ToolsetBlocks\Block\Style\Block
 */
class Button extends ABlock {
	const KEY_STYLES_FOR_ICON = 'icon';

	public function get_css_block_class() {
		// the space at the end is required
		return '.tb-button ';
	}

	public function get_css( $config = [], $force_apply = false, $responsive_device = null ) {
		return parent::get_css( $this->get_css_config(), $force_apply, $responsive_device );
	}

	/**
	 * @param FactoryStyleAttribute $factory
	 */
	public function load_block_specific_style_attributes( FactoryStyleAttribute $factory ) {
		// icon styles
		$config = $this->get_block_config();

		$icon_styles = isset( $config['icon'] ) && is_array( $config['icon'] ) ? $config['icon'] : false;

		if( empty( $icon_styles ) ) {
			return;
		}

		// font family
		if( isset( $icon_styles[ 'fontFamily' ] ) ) {
			if( $style = $factory->get_attribute( 'font-family', $icon_styles['fontFamily' ] ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_ICON );
			}
		}

		// font code
		$icon_style_font_code =
			isset( $icon_styles['fontCode'] ) &&
			'' !== $icon_styles['fontCode'] ?
				$icon_styles['fontCode'] :
				false;
		if ( $icon_style_font_code ) {
			// I don't know why, font codes like '\f11f' are translated to \f => form feed (FF or 0x0C (12) in ASCII), breaking all CSS rules
			// I wasn't able to figure out why sometimes json_decode translates it properly and in a different WP site it doesn't wrongly
			// Solution: replace it :(
			$font_code = str_replace( "\f", '\f', $icon_styles['fontCode' ] );
			if( $style = $factory->get_attribute( 'content', $font_code ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_ICON );
			}
		}

		// spacing
		$icon_style_spacing =
			$icon_style_font_code && // Spacing only makes sense when an icon is present.
			isset( $icon_styles['spacing'] ) &&
			'' !== $icon_styles['spacing']
			? $icon_styles['spacing'] :
			false;
		if ( $icon_style_spacing ) {
			$position = isset( $icon_styles['position'] ) ? $icon_styles['position'] : 'left';
			$margin = array(
				'enabled' => true,
				'marginTop' => null,
				'marginBottom' => null,
				'marginLeft' => null,
				'marginRight' => null
			);

			if( $position === 'left' ) {
				$margin['marginRight'] = $icon_styles[ 'spacing' ] . 'px';
			} else {
				$margin['marginLeft'] = $icon_styles[ 'spacing' ] . 'px';
			}

			if( $style = $factory->get_attribute( 'margin', $margin ) ) {
				$this->add_style_attribute( $style, self::KEY_STYLES_FOR_ICON );
			}
		}
	}

	private function get_css_config() {
		$styling_styles = [
			'background-color',
			'border-radius',
			'color',
			'padding',
			'margin',
			'box-shadow',
			'border',
		];
		$fonts_styles = [
			'font-size',
			'font-family',
			'font-style',
			'font-weight',
			'line-height',
			'letter-spacing',
			'text-decoration',
			'text-shadow',
			'text-transform',
			'color',
		];

		return array(
			parent::CSS_SELECTOR_ROOT    => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array_merge( $styling_styles, [ 'display' ] ),
			),
			':hover'                     => array(
				parent::KEY_STYLES_FOR_HOVER => $styling_styles,
			),
			// :hover and :focus must be the same.
			':focus'                     => array(
				parent::KEY_STYLES_FOR_HOVER => $styling_styles,
			),
			':active'                    => array(
				parent::KEY_STYLES_FOR_ACTIVE => $styling_styles,
			),
			':visited'                   => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => array( 'color' ),
			),
			'.tb-button__content'        => array(
				parent::KEY_STYLES_FOR_COMMON_STYLES => $fonts_styles,
			),
			':hover .tb-button__content'  => array(
				parent::KEY_STYLES_FOR_HOVER => $fonts_styles,
			),
			':active .tb-button__content' => array(
				parent::KEY_STYLES_FOR_ACTIVE => $fonts_styles,
			),
			'.tb-button__icon'           => array(
				self::KEY_STYLES_FOR_ICON => array(
					'font-family',
					'margin',
				),
			),

			'.tb-button__icon::before'   => array(
				self::KEY_STYLES_FOR_ICON => array(
					'content',
				),
			),
		);
	}
}
