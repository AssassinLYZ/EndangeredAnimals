<?php
/**
 * Plugin constants.
 *
 * @package Toolset Views
 * @since 3.0
 */

/**
 * Embedded directory.
 */
define( 'WPV_PATH_EMBEDDED', WPV_PATH . '/embedded' );

/**
 * Plugin folder name.
 */
define( 'WPV_FOLDER', basename( WPV_PATH ) );

/**
 * General URLs: root URL, embedded URL, frontend embedded URL.
 */
if (
	(
		defined( 'FORCE_SSL_ADMIN' )
		&& FORCE_SSL_ADMIN
	) || is_ssl()
) {
	define( 'WPV_URL', rtrim( str_replace( 'http://', 'https://', plugins_url() ), '/' ) . '/' . WPV_FOLDER );
} else {
	define( 'WPV_URL', plugins_url() . '/' . WPV_FOLDER );
}

define( 'WPV_URL_EMBEDDED', WPV_URL . '/embedded' );
if ( is_ssl() ) {
	define( 'WPV_URL_EMBEDDED_FRONTEND', WPV_URL_EMBEDDED );
} else {
	define( 'WPV_URL_EMBEDDED_FRONTEND', str_replace( 'https://', 'http://', WPV_URL_EMBEDDED ) );
}

/**
 * Views Lite.
 *
 * Note that the value if this constant is changed during the Views Lite build:
 * any change on WPV_LITE needs to be synced in ./make/build_lite.sh
 */
define( 'WPV_LITE', false );
define( 'WPV_LITE_UPGRADE_LINK', 'https://wpml.org/documentation/developing-custom-multilingual-sites/types-and-views-lite/' );

/**
 * Toolset Blocks
 */
if( ! defined( 'WPV_FLAVOUR' ) ) {
	define( 'WPV_FLAVOUR', 'blocks' );
}

/**
 * Space char used in documentation.
 */
if ( ! defined( 'WPV_MESSAGE_SPACE_CHAR' ) ) {
	define( 'WPV_MESSAGE_SPACE_CHAR', '&nbsp;' );
}

/**
 * Listing screens default items per page.
 */
define( 'WPV_ITEMS_PER_PAGE', 20 );

/**
 * Documentation links.
 */
if ( ! defined( 'WPV_LINK_CREATE_PAGINATED_LISTINGS' ) ) {
	define( 'WPV_LINK_CREATE_PAGINATED_LISTINGS', 'https://toolset.com/documentation/user-guides/views-pagination/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-create-paginated-listing-helpbox&utm_term=Creating paginated listings with Views' );
}
if ( ! defined( 'WPV_LINK_CREATE_SLIDERS' ) ) {
	define( 'WPV_LINK_CREATE_SLIDERS', 'https://toolset.com/documentation/user-guides/creating-sliders-with-types-and-views/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-create-sliders-helpbox&utm_term=Creating sliders with Views' );
}
if ( ! defined( 'WPV_LINK_CREATE_PARAMETRIC_SEARCH' ) ) {
	define( 'WPV_LINK_CREATE_PARAMETRIC_SEARCH', 'https://toolset.com/documentation/user-guides/front-page-filters/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-create-custom-search-helpbox&utm_term=Creating custom searches with Views' );
}
if ( ! defined( 'WPV_LINK_DESIGN_SLIDER_TRANSITIONS' ) ) {
	define( 'WPV_LINK_DESIGN_SLIDER_TRANSITIONS', 'https://toolset.com/documentation/user-guides/creating-sliders-with-types-and-views/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-create-sliders-transitions-helpbox&utm_term=Creating sliders with Views' );
}
if ( ! defined( 'WPV_LINK_LOOP_DOCUMENTATION' ) ) {
	define( 'WPV_LINK_LOOP_DOCUMENTATION', 'https://toolset.com/documentation/user-guides/digging-into-view-outputs/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-edit-layouts-helpbox&utm_term=Learn more by reading the Views Loop documentation.' );
}
if ( ! defined( 'WPV_LINK_CONTENT_TEMPLATE_DOCUMENTATION' ) ) {
	define( 'WPV_LINK_CONTENT_TEMPLATE_DOCUMENTATION', 'https://toolset.com/documentation/user-guides/view-templates/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-content-template-page&utm_term=Content Template documentation#tutorial' );
}
if ( ! defined( 'WPV_LINK_WORDPRESS_ARCHIVE_DOCUMENTATION' ) ) {
	define( 'WPV_LINK_WORDPRESS_ARCHIVE_DOCUMENTATION', 'https://toolset.com/documentation/user-guides/normal-vs-archive-views/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-wordpress-archive-page&utm_term=WordPress Archive documentation' );
}
if ( ! defined( 'WPV_LINK_FRAMEWORK_INTEGRATION_DOCUMENTATION' ) ) {
	define( 'WPV_LINK_FRAMEWORK_INTEGRATION_DOCUMENTATION', 'https://toolset.com/documentation/user-guides/theme-frameworks-integration/?utm_source=viewsplugin&utm_campaign=views&utm_medium=theme-framework-integration-page&utm_term=theme framework integration documentation page' );
}

define( 'WPV_SUPPORT_LINK', 'https://toolset.com/forums/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view&utm_term=support forum' );

define( 'WPV_FILTER_BY_TAXONOMY_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-by-taxonomy/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-category-filter&utm_term=Learn about filtering by taxonomy' );
define( 'WPV_FILTER_BY_CUSTOM_FIELD_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-by-custom-fields/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-custom-fields-filter&utm_term=Learn about filtering by custom fields' );
define( 'WPV_ADD_FILTER_CONTROLS_LINK', 'https://toolset.com/documentation/user-guides/front-page-filters/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-filter-controls&utm_term=filter controls' );
define( 'WPV_FILTER_BY_AUTHOR_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-query-by-author/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-author-filter&utm_term=Learn about filtering by Post Author' );
define( 'WPV_FILTER_BY_POST_PARENT_LINK', 'https://toolset.com/documentation/user-guides/displaying-brother-pages/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-post-parent-filter&utm_term=Learn about displaying brother pages using this filter' );
define( 'WPV_FILTER_BY_SPECIFIC_TEXT_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-for-a-specific-text-string-search/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-search-text-filter&utm_term=Learn about filtering for a specific text string' );
define( 'WPV_FILTER_BY_POST_ID_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-query-by-post-id/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-post-ids-filter&utm_term=Learn about filtering by Post ID' );
define( 'WPV_FILTER_BY_USERS_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-query-by-author/?utm_source=viewsplugin&utm_campaign=views&utm_medium=undefined&utm_term=undefined' );
define( 'WPV_FILTER_BY_USER_FIELDS_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-by-custom-fields/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-users-fields-filter&utm_term=Learn about filtering by user fields' );
define( 'WPV_FILTER_BY_POST_DATE_LINK', 'https://toolset.com/documentation/user-guides/filtering-views-query-by-date/?utm_source=viewsplugin&utm_campaign=views&utm_medium=edit-view-post-date-filter&utm_term=Learn about filtering by Post Date' );
