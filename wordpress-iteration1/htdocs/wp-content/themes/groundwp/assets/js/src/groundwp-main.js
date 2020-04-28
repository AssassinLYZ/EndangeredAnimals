/**
 * File groundwp-main.js
 *
 * Main js file for theme frontend
 */

/**
 * Internal dependencies
 */
import scrollToTop from './_groundwp-scroll-to-top';
import infiniteScroll from './_groundwp-infinite-scroll';
import relatedPosts from './_groundwp-related-posts';
import singleSelectionPopup from './_groundwp-single-select-popup';
import stickyHeader from './_groundwp-sticky-header';

/**
 * External dependencies
 */

const { scripts } = groundwpMainData;

const definedFunctions = {
	scrollToTop,
	infiniteScroll,
	relatedPosts,
	singleSelectionPopup,
	stickyHeader,
};

//eslint-disable-next-line array-callback-return
scripts.map( ( s ) => {
	// eslint-disable-next-line no-unused-expressions
	definedFunctions[ s ] ? definedFunctions[ s ]() : '';
} );
