/* global wpRigScreenReaderText */
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

const KEYMAP = {
	TAB: 9,
};

if ( 'loading' === document.readyState ) {
	// The DOM has not yet been loaded.
	document.addEventListener( 'DOMContentLoaded', initNavigation );
} else {
	// The DOM has already been loaded.
	initNavigation();
}

// Initiate the menus when the DOM loads.
function initNavigation() {
	initNavToggleSubmenus();
	initNavToggleSmall();
	submenuAlignment();
}

/**
 * Initiate the script to process all
 * navigation menus with submenu toggle enabled.
 */
function initNavToggleSubmenus() {
	const navTOGGLE = document.querySelectorAll( '.nav--toggle-sub' );

	// No point if no navs.
	if ( ! navTOGGLE.length ) {
		return;
	}

	for ( let i = 0; i < navTOGGLE.length; i++ ) {
		initEachNavToggleSubmenu( navTOGGLE[ i ] );
	}
}

/**
 * Initiate the script to process submenu
 * navigation toggle for a specific navigation menu.
 * @param {Object} nav Navigation element.
 */
function initEachNavToggleSubmenu( nav ) {
	// Get the submenus.
	const SUBMENUS = nav.querySelectorAll( '.menu ul' );

	// No point if no submenus.
	if ( ! SUBMENUS.length ) {
		return;
	}

	const nonSubMenus = Array.from( nav.querySelectorAll( '.menu>li' ) ).filter( ( el ) => ! el.classList.contains( 'menu-item-has-children' ) );

	const submenuParents = nav.querySelectorAll( '.menu>li.menu-item-has-children' );

	// eslint-disable-next-line array-callback-return
	nonSubMenus.map( ( n ) => {
		n.querySelector( 'a' ).addEventListener( 'focus', ( ) => {
			submenuParents.forEach( ( s ) => {
				toggleSubMenu( s, false );
			} );
		} );
	} );

	// Create the dropdown button.
	const dropdownButton = getDropdownButton();

	for ( let i = 0; i < SUBMENUS.length; i++ ) {
		const parentMenuItem = SUBMENUS[ i ].parentNode;
		let dropdown = parentMenuItem.querySelector( '.dropdown' );

		// If no dropdown, create one.
		if ( ! dropdown ) {
			// Create dropdown.
			dropdown = document.createElement( 'span' );
			dropdown.classList.add( 'dropdown' );

			const dropdownSymbol = document.createElement( 'i' );
			dropdownSymbol.classList.add( 'dropdown-symbol' );
			dropdown.appendChild( dropdownSymbol );

			// Add before submenu.
			SUBMENUS[ i ].parentNode.insertBefore( dropdown, SUBMENUS[ i ] );
		}

		// Convert dropdown to button.
		const thisDropdownButton = dropdownButton.cloneNode( true );

		// Copy contents of dropdown into button.
		thisDropdownButton.innerHTML = dropdown.innerHTML;

		// Replace dropdown with toggle button.
		dropdown.parentNode.replaceChild( thisDropdownButton, dropdown );

		// Toggle the submenu when we click the dropdown button.
		thisDropdownButton.addEventListener( 'click', ( e ) => {
			toggleSubMenu( e.target.parentNode );
		} );

		// Toggle the submenu when we focus the dropdown button.
		thisDropdownButton.addEventListener( 'focus', ( e ) => {
			toggleSubMenu( e.target.parentNode );
		} );

		// Clean up the toggle if a mouse takes over from keyboard.
		parentMenuItem.addEventListener( 'mouseleave', ( e ) => {
			toggleSubMenu( e.target, false );
		} );

		// When we focus on a menu link, make sure all siblings are closed.
		parentMenuItem.querySelector( 'a' ).addEventListener( 'focus', ( e ) => {
			const parentMenuItemsToggled = e.target.parentNode.parentNode.querySelectorAll( 'li.menu-item--toggled-on' );
			for ( let j = 0; j < parentMenuItemsToggled.length; j++ ) {
				toggleSubMenu( parentMenuItemsToggled[ j ], false );
			}
			toggleSubMenu( e.target.parentNode );
		} );

		// Handle keyboard accessibility for traversing menu.
		SUBMENUS[ i ].addEventListener( 'keydown', ( e ) => {
			// These specific selectors help us only select items that are visible.
			const focusSelector = 'ul.toggle-show > li > a, ul.toggle-show > li > button';

			if ( KEYMAP.TAB === e.keyCode ) {
				if ( e.shiftKey ) {
					// Means we're tabbing out of the beginning of the submenu.
					if ( isfirstFocusableElement( e.target, document.activeElement, focusSelector ) ) {
						toggleSubMenu( e.target.parentNode, false );
					}
					// Means we're tabbing out of the end of the submenu.
				} else if ( islastFocusableElement( e.target, document.activeElement, focusSelector ) ) {
					toggleSubMenu( e.target.parentNode, false );
				}
			}
		} );

		SUBMENUS[ i ].parentNode.classList.add( 'menu-item--has-toggle' );
	}
}

