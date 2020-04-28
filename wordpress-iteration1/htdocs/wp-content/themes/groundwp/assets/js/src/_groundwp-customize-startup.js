/**
 * File _groundwp-customize-startup.js
 *
 * Bootstrap file for custom customize controls/settings
 */

export default function startup() {
	( function noConflict( $ ) {
		const customizerStartupData = { ...groundwpCustomizerStartup };

		groundwpCustomizerStartup = undefined;

		wp.customize.bind( 'ready', function() {
			const allPanels = $( `li[id^="${ customizerStartupData.elements.panel.id }"]` );
			// customizer theme brand header
			$( '<div/>', {
				text: 'GroundWP',
				class: 'groundwp-customizer-panel-brandheader',
			} ).insertBefore( allPanels.first() );

			// add the margin to distinguish our panels from the other
			allPanels.last().addClass( 'groundwp-customizer-last-panel' );

			// description pop up setup
			$( `li[id^="${ customizerStartupData.elements.control.id }"] span.customize-control-description` ).each( function() {
				const current = $( this );
				current.attr( 'aria-popup', $( this ).text() );
				current.text( '?' );
				current.addClass( 'groundwp-customizer-description' );
				current.siblings( 'label' ).first().append( current );

				// popup setup
				const popup = $( '<div/>', {
					class: 'groundwp-customizer-popup-wrapper',
				} );

				popup.text( current.attr( 'aria-popup' ) );

				current.append( popup );

				current.hover( function() {
					const popupHeight = popup.outerHeight( true );
					const xMargin = 5;

					const { top, left } = $( this ).position();

					popup.css( 'left', left + $( this ).outerWidth() + xMargin );
					popup.css( 'top', top + ( popupHeight / 2 ) );
				} );
			} );

			// slider with value box
			$( `li[id^="${ customizerStartupData.elements.control.id }"] input[type="range"]` ).each( function() {
				const current = $( this );
				current.addClass( 'groundwp-customizer-control-slider' );

				// number box
				const numberBox = $( '<input/>', {
					type: 'number',
					class: 'groundwp-customizer-control-number-box',
					min: current.attr( 'min' ),
					max: current.attr( 'max' ),
					value: current.val(),
				} );

				function numberBoxHandler() {
					const targetVal = current.val();
					const numberboxVal = $( this ).val();
					if ( targetVal !== numberboxVal ) {
						current.val( numberboxVal );

						// trigger customizer event listeners
						current.change();
					}
				}

				// bind number box changes to slider
				numberBox.change( numberBoxHandler );
				numberBox.on( 'input', numberBoxHandler );

				// bind slider changes to number box
				current.on( 'input', ( function() {
					const targetVal = numberBox.val();
					const sliderVal = $( this ).val();
					if ( targetVal !== sliderVal ) {
						numberBox.val( sliderVal );
					}
				} ) );

				// wrapper
				$( '<div/>', {
					class: 'groundwp-customizer-control-slide-box',
				} ).appendTo( current.parent() ).append( current ).append( numberBox );
			} );

			//horizontal rulers
			$( `li[id^="${ customizerStartupData.elements.horizontal_ruler.id }"]` ).each( function() {
				$( this ).children( 'label' ).html( '<hr/>' );
			} );

			//bind switches
			function bindSwitches( masterName ) {
				if ( Object.prototype.hasOwnProperty.call( customizerStartupData.switches, masterName ) ) {
					const dependentControls = customizerStartupData.switches[ masterName ];

					wp.customize( masterName, function( setting ) {
						function controlHandler( control ) {
							function visibility() {
								if ( setting.get() ) {
									control.container.slideDown();
								} else {
									control.container.slideUp();
								}
							}

							visibility();
							setting.bind( visibility );
						}
						// eslint-disable-next-line array-callback-return
						dependentControls.map( ( d ) => {
							wp.customize.control( d, controlHandler );
						} );
					} );
				}
			}

			Object.keys( customizerStartupData.switches ).map( bindSwitches );
		} );
	}( jQuery ) );
}
