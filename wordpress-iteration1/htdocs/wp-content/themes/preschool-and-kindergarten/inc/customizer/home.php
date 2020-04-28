<?php
/**
 * Home Page Theme Option.
 *
 * @package preschool_and_kindergarten
 */

function preschool_and_kindergarten_customize_register_home( $wp_customize ) {
    
    /** Home Page Settings */
    $wp_customize->add_panel( 
        'preschool_and_kindergarten_home_page_settings',
         array(
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Home Page Settings', 'preschool-and-kindergarten' ),
            'description' => __( 'Customize Home Page Settings', 'preschool-and-kindergarten' ),
        ) 
    );

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_home' );