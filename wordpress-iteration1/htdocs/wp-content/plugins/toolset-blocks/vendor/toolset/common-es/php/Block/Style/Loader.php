<?php

namespace ToolsetCommonEs\Block\Style;

use ToolsetCommonEs\Block\Style\Block\Factory;
use ToolsetCommonEs\Block\Style\Block\IBlock;
use ToolsetCommonEs\Block\Style\Responsive\Devices\Devices;
use ToolsetCommonEs\Library\WordPress\Actions as WPActions;

class Loader {
	/** @var Factory  */
	private $block_factory;

	/** @var IBlock[] */
	private $blocks = array();

	/** @var WPActions */
	private $wp_actions;

	/** @var Devices */
	private $responsive_devices;

	/** @var array */
	private $blocks_applied = array();

	/**
	 * Blocks Style Backup is needed for reappling style when automatic excerpt is used.
	 * Because WP does trimming on the content and removes our structure.
	 * This is used to reapply it after trimming of WP was done.
	 * KEY = base64 encoded style (that's left after WP trim)
	 * VALUE = complete structure, div > base64 code && script to move it to head
	 *
	 * @var array
	 */
	private $blocks_style_backup = array();

	/**
	 * Loader constructor.
	 *
	 * @param Factory $block_factory
	 * @param WPActions $wp_actions
	 * @param Devices $responsive_devices
	 */
	public function __construct( Factory $block_factory, WPActions $wp_actions, Devices $responsive_devices ) {
		$this->block_factory = $block_factory;
		$this->wp_actions = $wp_actions;
		$this->responsive_devices = $responsive_devices;

		$this->wp_actions->add_filter( 'render_block', array( $this, 'register_block' ), 10, 2 );
		$this->wp_actions->add_filter( 'wpv_filter_wpv_view_shortcode_output', array( $this, 'views_pagination_content_filter' ), 10, 2 );
		$this->wp_actions->add_action( 'wp', array( $this, 'hook_up_frontend' ) );
		$this->wp_actions->add_filter( 'wpv-pre-do-shortcode', array( $this, 'hook_to_wpv_post_do_shortcode' ) );

		$this->wp_actions->add_filter( 'toolset/dynamic_sources/actions/register_sources', array( $this, 'hook_to_dynamic_sources' ) );
	}

	public function hook_up_frontend() {
		$doing_rest_request = defined( 'REST_REQUEST' ) && REST_REQUEST;

		if( ! $doing_rest_request ) {
			$this->wp_actions->add_filter( 'the_content', array( $this, 'apply_blocks_style_by_script_to_head' ), PHP_INT_MAX - 1, 1 );
			$this->wp_actions->add_filter( 'toolset_the_content_basic_formatting', array( $this, 'apply_blocks_style_by_script_to_head' ), PHP_INT_MAX - 1, 1 );
			$this->wp_actions->add_filter( 'wp_trim_excerpt', array( $this, 'repair_excerpt_trim_ruin_block_style' ), PHP_INT_MAX, 2 );
		}
	}

	public function hook_to_dynamic_sources() {
		$this->wp_actions->add_filter( 'render_block', array( $this, 'register_block' ), 10, 2 );
		// THIS SHOULD NO LONGER BE NEEDED DUE TO CHANGES ON views-2911
		// $this->wp_actions->add_filter( 'the_content', array( $this, 'apply_blocks_style_directly' ), PHP_INT_MAX, 1 );
	}

