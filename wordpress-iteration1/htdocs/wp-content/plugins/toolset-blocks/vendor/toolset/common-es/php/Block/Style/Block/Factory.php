<?php

namespace ToolsetCommonEs\Block\Style\Block;

use \ToolsetCommonEs\Block\Style\Attribute\Factory as FactoryStyleAttribute;
use ToolsetCommonEs\Block\Style\Responsive\Devices\Devices;

/**
 * Class Factory
 *
 * Maps block array comming from WordPress to our Style/Block class. The array can be filtered, so it's important
 * to prove every key before use.
 *
 * @package ToolsetCommonEs\Block\Style\Block
 */
class Factory {
	/** @var FactoryStyleAttribute */
	private $factory_style_attribute;

	/** @var Devices */
	private $responsive_devices;

	/** @var IFactory[]  */
	private $block_factories = array();

	public function __construct( FactoryStyleAttribute $factory_attribute, Devices $responsive_devices ) {
		$this->factory_style_attribute = $factory_attribute;
		$this->responsive_devices = $responsive_devices;
	}

	/**
	 * A block factory is useful to build blocks which are not directly provided by CommonES (all blocks for now).
	 * The function get_block_by_array() will loop over every sub factory to find a matching block.
	 *
	 * @param IFactory $sub_factory
	 */
	public function add_block_factory( IFactory $sub_factory ) {
		$this->block_factories[] = $sub_factory;
	}

	/**
	 * Returns an array of ToolsetCommonEs\Block\Style\Block by given object.
	 *
	 * @param array $array
	 *
	 * @return IBlock
	 */
	public function get_block_by_array( $array ) {
		if(
			! is_array( $array ) ||
			! array_key_exists( 'blockName', $array ) ||
			! array_key_exists( 'attrs', $array )
		) {
			return;
		}

		$block = false;

		foreach( $this->block_factories as $block_factory ) {
			if( $block = $block_factory->get_block_by_array( $array ) ) {
				// Block found, break out of loop.
				break;
			}
		}

		if( ! $block ) {
			// No Block provided by the sub factories.
			return;
		}

		$this->load_styles_attributes( $block );
		$block->set_name( $array['blockName'] );
		$block->make_use_of_inner_html( $array['innerHTML'] );
		return $block;
	}

	/**
	 * The common style is stored indentically on all blocks.
	 *
	 * @param IBlock $block
	 */
	protected function load_styles_attributes( IBlock $block ) {
		$devices = $this->responsive_devices->get();

		$extra_style_keys = [
			ABlock::KEY_STYLES_FOR_CONTAINER => [ null ],
			'style'                          => [ ABlock::KEY_STYLES_FOR_HOVER, ABlock::KEY_STYLES_FOR_ACTIVE ],
		];


		// Styles provided by the "Style Settings" section.
		foreach( $devices as $device_key => $device_info ) {
			$styles = $this->factory_style_attribute->load_common_attributes_by_array(
				$block->get_block_config(),
				'style',
				null,
				$device_key
			);

			if ( ! empty( $styles ) ) {
				foreach ( $styles as $style ) {
					$block->add_style_attribute( $style, ABlock::KEY_STYLES_FOR_COMMON_STYLES, $device_key );
				}
			}

			foreach ( $extra_style_keys as $extra_key => $subkeys ) {
				foreach ( $subkeys as $subkey ) {
					$styles = $this->factory_style_attribute->load_common_attributes_by_array(
						$block->get_block_config(),
						$extra_key,
						$subkey,
						$device_key
					);

					if ( ! empty( $styles ) ) {
						foreach ( $styles as $style ) {
							$block->add_style_attribute( $style, $subkey ? $subkey : $extra_key, $device_key );
						}
					}
				}
			}
		}

		foreach( $devices as $device_key => $device_info ) {
			$block->load_block_specific_style_attributes( $this->factory_style_attribute, $device_key );
		}
	}
}
