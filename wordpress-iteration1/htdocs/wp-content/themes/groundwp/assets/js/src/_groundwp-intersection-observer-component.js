/**
 * File _groundwp-intersection-observer-component.js
 *
 * Intersection observer component
 */
const intersectionObserverComponent = {
	name: 'intersection-observer',
	props: [ 'element' ],
	data() {
		return {
			offsetTop: 0,
		};
	},
	mounted() {
		this.handleScroll();
		window.addEventListener( 'scroll', this.handleScroll.bind( this ), { passive: true } );
	},
	methods: {
		handleScroll() {
			const targetElement = document.querySelector( this.element );
			this.offsetTop = targetElement.offsetTop;
			const currentScrollY = window.scrollY + window.innerHeight;
			if ( currentScrollY > this.offsetTop ) {
				this.$emit( 'intersect' );
			}
		},
	},
};

export default intersectionObserverComponent;
