<?php
/**
 * Template Name: Home Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Preschool_and_Kindergarten
 */

get_header();


    $preschool_and_kindergarten_page_sections = array( 'banners', 'about', 'lessons', 'services', 'promotional', 'program', 'testimonials', 'staff', 'news' );

    foreach( $preschool_and_kindergarten_page_sections as $section ){ 
        if( get_theme_mod( 'preschool_and_kindergarten_ed_' . $section . '_section' ) == 1 ){
            get_template_part( 'sections/' . esc_attr( $section ) );
        } 
    }
    

get_footer(); ?>