<?php

namespace ToolsetCommonEs\Block\Style\Attribute;

use ToolsetCommonEs\Block\Style\Block\ABlock;
use ToolsetCommonEs\Block\Style\Responsive\Devices\Devices;

/**
 * Class Factory
 *
 * Creates Attribute Object
 *
 * @package ToolsetCommonEs\Block\Style\Attribute
 */
class Factory {

	/** @var Devices */
	private $responsive_devices;

	/**
	 * Factory constructor.
	 *
	 * @param Devices $responsive_devices
	 */
	public function __construct( Devices $responsive_devices ) {
		$this->responsive_devices = $responsive_devices;
	}


	/**
	 * Returns an object of IAttribute by given name and settings
	 *
	 * @param string $name
	 * @param array $settings
	 *
	 * @return IAttribute
	 */
	public function get_attribute( $name, $settings ) {
		try {
			// generalise style name
			$name = strtolower( str_replace( '-', '', $name ) );

			switch( $name ) {
				case 'alignitems':
					return new AlignItems( $settings );
				case 'background':
					return new Background( $settings );
				case 'backgroundcolor':
					return new BackgroundColor( $settings );
				case 'border':
					$top = $this->get_border_side( 'top', $settings );
					$right = $this->get_border_side( 'right', $settings );
					$bottom = $this->get_border_side( 'bottom', $settings );
					$left = $this->get_border_side( 'left', $settings );
					return new Border( $top, $right, $bottom, $left );
				case 'borderradius':
					return new BorderRadius( $settings );
				case 'boxshadow':
					return new BoxShadow( $settings );
				case 'textcolor':
				case 'color':
					return new Color( $settings );
				case 'content':
					return new Content( $settings );
				case 'display':
					return new Display( $settings );
				case 'fontfamily':
					return new FontFamily( $settings );
				case 'fontweight':
					return new FontWeight( $settings );
				case 'fontstyle':
					return new FontStyle( $settings );
				case 'fontsize':
					return new FontSize( $settings );
				case 'height':
					return new Height( $settings );
				case 'justifycontent':
					return new JustifyContent( $settings );
				case 'lineheight':
					return new LineHeight( $settings );
				case 'letterspacing':
					return new LetterSpacing( $settings );
				case 'margin':
					return new Margin( $settings );
				case 'maxwidth':
					return new MaxWidth( $settings );
				case 'minheight':
					return new MinHeight( $settings );
				case 'padding':
					return new Padding( $settings );
				case 'rotate':
					return new Rotate( $settings );
				case 'textalign':
					return new TextAlign( $settings );
				case 'textdecoration':
					return new TextDecoration( $settings );
				case 'texttransform':
					return new TextTransform( $settings );
				case 'textshadow':
					return new TextShadow( $settings );
				case 'top':
					return new Top( $settings );
				case 'width':
					return new Width( $settings );
				case 'scale':
					return new Scale( $settings );
				case 'verticalalign':
					return new VerticalAlign( $settings );
				case 'zindex':
					return new ZIndex( $settings );
				case 'gridtemplatecolumns':
					return new GridTemplateColumns( $settings );
				case 'gridrowgap':
					return new GridRowGap( $settings );
				case 'gridcolumngap':
					return new GridColumnGap( $settings );
				case 'gridautoflow':
					return new GridAutoFlow( $settings );
				case 'gridcolumn':
					return new GridColumn( $settings );
				case 'order':
					return new Order( $settings );
				default:
					return;
			}
		} catch( \Exception $e ) {
			// Attribute could not be build.
			// error_log( 'EXCEPTION: ' . $e->getMessage() . '<br />' . $e->getFile());
			return;
		}
	}

	public function get_attribute_width( $width, $unit ) {
		return $this->get_attribute( 'width', array( 'width' => $width, 'widthUnit' => $unit ) );
	}

	public function get_attribute_max_width( $width, $unit ) {
		return $this->get_attribute( 'max-width', array( 'width' => $width, 'widthUnit' => $unit ) );
	}

