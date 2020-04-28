<?php
/**
 * Scroll to top icon template part
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

?>
<svg
		id="stt_svg"
		viewBox="0 0 100 100"
		height="70"
		width="70"
		aria-describedby="title desc"
>
	<title id="title"><?php esc_html_e( 'Scroll to top', 'groundwp' ); ?></title>
	<desc id="desc">
		<?php
		esc_html_e(
			'An arrow pointing upwards to a line which refers to scrolling to the top of the current page.',
			'groundwp'
		);
		?>
	</desc>
	<g
			transform="translate(0,-270.54165)"
			id="stt_svg_layer_wrapper">
		<rect
				ry="5.1213698"
				y="275.54166"
				x="5"
				height="90"
				width="90"
				id="stt_back"
				style="opacity:1;fill-opacity:1;stroke:none;stroke-width:2;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
		<circle
				r="40"
				cy="320.54166"
				cx="50"
				id="stt_circle"
				style="opacity:1;fill-opacity:1;stroke:none;stroke-width:2;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
		<rect
				ry="3.3461304"
				y="295.53781"
				x="26.008877"
				height="6.6922607"
				width="47.982246"
				id="stt_top_line"
				style="opacity:1;fill-opacity:1;stroke:none;stroke-width:2;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
		<path
				id="stt_arrow"
				d="m 49.699219,310.53508 c -0.686472,0.0584 -1.356632,0.35077 -1.884766,0.8789 l -12.787109,12.78907 c -1.19149,1.19149 -1.191489,3.11124 0,4.30273 1.19149,1.19149 3.109292,1.19111 4.300781,0 l 7.505859,-7.50586 v 34.98242 c 0,1.72 1.385472,3.10352 3.105469,3.10352 1.719998,0 3.105469,-1.38352 3.105469,-3.10352 v -35.05273 l 7.626953,7.625 c 1.19149,1.19149 3.109291,1.19111 4.300781,0 1.19149,-1.19149 1.19149,-3.10929 0,-4.30078 L 52.183594,311.46281 c -0.155545,-0.15555 -0.324864,-0.28741 -0.501953,-0.40234 -0.592971,-0.40826 -1.295951,-0.58381 -1.982422,-0.52539 z"
				style="opacity:1;fill-opacity:1;stroke:none;stroke-width:2;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1"/>
	</g>
</svg>