	/**
	 * This is called on 'wpv-pre-do-shortcode'. Simply because REST_REQUEST is not loaded on init.
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function hook_to_wpv_post_do_shortcode( $content ) {
		// only need to hook once
		$this->wp_actions->remove_filter( 'wpv-pre-do-shortcode', array( $this, 'hook_to_wpv_post_do_shortcode' ) );

		// When on admin or on a rest call we need to apply the style directly to the loop item.
		if( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			// this is for the preview of Views on posts
			$this->wp_actions->add_filter( 'wpv-post-do-shortcode', array( $this, 'apply_blocks_style_directly' ), 10, 2 );
		}

		// also need to hook to render_block() for collecting block css
		$this->wp_actions->add_filter( 'render_block', array( $this, 'register_block' ), 10, 2 );

		// THIS SHOULD NO LONGER BE NEEDED DUE TO CHANGES ON views-2911
		// add 'apply_blocks_style_directly' to views after render hook
		// $this->wp_actions->add_filter( 'the_content', array( $this, 'apply_blocks_style_by_script_to_head' ), PHP_INT_MAX - 1, 1 );

		return $content;
	}

	public function apply_blocks_style_by_script_to_head( $content ) {
		$content = $this->block_specific_content_filter( $content );
		if( empty( $content ) ) {
			return $content;
		}



		// Reverse blocks. This is important to have inner blocks styles AFTER outer blocks as later applied style
		// has an higher priority. Otherwise the inner blocks style would not be applied.
		$reversed_blocks = array_reverse( $this->blocks );
		$devices = $this->responsive_devices->get();
		$media_styles = '';

		$media_break_points = array_map( function( $device_info ) {
			return isset( $device_info['maxWidth'] ) && $device_info['maxWidth'] ?
				(int) $device_info['maxWidth'] :
				PHP_INT_MAX;
		}, $devices );

		asort( $media_break_points );

		foreach( $devices as $device_key => $device_info ) {
			$style = '';

			foreach( $reversed_blocks as $block ) {
				$this->blocks_applied[] = $block->get_id();
				$style .= $block->get_css( [], true, $device_key );
			}

			if( empty( $style ) ) {
				continue;
			}

			// Filter display from style.
			$media_max_width = isset( $device_info['maxWidth'] ) && $device_info['maxWidth']
				? $device_info['maxWidth']
				: PHP_INT_MAX;

			if( preg_match( '/([^\}]*)?{(?:[^\}]*)?(display: none;)/', $style, $matches ) ) {
				reset( $media_break_points );
				while( key( $media_break_points ) !== $device_key ) {
					next( $media_break_points );
				}

				if( $media_min_width = prev( $media_break_points ) ) {
					// Remove the display none from the current style set.
					$style = str_replace( 'display: none;', '', $style );

					// Add the display none to a media range, to make it only active for the curr
					$media_styles .= '@media only screen and (min-width: ' . ($media_min_width + 1) . 'px) ';
					$media_styles .= $media_max_width < PHP_INT_MAX ?
						'and (max-width: ' . $media_max_width . 'px) ' :
						'';
					$media_styles .= '{ ' . trim( $matches[1] ) . ' { display: none; } } ';
				}
			}

			$media_styles .= $media_max_width < PHP_INT_MAX ?
				'@media only screen and (max-width: ' . $device_info['maxWidth'] . 'px) { ' . $style . ' } ' :
				$style;
		}



		if( ! empty( $media_styles ) ) {
			$style_encoded =  base64_encode( $media_styles );
			$style_div_with_script = '<div class="toolset-blocks-styling-tmp" style="display:none;">' .
									$style_encoded .
									'</div>' .
									$this->js_to_move_style_to_head();

			// Put to backup for later re-apply. In the case WP excerpt trim runs.
			$this->blocks_style_backup[ $style_encoded ] = $style_div_with_script;

			// Apply to content.
			$content = $style_div_with_script . $content;
		}
		$fonts = $this->block_fonts();
		$font_sets = array();
		foreach( $fonts as $family => $variants ) {
			$font_sets[] = str_replace( ' ', '+', $family ) . ':' . implode( ',', $variants );
		}

		if( ! empty( $font_sets ) ) {
			$font_sets_encoded = base64_encode( implode( '###', $font_sets ) );
			$font_sets_div_with_script = '<div class="toolset-blocks-styling-font-links-tmp" style="display:none;">' .
										 $font_sets_encoded .
										 '</div>' .
										 $this->js_to_move_fonts_to_head();

			// Put to backup for later re-apply. In the case WP excerpt trim runs.
			$this->blocks_style_backup[ $font_sets_encoded ] = $font_sets_div_with_script;

			// Apply to content.
			$content = $font_sets_div_with_script . $content;
		}

		return $content;
	}

	public function apply_blocks_style_directly( $content, $doing_excerpt ) {
		$responsive_device = apply_filters( 'wpv_view_block_preview_for_responsive_device', null );

		if( $doing_excerpt || empty( $responsive_device ) ) {
			return $content;
		}
		$devices = $this->responsive_devices->get();
		$content = $this->block_specific_content_filter( $content );

		$fonts = $this->block_fonts();
		$font_sets = array();
		foreach( $fonts as $family => $variants ) {
			$font_sets[] =  str_replace( ' ', '+', $family ) . ':' . implode( ',', $variants );
		}

		$content = empty( $font_sets )
			? $content
			: '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . implode( '|', $font_sets ) . '" />' . $content;

		$style = '';

		// Reverse blocks. This is important to have inner blocks styles AFTER outer blocks as later applied style
		// has an higher priority. Otherwise the inner blocks style would not be applied.
		$reversed_blocks = array_reverse( $this->blocks );

		foreach( $reversed_blocks as $block ) {
			if ( in_array( $block->get_id(), $this->blocks_applied ) ) {
				// already applied
				continue;
			}

			foreach( $devices as $device_key => $device_data ) {
				$this->blocks_applied[] = $block->get_id();
				$style .= $block->get_css( array(), true, $device_key );

				if( $device_key === $responsive_device ) {
					// Only load css until the current selected device is reached.
					// When tablet is selected: Desktop is loaded first, then tablet and phone is skipped.
					// Why not only loading current selected device's style? Cascading needs to be kept!
					break;
				}
			}
		}


		$content = empty( $style )
			? $content
			: '<style>' . $style . '</style>' . $content;

		return $content;
	}

	/**
	 * Returns the style of a block.
	 *
	 * @param $block_content
	 * @param \WP_Block_Parser_Block $block The class typing is not part of the parameter list as the parse blocks
	 *                                        parser can be alter by a filter. So it's not really sure if we get
	 *                                        WP_Block_Parser_Block here. Check that params exist before use!
	 *
	 * @return string
	 */
	public function register_block( $block_content, $block ) {
		try {
			$supported_block = $this->block_factory->get_block_by_array( $block );

			if( $supported_block ) {
				if( ! array_key_exists( $supported_block->get_id(), $this->blocks ) ) {
					$this->blocks[ $supported_block->get_id() ] = $supported_block;
				}

				$updated_content = preg_replace_callback(
					'/(data-'. str_replace( '/', '-', $supported_block->get_name() ) .')=\"([^\"]*)\"/',
					function( $matches ) use ( $supported_block ) {
						return $matches[1] . '="' . $supported_block->get_id() . '"';
					},
					$block_content,
					$supported_block->get_html_root_element_count()
				);

				return $updated_content;
			}
		} catch( \Exception $e ) {
			// Something went wrong, which may end in an unexpected display on the frontend.
			// Do nothing.
		}

		return $block_content;
	}

