/**
 * File _groundwp-scroll-to-top.js.
 *
 * Scroll-to-top component script
 *
 * Contains necessary logic for scrolling the page to the top
 */

export default function scrollToTop( ) {
	const scrollToTopData = { ...groundwpScrollToTopData };

	groundwpScrollToTopData = undefined;

	const componentId = '#' + scrollToTopData.elementId;
	const minScroll = 100;

	const component = document.querySelector( componentId );

	component.addEventListener( 'click', () => {
		window.scrollTo( 0, 0 );
	} );

	/**
     * function responsible for main component logic
     */
	function scrollHandler() {
		const current = window.scrollY;
		if ( current > minScroll ) {
			component.classList.add( 'groundwp_scroll_to_top_visible' );
		} else {
			component.classList.remove( 'groundwp_scroll_to_top_visible' );
		}
	}

	scrollHandler();

	window.addEventListener( 'scroll', scrollHandler );
}
