<?php
/**
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Preschool and Kindergarten 
 */

    $ed_section = preschool_and_kindergarten_ed_section();
    $preschool_and_kindergarten_page_sections = array( 'banners', 'about', 'lessons', 'services', 'promotional', 'program', 'testimonials', 'staff', 'news' );    

get_header(); 
    if ( 'posts' == get_option( 'show_on_front' ) ) {

        include( get_home_template() );

    }elseif( $ed_section ){ 

        foreach( $preschool_and_kindergarten_page_sections as $section ){ 
            if( get_theme_mod( 'preschool_and_kindergarten_ed_' . $section . '_section' ) == 1 ){
                get_template_part( 'sections/' . esc_attr( $section ) );
            } 
        }
            
    }else{

        include( get_page_template() );

    }
                  
get_footer();