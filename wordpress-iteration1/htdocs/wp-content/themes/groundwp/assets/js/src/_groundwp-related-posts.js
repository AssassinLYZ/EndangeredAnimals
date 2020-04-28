/**
 * File _groundwp-related-posts.js.
 *
 * Related posts script
 *
 * Contains Vue components and logic to lazy-fetch related posts on the single post display page
 */
/**
 * External dependencies
 */
import Vue from 'vue';
/**
 * Internal dependencies
 */
import intersectionObserver from './_groundwp-intersection-observer-component';
import consoleLogger from './_groundwp-console-logger';

export default function relatedPosts() {
	const rpd = { ...relatedPostsData };

	relatedPostsData = undefined;

	// const intersectionObserver = groundwpIntersectionObserverComponent;

	Vue.component( 'related-post', {
		props: [ 'link', 'id', 'date', 'title', 'excerpt' ],
		computed: {
			parsedId() {
				return `post-${ this.id }`;
			},
			displayDate() {
				return new Intl.DateTimeFormat( 'default', {
					month: 'long',
					day: 'numeric',
					year: 'numeric',
				} ).format( new Date( this.date ) );
			},
		},
		methods: {
			followLink() {
				window.location.href = this.link;
			},
		},
	} )
	;

	Vue.component( 'related-posts-component', {
		props: [ 'dataObj' ],
		components: { intersectionObserver },
		data() {
			return {
				posts: [],
				masterVisibility: true,
				fetching: false,
				fetchedOnce: false,
			};
		},
		mounted() {
			// this.getRelatedPosts();
		},
		computed: {
			isPostsVisible() {
				return this.posts.length > 0;
			},
		},
		methods: {
			getRelatedPosts() {
				const urlObject = new URL( this.dataObj.ajaxUrl );
				const { numberOfPosts } = this.dataObj.settings;

				// number of posts to fetch
				urlObject.searchParams.append( 'per_page', numberOfPosts );
				// exclude current post from the results
				urlObject.searchParams.append( 'exclude', this.dataObj.currentPostId );
				// categories to check
				urlObject.searchParams.append( 'categories', this.dataObj.category.join( ',' ) );
				// only get the necessary fields for the components (Principle of the least privileged)
				urlObject.searchParams.append( '_fields', this.dataObj.fields.join( ',' ) );

				this.setFetchStatus( true );

				fetch( urlObject )
					.then( ( r ) => {
						if ( ! r.ok ) {
							throw Error( r.statusText );
						}
						return r.json();
					} )
					.then( ( resp ) => {
						this.posts = resp;
					} ).catch( ( e ) => {
						// eslint-disable-next-line no-console
						console.error( 'an error occurred : ' + e );
					} ).finally( () => {
						this.setFetchStatus( false );
						this.setMasterVisibility();
					} );
			},
			setFetchStatus( status ) {
				this.fetching = status;
			},
			setMasterVisibility() {
				this.masterVisibility = this.posts.length > 0;
			},
			intersect() {
				if ( ! this.fetchedOnce ) {
					this.fetchedOnce = true;
					consoleLogger.log( 'fetching related posts.' );
					this.getRelatedPosts();
				}
			},
		},
	} );

	new Vue( {
		data: {
			dataObj: rpd,
		},
	} ).$mount( '#related-posts' );
}

