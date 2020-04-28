/**
 * File _groundwp-selection-popup.js
 *
 * Selection pop up component
 */
/**
 * Internal dependencies
 */
import consoleLogger from './_groundwp-console-logger';

/**
 *
 * @param {string} elementQuery - query string for parent element to be used for selections
 * @param {Array} items - array of key value pairs to be used for inner button functionality
 * @param  {string} copy - translated copy text
 * @constructor
 */
function SelectionPop( elementQuery, items, copy ) {
	this.element = document.querySelector( elementQuery );
	this.element.addEventListener( 'mouseup', this.handleMouseUp.bind( this ) );
	this.lastSelection = '';

	const popupString = '<div class="groundwp-selection-popup"><div class="groundwp-selection-popup-arrow"></div></div>';
	const popupFragment = createFragment( popupString );
	this.element.appendChild( popupFragment );

	this.popupElement = document.querySelector( '.groundwp-selection-popup' );

	items.forEach( ( i ) => {
		insertFunctionElement( i.title, () => shareOn( i.url, this.lastSelection ), this.popupElement );
	} );

	// copy element
	insertFunctionElement( copy, () => {
		documentCopy( this.lastSelection );
		this.popupElement.classList.remove( 'groundwp-selection-popup-visible' );
	}, this.popupElement );

	// document wide event listener for mouseup to hide selection-popup at off click and escape key press

	document.addEventListener( 'mouseup', () => {
		const selection = window.getSelection();

		if ( selection.toString().length <= 0 || selection.toString() === '' ) {
			this.hidePopup();
		}
	} );

	document.addEventListener( 'keyup', ( e ) => {
		if ( e.which === 27 ) {
			this.hidePopup();
		}
	} );
}

/**
 * Create a document fragment from string
 * @param {string} element string representation of the element
 * @return {DocumentFragment} created fragment
 */
function createFragment( element ) {
	const range = document.createRange();
	range.setStart( document.body, 0 );
	return range.createContextualFragment( element );
}

/**
 * share selected text on different REST APIs
 *
 * @param {string} baseUrl - base url
 * @param {string} data - data to be shared
 */
function shareOn( baseUrl, data ) {
	window.location.href = baseUrl + encodeURIComponent( data );
}

/**
 * copy selected text to clipboard
 *
 * @param {string} val - value to be copied
 */
function documentCopy( val ) {
	const tempCopyFragment = createFragment( '<input type="text" class="groundwp-selection-temp-input">' );
	const firstChild = tempCopyFragment.children[ 0 ];
	firstChild.value = val;

	document.body.appendChild( tempCopyFragment );

	firstChild.select();
	document.execCommand( 'copy' );
	consoleLogger.log( 'selection copied' );
	document.body.removeChild( firstChild );
}

/**
 * create and insert functional elements inside popup element
 *
 * @param {string} title - link title
 * @param {function} func - onClick function to be fired
 * @param {HTMLElement} parent - parent element
 */
function insertFunctionElement( title, func, parent ) {
	const template = `<div class="groundwp-selection-element" >${ title.trim() }</div>`;
	const tempFragment = createFragment( template );
	tempFragment.children[ 0 ].addEventListener( 'click', func );

	parent.appendChild( tempFragment );
}

/**
 * change value into pixel representation
 *
 * @param {number|string} val - base value
 * @return {string} - pixel representation
 */
function toPx( val ) {
	return val + 'px';
}

/**
 * hide popup element
 */
function hidePopup() {
	this.popupElement.classList.remove( 'groundwp-selection-popup-visible' );
}

/**
 * mouse-up event callback
 */
function handleMouseUp() {
	const selection = window.getSelection();

	if ( selection.toString().length <= 0 || selection.toString() === '' ) {
		this.hidePopup();
		return;
	}

	this.lastSelection = selection.toString();

	this.popupElement.classList.add( 'groundwp-selection-popup-visible' );

	const range = selection.getRangeAt( 0 );
	const sRect = range.getBoundingClientRect();
	const { left, width, top } = sRect;

	const popUpWidth = this.popupElement.offsetWidth;
	const popUpHeight = this.popupElement.offsetHeight;
	const yMargin = 10;

	const middleX = left + ( width / 2 ) - ( popUpWidth / 2 );
	const calculatedTop = top - popUpHeight - yMargin + window.scrollY;

	this.popupElement.style.left = toPx( middleX );
	this.popupElement.style.top = toPx( calculatedTop );
}

// prototypes
SelectionPop.prototype.handleMouseUp = handleMouseUp;
SelectionPop.prototype.hidePopup = hidePopup;

export default SelectionPop;