	public function get_attribute_height( $height, $unit ) {
		return $this->get_attribute( 'height', array( 'height' => $height, 'heightUnit' => $unit ) );
	}

	public function apply_style_to_block_for_all_devices(
		ABlock $block,
		$styles,
		$style_key,
		$storage_key,
		$custom_block_attribute = null,
		$modify_settings_callback = null
	) {
		$devices = $this->responsive_devices->get();
		foreach( $devices as $device_key => $device ) {
			$device_styles = null;

			if( $device_key === Devices::DEVICE_DESKTOP ) {
				// Desktop Attributes are stored on root.
				$device_styles = $styles;
			} else if( isset( $styles[ $device_key ] ) ) {
				$device_styles = $styles[ $device_key ];
			}

			if( empty( $device_styles ) ) {
				// No styles for this device.
				continue;
			}

			$block_attribute_key = $custom_block_attribute ?: $style_key;

			$settings = null;

			if( $block_attribute_key === 'root' ) {
				$settings = $device_styles;
			} else if( isset( $device_styles[ $block_attribute_key ] ) ) {
				$settings = $device_styles[ $block_attribute_key ];
			}

			$settings = is_callable( $modify_settings_callback ) ?
				$modify_settings_callback( $settings, $device_key === Devices::DEVICE_DESKTOP ) :
				$settings;

			if( $settings === null ) {
				// No settings, no style.
				continue;
			}

			if( ! $style_attribute = $this->get_attribute( $style_key, $settings ) ) {
				// Attribute could not be created.
				continue;
			}

			$block->add_style_attribute( $style_attribute, $storage_key, $device_key );
		}

	}

	/**
	 * @param ABlock $block
	 * @param $config
	 * @param string $styles_key
	 * @param null $subkey
	 * @param string $storage_key
	 */
	public function apply_common_styles_to_block(
		ABlock $block,
		$config,
		$styles_key = 'style',
		$subkey = null,
		$storage_key = ABlock::KEY_STYLES_FOR_COMMON_STYLES
    ) {
		$devices = $this->responsive_devices->get();
		// Styles provided by the "Style Settings" section.
		foreach( $devices as $device_key => $device_info ) {
			$styles = $this->load_common_attributes_by_array(
				$config,
				$styles_key,
				$subkey,
				$device_key
			);

			if ( ! empty( $styles ) ) {
				foreach ( $styles as $style ) {
					$block->add_style_attribute( $style, $storage_key, $device_key );
				}
			}
		}
	}