/**
 * Initiate the script to process all
 * navigation menus with small toggle enabled.
 */
function initNavToggleSmall() {
	const navTOGGLE = document.querySelectorAll( '.nav--toggle-small' );

	// No point if no navs.
	if ( ! navTOGGLE.length ) {
		return;
	}

	for ( let i = 0; i < navTOGGLE.length; i++ ) {
		initEachNavToggleSmall( navTOGGLE[ i ] );
	}
}

/**
 * Stops the dropdown menu from overflowing the body width and moves the dropdown towards the left.
 */
function submenuAlignment() {
	// Select all menu items that have children (even the ones within the dropdown).
	const submenuParents = document.querySelectorAll( '.menu li.menu-item-has-children' );

	// No point if no submenus.
	if ( ! submenuParents.length ) {
		return;
	}

	// Get the document width.
	const docWidth = document.querySelector( 'body' ).offsetWidth;

	Array.from( submenuParents ).forEach( ( submenuParent ) => {
		// Set mouseenter eventlistener on all the menu-item with children.
		submenuParent.addEventListener( 'mouseenter', ( e ) => {
			const submenu = e.target.querySelector( 'ul.sub-menu' );

			const submenuLeftEdge = submenu.getBoundingClientRect().left;

			// Checks whether the element is within the viewport. Boolean.
			const isVisible = ( submenuLeftEdge + submenu.offsetWidth <= docWidth );

			// If it overflows, add `submenu-left` class to show it on the left side instead of right.
			if ( ! isVisible ) {
				submenu.classList.add( 'submenu-left' );
			}
		}, false );
	} );
}

/**
 * Initiate the script to process small
 * navigation toggle for a specific navigation menu.
 * @param {Object} nav Navigation element.
 */
function initEachNavToggleSmall( nav ) {
	const menuTOGGLE = nav.querySelector( '.menu-toggle' );

	// Return early if MENUTOGGLE is missing.
	if ( ! menuTOGGLE ) {
		return;
	}

	// Add an initial values for the attribute.
	menuTOGGLE.setAttribute( 'aria-expanded', 'false' );

	// all items inside hamburger menu
	const smallMenuItems = nav.querySelectorAll( '.menu>li a' );
	// first item that will be used to check shift-tabbing out of menu
	const firstSmall = smallMenuItems[ 0 ];
	// last item that will be used to check tabbing out of menu
	const lastSmall = smallMenuItems[ smallMenuItems.length - 1 ];

	/**
     * change tab focus functionality of elements
     * this function also makes sure on focusable event to focus the first element automatically
     *
     * @param {NodeList} allItems array of elements that will be affected
     * @param {boolean} focus give tab focus functionality or not
     */
	function focusElements( allItems, focus ) {
		// eslint-disable-next-line array-callback-return
		Array.from( smallMenuItems ).map( ( el, index ) => {
			el.setAttribute( 'tabIndex', focus ? '1' : '-1' );
			if ( focus && index === 0 ) {
				el.focus();
			}
		} );
	}

	/**
     * is hamburger menu visible
     * @return {boolean} visible or not
     */
	function isHamburgerMenuVisible() {
		return getComputedStyle( menuTOGGLE ).display !== 'none';
	}

	// listener for page resize events
	window.addEventListener( 'resize', ( ) => {
		focusElements( smallMenuItems, isHamburgerMenuVisible() === false );
	} );

	// startup focus check
	if ( isHamburgerMenuVisible() ) {
		focusElements( smallMenuItems, false );
	}

	/**
     * toggle mobile device hamburger menu
     *
     * @param {any} e object that implements event listener interface
     */
	function hamburgerMenuToggle( e ) {
		nav.classList.toggle( 'nav--toggled-on' );

		const ariaExpanded = e.target.getAttribute( 'aria-expanded' );
		e.target.setAttribute( 'aria-expanded', 'false' === ariaExpanded ? 'true' : 'false' );

		// change focus attributes of menu elements depends on visibility of the nav menu
		focusElements( smallMenuItems, ( nav.classList.contains( 'nav--toggled-on' ) ) );
	}

	menuTOGGLE.addEventListener( 'click', hamburgerMenuToggle, false );

	// close hamburger menu at shift-tabbing first element
	firstSmall.addEventListener( 'keydown', ( e ) => {
		if ( isHamburgerMenuVisible() && e.keyCode === KEYMAP.TAB && e.shiftKey ) {
			hamburgerMenuToggle( { target: menuTOGGLE } );
		}
	} );

	// close hamburger menu at tabbing last element
	lastSmall.addEventListener( 'keydown', ( e ) => {
		if ( isHamburgerMenuVisible() && e.keyCode === KEYMAP.TAB ) {
			hamburgerMenuToggle( { target: menuTOGGLE } );
		}
	} );
}

