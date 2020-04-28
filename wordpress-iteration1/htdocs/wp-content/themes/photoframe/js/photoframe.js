jQuery(document).ready(function($) { 

	/**
	* Document ready (jQuery)
	*/
	$(function () {

		/**
		* Activate superfish menu.
		*/
		$('.sf-menu').superfish({
			'speed': 'fast',
			'delay' : 0,
			'animation': {
				'height': 'show'
			}
		});

	});

	/**
	* SlickNav
	*/

	$('#menu-main-slick').slicknav({
		prependTo:'.site-navbar-header',
		label: photoframeStrings.slicknav_menu_home,
		allowParentLinks: true
	});

	jQuery(".site-hero-slideshow").flexslider({
		selector: ".site-hero-slideshow-list .site-hero-slideshow-item",
		animation: "slide",
		animationLoop: true,
		initDelay: 500,
		smoothHeight: false,
		slideshow: true,
		slideshowSpeed: 5000,
		pauseOnAction: true,
		pauseOnHover: false,
		controlNav: false,
		directionNav: true,
		useCSS: true,
		touch: true,
		animationSpeed: 800,
		allowOneSlide: false,
		rtl: false,
		reverse: false,
		start: function () {
			BackgroundCheck.init({
				targets: '#site-logo',
				changeParent: true,
				threshold: 70,
				minOverlap: 30,
				images: '.site-hero-slideshow-item'
			});
		},
		after: function () {
			BackgroundCheck.refresh();
		}
	});

	function backgroundCheckWithSlideshow() {
		BackgroundCheck.init({
			targets: '#site-logo',
			changeParent: true,
			threshold: 70,
			minOverlap: 30,
			images: '#site-hero-image .site-hero-slideshow-item'
		});
	}

	if ( document.getElementById('site-hero-image') ) {
		backgroundCheckWithSlideshow();
	}

	function photoframeStickyHeader() {
		// When the user scrolls the page, execute scrollclasses 
		window.onscroll = function() { scrollclasses() };

		// Get the masthead (header block)
		var masthead = document.getElementById("site-masthead");
		var primarymenu = document.getElementById("site-primary-nav");
		var primarycontent = document.getElementById("site-main");

		// Get the offset position of the masthead
		var sticky = primarymenu.offsetTop + 90;

		// Add the sticky class to the masthead when you reach its scroll position. Remove "sticky" when you leave the scroll position
		function scrollclasses() {
			if (window.pageYOffset > sticky) {
				masthead.classList.add("site-masthead-fixed");
				primarycontent.classList.add("site-main-displaced");
			} else {
				masthead.classList.remove("site-masthead-fixed");
				primarycontent.classList.remove("site-main-displaced");
			}
		}
	}

	photoframeStickyHeader();

});