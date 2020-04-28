/**
 * File _groundwp-infinite-scroll.js
 *
 * Infinite scrolling for post listings
 */

/**
 * External dependencies
 */
import Vue from 'vue';
/**
 * Internal dependencies
 */
import intersectionObserver from './_groundwp-intersection-observer-component';

export default function infiniteScroll() {
	const infiniteScrollData = { ...groundwpInfiniteScrollData };

	groundwpInfiniteScrollData = undefined;


	// const intersectionObserver = groundwpIntersectionObserverComponent;

	Vue.component( 'groundwp-infinite-scroll-post', {
		props: [ 'date' ],
		computed: {
			localTime() {
				return new Intl.DateTimeFormat( 'default', {
					month: 'long', day: 'numeric', year: 'numeric',
				} ).format( new Date( this.date ) );
			},
		},
	} );

	Vue.component( 'groundwp-infinite-scroll-posts-container', {
		props: [ 'url', 'query' ],
		components: { intersectionObserver },
		data() {
			return {
				message: 'infinite scroll landmark',
				fetching: false,
				disable: false,
				posts: [],
			};
		},
		beforeMount() {
			const queryConversionTable = {
				paged: 'page',
				cat: 'categories',
			};

			this.urlObject = new URL( this.url );

			if ( this.query !== '' ) {
				let decoded = decodeURIComponent( this.query );
				Object.keys( queryConversionTable ).map( ( key ) => {
					if ( Object.prototype.hasOwnProperty.call( queryConversionTable, key ) ) {
						decoded = decoded.replace( `${ key }=`, `${ queryConversionTable[ key ] }=` );
					}
				} );

				decoded.split( '&' ).map( ( q ) => {
					const [ key, value ] = q.split( '=' );
					this.urlObject.searchParams.set( key, value );
					return true;
				} );

				const dateQuery = this.urlObject.searchParams.get( 'm' );

				if ( dateQuery ) {
					const parsedDateString = `${ dateQuery.slice( 0, 4 ) }-${ dateQuery.slice( -2 ) }`;

					const currentQuery = new Date( parsedDateString );
					const afterQuery = new Date( parsedDateString ).setDate( currentQuery.getDate() - 1 );
					const beforeQuery = new Date( parsedDateString ).setMonth( currentQuery.getMonth() + 1 );

					this.urlObject.searchParams.set( 'after', new Date( afterQuery ).toISOString() );
					this.urlObject.searchParams.set( 'before', new Date( beforeQuery ).toISOString() );

					this.urlObject.searchParams.delete( 'm' );
				}
			}
		},
		methods: {
			nextPage() {
				const currentPage = this.urlObject.searchParams.get( 'page' ) || 1;

				this.urlObject.searchParams.set( 'page', ( Number.parseInt( currentPage, 10 ) + 1 ).toString() );
				return this.urlObject.href;
			},
			fetchPosts() {
				if ( this.fetching || this.disable ) {
					return;
				}
				this.fetching = true;
				fetch( this.nextPage() ).then( ( resp ) => {
					if ( ! resp.ok ) {
						throw Error( 'no more posts to fetch' );
					}
					return resp.json();
				} ).then( ( r ) => {
					if ( r.length === 0 ) {
						this.disable = true;
						throw Error( 'no more posts to fetch' );
					}
					this.posts.push( ...r );
					this.fetching = false;
				} ).catch( ( e ) => {
					// eslint-disable-next-line no-console
					this.disable = true;
					// eslint-disable-next-line no-console
					console.error( e.message );
				} ).finally( () => {
					this.fetching = false;
				} );
			},
		},
	} );

	new Vue( {
		data: infiniteScrollData,
	} ).$mount( '#groundwp-infinite-scroll' );
}