/**
 * Toggle submenus open and closed, and tell screen readers what's going on.
 * @param {Object} parentMenuItem Parent menu element.
 * @param {boolean} forceToggle Force the menu toggle.
 * @return {void}
 */
function toggleSubMenu( parentMenuItem, forceToggle ) {
	const toggleButton = parentMenuItem.querySelector( '.dropdown-toggle' ),
		subMenu = parentMenuItem.querySelector( 'ul' );
	let parentMenuItemToggled = parentMenuItem.classList.contains( 'menu-item--toggled-on' );

	// Will be true if we want to force the toggle on, false if force toggle close.
	if ( undefined !== forceToggle && 'boolean' === ( typeof forceToggle ) ) {
		parentMenuItemToggled = ! forceToggle;
	}

	// Toggle aria-expanded status.
	toggleButton.setAttribute( 'aria-expanded', ( ! parentMenuItemToggled ).toString() );

	/*
     * Steps to handle during toggle:
     * - Let the parent menu item know we're toggled on/off.
     * - Toggle the ARIA label to let screen readers know will expand or collapse.
     */
	if ( parentMenuItemToggled ) {
		// Toggle "off" the submenu.
		parentMenuItem.classList.remove( 'menu-item--toggled-on' );
		subMenu.classList.remove( 'toggle-show' );
		toggleButton.setAttribute( 'aria-label', wpRigScreenReaderText.expand );

		// Make sure all children are closed.
		const subMenuItemsToggled = parentMenuItem.querySelectorAll( '.menu-item--toggled-on' );
		for ( let i = 0; i < subMenuItemsToggled.length; i++ ) {
			toggleSubMenu( subMenuItemsToggled[ i ], false );
		}
	} else {
		// Make sure siblings are closed.
		const parentMenuItemsToggled = parentMenuItem.parentNode.querySelectorAll( 'li.menu-item--toggled-on' );
		for ( let i = 0; i < parentMenuItemsToggled.length; i++ ) {
			toggleSubMenu( parentMenuItemsToggled[ i ], false );
		}

		// Toggle "on" the submenu.
		parentMenuItem.classList.add( 'menu-item--toggled-on' );
		subMenu.classList.add( 'toggle-show' );
		toggleButton.setAttribute( 'aria-label', wpRigScreenReaderText.collapse );
	}
}

/**
 * Returns the dropdown button
 * element needed for the menu.
 * @return {Object} drop-down button element
 */
function getDropdownButton() {
	const dropdownButton = document.createElement( 'button' );
	dropdownButton.classList.add( 'dropdown-toggle' );
	dropdownButton.setAttribute( 'aria-expanded', 'false' );
	dropdownButton.setAttribute( 'aria-label', wpRigScreenReaderText.expand );

	//makes button unfocusable
	dropdownButton.setAttribute( 'tabIndex', '-1' );
	return dropdownButton;
}

/**
 * Returns true if element is the
 * first focusable element in the container.
 * @param {Object} container
 * @param {Object} element
 * @param {string} focusSelector
 * @return {boolean} whether or not the element is the first focusable element in the container
 */
function isfirstFocusableElement( container, element, focusSelector ) {
	const focusableElements = container.querySelectorAll( focusSelector );
	if ( 0 < focusableElements.length ) {
		return element === focusableElements[ 0 ];
	}
	return false;
}

/**
 * Returns true if element is the
 * last focusable element in the container.
 * @param {Object} container
 * @param {Object} element
 * @param {string} focusSelector
 * @return {boolean} whether or not the element is the last focusable element in the container
 */
function islastFocusableElement( container, element, focusSelector ) {
	const focusableElements = container.querySelectorAll( focusSelector );
	if ( 0 < focusableElements.length ) {
		return element === focusableElements[ focusableElements.length - 1 ];
	}
	return false;
}
