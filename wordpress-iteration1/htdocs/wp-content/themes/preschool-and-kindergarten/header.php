<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Preschool_and_Kindergarten
 **
 *
     * Doctype Hook
     * 
     * @hooked preschool_and_kindergarten_doctype_cb
    */
    do_action( 'preschool_and_kindergarten_doctype' );
?>

<head itemscope itemtype="http://schema.org/WebSite">

<?php 
    /**
     * Before wp_head
     * 
     * @hooked preschool_and_kindergarten_head
    */
    do_action( 'preschool_and_kindergarten_before_wp_head' );

    wp_head(); 
?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
		
    <?php
    wp_body_open();
    
    /**
    * @hooked preschool_and_kindergarten_page_start 
    */
    do_action( 'preschool_and_kindergarten_before_page_start' ); 
    
    /**
    * preschool_and_kindergarten Header Top
    * 
    * @hooked preschool_and_kindergarten_mobile_navigation_header  - 5
    * @hooked preschool_and_kindergarten_header_start  - 10
    * @hooked preschool_and_kindergarten_header_top    - 20
    * @hooked preschool_and_kindergarten_header_bottom - 30
    * @hooked preschool_and_kindergarten_header_end    - 40    
    */	    
    
    do_action( 'preschool_and_kindergarten_header' ); 
    
    /**
    * After Header
    * 
    * @hooked preschool_and_kindergarten_page_header
    */
    do_action( 'preschool_and_kindergarten_after_header' );