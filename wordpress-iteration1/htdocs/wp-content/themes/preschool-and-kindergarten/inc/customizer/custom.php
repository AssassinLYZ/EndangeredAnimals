<?php 
/*
* * Custom CSS Theme Option. 
* @package Preschool and kindergarten
*/

function preschool_and_kindergarten_customize_register_custom( $wp_customize ) {
    
    if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
        /** Custom CSS*/
        $wp_customize->add_section(
            'preschool_and_kindergarten_custom_settings',
            array(
                'title' => __( 'Custom CSS Settings', 'preschool-and-kindergarten' ),
                'priority' => 50,
                'capability' => 'edit_theme_options',
            )
        );
        
        $wp_customize->add_setting(
            'preschool_and_kindergarten_custom_css',
            array(
                'default' => '',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'wp_strip_all_tags'
                )
        );
        
        $wp_customize->add_control(
            'preschool_and_kindergarten_custom_css',
            array(
                'label' => __( 'Custom Css', 'preschool-and-kindergarten' ),
                'section' => 'preschool_and_kindergarten_custom_settings',
                'description' => __( 'Put your custom CSS', 'preschool-and-kindergarten' ),
                'type' => 'textarea',
            )
        );
        /** Custom CSS Ends */
    }
    
}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_custom' );