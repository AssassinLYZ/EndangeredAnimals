/**
 * File _groundwp-selection-popup-customize-controls.js
 *
 * Selection Popup customizer custom control
 */
/**
 * External dependencies
 */
import Vue from 'vue';

export default function selectionPopupCustomizeControls() {
	( function noConflict() {
		const selectionPopupCustomizeData = { ...groundwpSelectionPopupCustomizerData };

		groundwpSelectionPopupCustomizerData = undefined;

		wp.customize.bind( 'ready', function() {
			Vue.filter( 'cap', function( val ) {
				return val[ 0 ].toUpperCase() + val.slice( 1 );
			} );

			Vue.component( 'sp-customizer-item', {
				data() {
					return {
						showDelete: false,
					};
				},
			} );

			new Vue( {
				data: selectionPopupCustomizeData,
				watch: {
					items: {
						handler() {
							wp.customize( selectionPopupCustomizeData.id, function( setting ) {
								setting.set( selectionPopupCustomizeData.items );
							} );
						}, deep: true,
					},
				},
			} ).$mount( `#${ selectionPopupCustomizeData.id }` );
		} );
	}() );
}