	public function repair_excerpt_trim_ruin_block_style( $excerpt, $raw_excerpt ) {
		if( empty( $this->blocks_style_backup ) ) {
			// No block styling at all.
			return $excerpt;
		}

		// Replace all style base64 strings by the complete required html structure / scripts.
		return str_replace(
			array_keys( $this->blocks_style_backup ),
			array_values( $this->blocks_style_backup ),
			$excerpt
		);
	}

	private function block_specific_content_filter( $content ) {
		foreach( $this->blocks as $block ) {
			$content = $block->filter_content( $content );
		}

		return $content;
	}

	/**
	 * This is required for views ajax pagination. Otherwise DS inside styles (like background of Container) are
	 * not applied on page switches.
	 *
	 * @param $content
	 * @param $id
	 *
	 * @return mixed
	 */
	public function views_pagination_content_filter( $content, $id ) {
		foreach( $this->blocks as $block ) {
			$content = $block->filter_content( $content );
		}

		return $content;
	}

	private function block_fonts() {
		$fonts = array();
		foreach( $this->blocks as $block ) {
			$block_fonts = $block->get_font( $this->responsive_devices->get() );

			foreach( $block_fonts as $block_font ) {
				$family = $block_font['family'];
				$variant = $block_font['variant'];

				if( ! isset( $fonts[ $family ] ) ) {
					// New font.
					$fonts[ $family ] = array( $variant );
				} elseif( ! in_array( $variant, $fonts[ $family ] ) ) {
					// Existing font, but variant is new.
					$fonts[ $family ][] = $variant;
				}
			}
		}

		return $fonts;
	}

