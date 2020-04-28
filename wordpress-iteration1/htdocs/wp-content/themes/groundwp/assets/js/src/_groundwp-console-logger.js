/**
 * File _groundwp-console-logger.js
 *
 * Customized console logging utility
 */
( function consoleLogger( global, factory ) {
	// eslint-disable-next-line no-unused-expressions
	( typeof exports === 'object' && typeof module !== 'undefined' ) ? ( module.exports = factory() ) : ( global = global || self, global.consoleLogger = factory() );
}( this, () => {
	const parentName = 'groundwp';
	const formatStyle = 'background: blue;padding:5px;color:white;font-weight:bold;font-size:110%;border-radius:5px';
	const defaultStyle = 'background: inherit;padding:0px;color:inherit;font-weight:inherit;font-size:inherit;border-radius:inherit';

	/**
     * format supplied message
     *
     * @param {string} message - message to be formatted
     * @return {string} formatted message
     */
	function format( message ) {
		return `%c[${ parentName }]%c: ${ message }`;
	}

	/**
	 * log your message
	 *
	 * @param {string} message - message to be logged
	 */
	function log( message ) {
		// eslint-disable-next-line no-console
		console.log( format( message ), formatStyle, defaultStyle );
	}

	return { log };
} ) );
