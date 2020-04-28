<?php 
 /**
     * Doctype Hook
     * 
     * @see preschool_and_kindergarten_doctype_cb
    */
add_action( 'preschool_and_kindergarten_doctype', 'preschool_and_kindergarten_doctype_cb');

 /**
     * Before wp_head
     * 
     * @see preschool_and_kindergarten_head
    */

add_action( 'preschool_and_kindergarten_before_wp_head', 'preschool_and_kindergarten_head');

 /**
     * Page Start
     * 
     * @see preschool_and_kindergarten_page_start
    */

add_action( 'preschool_and_kindergarten_before_page_start','preschool_and_kindergarten_page_start');

 /**
     * Preschool and Kindergarten Header
     * 
     * @see preschool_and_kindergarten_header_start - 10
     * @see preschool_and_kindergarten_header_top - 20
     * @see preschool_and_kindergarten_header_bottom - 30
     * @see preschool_and_kindergarten_header_end - 40
     *
    */
add_action( 'preschool_and_kindergarten_header', 'preschool_and_kindergarten_mobile_navigation_header', 5 );
add_action( 'preschool_and_kindergarten_header', 'preschool_and_kindergarten_header_start', 10 );
add_action( 'preschool_and_kindergarten_header', 'preschool_and_kindergarten_header_top', 20 );
add_action( 'preschool_and_kindergarten_header', 'preschool_and_kindergarten_header_bottom', 30 );
add_action( 'preschool_and_kindergarten_header', 'preschool_and_kindergarten_header_end', 40 );

 /**
     * Page Header
     * 
     * @see preschool_and_kindergarten_page_header - 10
    */

add_action( 'preschool_and_kindergarten_after_header', 'preschool_and_kindergarten_page_header', 10 );

 /**
     * Breadcrumbs
     * 
     * @see preschool_and_kindergarten_breadcrumbs_cb 
    */

add_action( 'preschool_and_kindergarten_breadcrumbs', 'preschool_and_kindergarten_breadcrumbs_cb' );

/** Content HOOKS goes here */

/**
 * Before Page entry content
 * 
 * @see preschool_and_kindergarten_page_content_image
*/
add_action( 'preschool_and_kindergarten_before_page_entry_content', 'preschool_and_kindergarten_page_content_image' );

/**
 * Before Post entry content
 * 
 * @see preschool_and_kindergarten_post_content_image - 10
 * @see preschool_and_kindergarten_post_entry_header  - 20
*/
add_action( 'preschool_and_kindergarten_before_post_entry_content', 'preschool_and_kindergarten_post_content_image', 10 );
add_action( 'preschool_and_kindergarten_before_post_entry_content', 'preschool_and_kindergarten_post_entry_header', 20 );

/**
 * After post content
 * 
 * @see preschool_and_kindergarten_post_author - 20
*/
add_action( 'preschool_and_kindergarten_after_post_content', 'preschool_and_kindergarten_post_author', 20 );

/**
    * Content End
    * 
    * @see preschool_and_kindergarten_content_end -20
*/

add_action( 'preschool_and_kindergarten_after_content', 'preschool_and_kindergarten_content_end', 20 );

 /**
     * Preschool and Kindergarten Footer
     * 
     * @see preschool_and_kindergarten_footer_start - 10
     * @see preschool_and_kindergarten_footer_top - 20
     * @see preschool_and_kindergarten_footer_bottom - 30
     * @see preschool_and_kindergarten_footer_end - 40
    */

add_action( 'preschool_and_kindergarten_footer', 'preschool_and_kindergarten_footer_start', 10 );
add_action( 'preschool_and_kindergarten_footer', 'preschool_and_kindergarten_footer_top', 20 );
add_action( 'preschool_and_kindergarten_footer', 'preschool_and_kindergarten_footer_bottom', 30 );
add_action( 'preschool_and_kindergarten_footer', 'preschool_and_kindergarten_footer_end', 40 );

 /**
     * page start
     * 
     * @see preschool_and_kindergarten_page_end - 20
    */

add_action( 'preschool_and_kindergarten_after_footer', 'preschool_and_kindergarten_page_end', 20 );