	/**
	 * @param $config
	 * @param string $styles_key
	 * @param null $subkey
	 * @param null $responsive_device
	 *
	 * @return array
	 */
	public function load_common_attributes_by_array( $config, $styles_key = 'style', $subkey = null, $responsive_device = null ) {
		if( $responsive_device === Devices::DEVICE_DESKTOP ) {
			// Desktop styles are stored on the root of the styles_key.
			$responsive_device = null;
		}

		$attributes = array();

		if( ! is_array( $config ) ) {
			return $attributes;
		}

		if( ! array_key_exists( $styles_key, $config ) ) {
			// The styles key does not exist in the config array.
			return $attributes;
		}

		$styles_config = $config[ $styles_key ];

		// Todo Refactor to get rid of following if mammut tree.
		if( $subkey ) {
			// Subkey wanted.
			if( $responsive_device ) {
				// Subkey and responsive device wanted.
				if(
					array_key_exists( $responsive_device, $styles_config ) &&
					array_key_exists( $subkey, $styles_config[ $responsive_device ] )
				) {
					// Subkey is inside responsive device.
					$styles_config = $styles_config[$responsive_device][ $subkey ];
				} else if (
					array_key_exists( $subkey, $styles_config ) &&
					array_key_exists( $responsive_device, $styles_config[ $subkey ] )
				) {
					// Responsive device is inside subkey.
					$styles_config = $styles_config[ $subkey ][ $responsive_device ];
				} else {
					// Subkey does not exist.
					return $attributes;
				}
			} else if( array_key_exists( $subkey, $styles_config ) ) {
				// Subkey without responsive device.
				$styles_config = $styles_config[ $subkey ];
			} else {
				// Subkey does not exist.
				return $attributes;
			}
		} else if( $responsive_device ) {
			// No subkey, but responsive device wanted.
			if( array_key_exists( $responsive_device, $styles_config ) ) {
				// The wanted responsive styles are not available.
				$styles_config = $styles_config[ $responsive_device ];
			} else {
				// Responsive device does not exist in the attributes.
				return $attributes;
			}
		}

		// Normalise some storages which are not bundled inside an array.
		// Line Height.
		if( isset( $styles_config[ 'lineHeight'] ) && isset( $styles_config['lineHeightUnit'] ) ) {
			$styles_config['lineHeight'] = array(
				'size' => $styles_config['lineHeight'],
				'unit' => $styles_config['lineHeightUnit']
			);

			unset( $styles_config['lineHeightUnit'] );
		}

		// Letter Spacing.
		if( isset( $styles_config[ 'letterSpacing'] ) && isset( $styles_config['letterSpacingUnit'] ) ) {
			$styles_config['letterSpacing'] = array(
				'size' => $styles_config['letterSpacing'],
				'unit' => $styles_config['letterSpacingUnit']
			);

			unset( $styles_config['letterSpacingUnit'] );
		}

		// Font, including font variant.
		if( isset( $styles_config['font'] ) ) {
			$styles_config['fontFamily'] = $styles_config[ 'font' ];

			if(
				! isset( $styles_config['fontWeight'] )
				&& isset( $styles_config['fontVariant'] ) && $styles_config['fontVariant'] !== 'regular'
			) {
				// If 'bold' is not used and fontVariant is not 'regular', use it as fontWeight.
				$styles_config['fontWeight'] = $styles_config['fontVariant'];
			}
		}

		// Min Height.
		if( isset( $styles_config[ 'minHeight'] ) && isset( $styles_config['minHeightUnit'] ) ) {
			$styles_config['minHeight'] = array(
				'minHeight' => $styles_config['minHeight'],
				'minHeightUnit' => $styles_config['minHeightUnit']
			);

			unset( $styles_config['minHeightUnit'] );
		}

		// Width
		if( isset( $styles_config['width'] ) && ! is_array( $styles_config['width'] ) ) {
			$styles_config['width'] = array(
				'width' => $styles_config['width'],
				'widthUnit' => isset( $styles_config['widthUnit'] ) ? $styles_config['widthUnit'] :'px',
			);

			unset( $styles_config['widthUnit'] );
		}

		// Height
		if( isset( $styles_config['height'] ) && ! is_array( $styles_config['height'] ) ) {
			$styles_config['height'] = array(
				'height' => $styles_config['height'],
				'heightUnit' => isset( $styles_config['heightUnit'] ) ? $styles_config['heightUnit'] :'px',
			);

			unset( $styles_config['heightUnit'] );
		}

		// ApplyMaxWidth
		if( isset( $styles_config['applyMaxWidth'] ) ) {
			if( $styles_config['applyMaxWidth'] ) {
				$styles_config['max-width'] = array(
					'width' => 100,
					'widthUnit' => '%',
				);
			}

			unset( $styles_config['applyMaxWidth'] );
		}

		foreach( $styles_config as $key => $value ) {
			$key = $key == 'font' ? 'fontFamily' : $key;
			$key = $key == 'fontVariant' ? 'fontWeight' : $key;

			$style = $this->get_attribute( $key, $value );

			if( $style ) {
				$attributes[] = $style;
			}
		}

		return $attributes;
	}

	/**
	 * @param $side
	 * @param $settings
	 *
	 * @return null|BorderSide
	 */
	private function get_border_side( $side, $settings ) {
		if( ! is_array( $settings ) || ! array_key_exists( $side, $settings ) ) {
			return null;
		}

		return new BorderSide( $side, $settings[ $side ] );
	}
}