	private function js_to_move_style_to_head() {
		return "<script class='toolset-blocks-script-tmp' type='text/javascript'>
if ( ! document.getElementById( 'toolset-blocks-styling' ) ) {
		document.head.insertAdjacentHTML(
			'beforeend',
			'<style id=\"' + 'toolset-blocks-styling' + '\"></style>' );
}
		var style = document.getElementById( 'toolset-blocks-styling' );
		var styleTmp = document.querySelector( '.toolset-blocks-styling-tmp' );

		if( style && styleTmp ) {
			var currentStyle = style.innerHTML;
			var newStyle = window.atob( styleTmp.innerHTML );
			
			if( currentStyle.indexOf( newStyle ) !== -1 ) {
				// newStyle is already part of currentStyle.
				// Do Nothing.
			} else if( newStyle.indexOf( currentStyle ) !== -1 ) {
				// currentStyle is part of newStyle. Only use newStyle.
				style.innerHTML = newStyle;
			} else {
				// newStyle is not part of currentStyle, neither is currentStyle part of newStyle. 
				// Keep currentStyle and append newStyle.
				style.appendChild( document.createTextNode( newStyle ) );
			}
		}
	
	var styleTmp = document.getElementsByClassName( 'toolset-blocks-styling-tmp' );
	while( styleTmp[0] ) {
		styleTmp[0].parentNode.removeChild( styleTmp[0] );
	}
	var scriptTmp = document.getElementsByClassName( 'toolset-blocks-script-tmp' );
	while( scriptTmp[0] ) {
		scriptTmp[0].parentNode.removeChild( scriptTmp[0] );
	}
</script>";
	}

	private function js_to_move_fonts_to_head() {
		return "<script class='toolset-blocks-script-font-tmp' type='text/javascript'>

	var fontLinksRaw = document.querySelector( '.toolset-blocks-styling-font-links-tmp' );

	if( fontLinksRaw ) {
		fontLinks = window.atob( fontLinksRaw.innerHTML ).split( '###' );
		var href = 'https://fonts.googleapis.com/css?family=' + fontLinks.join( '|' );
		var fontAlreadyApplied = document.querySelector( \"link[href='\" + href + \"']\" );

		if ( ! fontAlreadyApplied ) {
			var newLink = document.createElement( 'link' );
			newLink.href = href;
			newLink.rel = 'stylesheet';
			document.head.appendChild( newLink );
		}
	}

	var styleTmp = document.getElementsByClassName( 'toolset-blocks-styling-font-links-tmp' );
	while( styleTmp[0] ) {
		styleTmp[0].parentNode.removeChild( styleTmp[0] );
	}
	var scriptTmp = document.getElementsByClassName( 'toolset-blocks-script-font-tmp' );
	while( scriptTmp[0] ) {
		scriptTmp[0].parentNode.removeChild( scriptTmp[0] );
	}
</script>";
	}
}
