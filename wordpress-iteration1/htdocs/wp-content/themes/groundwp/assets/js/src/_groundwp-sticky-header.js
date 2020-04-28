// TODO [erdembircan] rewrite in vanilla js
/**
 * File groundwp-sticky-header.js.
 *
 * Sticky header component script
 */
export default function stickyHeader() {
	const headerId = '#masthead';
	const contentContainer = '.content-container';
	const mainHeader = document.querySelector( headerId );
	const mainContentContainer = document.querySelector( contentContainer );
	const headerHeight = mainHeader.offsetHeight;
	const headerTop = mainHeader.scrollTop;

	const headerBottom = headerTop + headerHeight;
	const stickyHeaderClass = 'sticky-header';
	const stickyHideClass = 'sticky-header-hide';

	function handleScroll() {
		let lastPosition = window.pageYOffset;

		function scrollEventHandler() {
			const currentScroll = window.pageYOffset;
			const isGoingDown = currentScroll > lastPosition;
			lastPosition = currentScroll;

			if ( currentScroll > headerBottom ) {
				if ( isGoingDown ) {
					// going down
					mainHeader.classList.add( stickyHideClass );
				} else {
					// coming up
					mainHeader.classList.remove( stickyHideClass );
				}
				mainHeader.classList.add( stickyHeaderClass );
				mainContentContainer.style.paddingTop = headerHeight + 'px';
			} else {
				mainHeader.classList.remove( stickyHeaderClass );
				mainContentContainer.style.paddingTop = 0;
			}
		}

		return scrollEventHandler;
	}

	handleScroll()();

	window.addEventListener( 'scroll', handleScroll() );
}
