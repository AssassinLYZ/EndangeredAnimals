/**
 * File _groundwp-scroll-to-top-customize-preview.js
 *
 * Scroll to top component customize preview
 */
export default function scrollToTopPreview() {
	( function noConflict( $ ) {
		const scrollToTopCustomizerData = { ...groundwpScrollToTopCustomizeData };

		groundwpScrollToTopCustomizeData = undefined;

		wp.customize.bind( 'preview-ready', function() {
			wp.customize( scrollToTopCustomizerData.settings.xOffset, function( value ) {
				value.bind( function( to ) {
					wp.customize( scrollToTopCustomizerData.settings.side, function( setting ) {
						const el = $( '#' + scrollToTopCustomizerData.elementId );
						const isRight = setting.get() === 'right';
						el.css( isRight ? 'right' : 'left', to + '%' );
					} );
				} );
			} );
			wp.customize( scrollToTopCustomizerData.settings.yOffset, function( value ) {
				value.bind( function( to ) {
					const el = $( '#' + scrollToTopCustomizerData.elementId );
					el.css( 'bottom', to + '%' );
				} );
			} );
		} );
	}( jQuery ) );
}
