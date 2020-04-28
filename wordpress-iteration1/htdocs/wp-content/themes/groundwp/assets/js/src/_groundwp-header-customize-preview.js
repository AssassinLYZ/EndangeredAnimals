/**
 * File _groundwp-header-customize-preview.js.
 *
 * Theme Customizer enhancements for header theme options
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
export default function headerPreview() {
	( function noConflict( $ ) {
		// localized setting object
		const _cOptions = { ...customizeSettings };

		// remove setting object from global space
		customizeSettings = undefined;

		wp.customize.bind( 'preview-ready', function() {
			wp.customize( _cOptions.settings_ids.logo_options_position, function( value ) {
				value.bind( function( to ) {
					const logoClassPrefix = _cOptions.extras.logo_class_prefix;
					const classList = $( '#masthead' ).attr( 'class' ).split( ' ' );

					classList.map( ( c ) => {
						if ( c.includes( logoClassPrefix ) ) {
							$( '#masthead' ).removeClass( c );
						}
						return null;
					} );

					$( '#masthead' ).addClass( logoClassPrefix + to );
				} );
			} );

			wp.customize( _cOptions.settings_ids.header_options_width, function( value ) {
				value.bind( function( to ) {
					const headerWidthClass = _cOptions.extras.header_width_full_class;
					if ( to === 'content' ) {
						$( '.header-container' ).removeClass( headerWidthClass );
					} else {
						$( '.header-container' ).addClass( headerWidthClass );
					}
				} );
			} );
		} );
	}( jQuery ) );
}
