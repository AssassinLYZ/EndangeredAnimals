<?php
/**
 * BreadCrumb Theme Option.
 *
 * @package preschool_and_kindergarten
 */

function preschool_and_kindergarten_customize_register_breadcrumb( $wp_customize ) {


     /** BreadCrumb Settings */
    $wp_customize->add_section(
        'preschool_and_kindergarten_breadcrumb_settings',
        array(
            'title' => __( 'Breadcrumb Settings', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
        )
    );
   

    /** Enable/Disable BreadCrumb */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_breadcrumb',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_breadcrumb',
        array(
            'label' => __( 'Enable Breadcrumb', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_breadcrumb_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Show/Hide Current */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_current',
        array(
            'default' => '1',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_current',
        array(
            'label' => __( 'Show current', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_breadcrumb_settings',
            'type' => 'checkbox',
        )
    );
    
    /** Home Text */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_breadcrumb_home_text',
        array(
            'default' => __( 'Home', 'preschool-and-kindergarten' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_breadcrumb_home_text',
        array(
            'label' => __( 'Breadcrumb Home Text', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_breadcrumb_settings',
            'type' => 'text',
        )
    );
    
    /** Breadcrumb Separator */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_breadcrumb_separator',
        array(
            'default' => __( '>', 'preschool-and-kindergarten' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_breadcrumb_separator',
        array(
            'label' => __( 'Breadcrumb Separator', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_breadcrumb_settings',
            'type' => 'text',
        )
    );
    /** BreadCrumb Settings Ends */


}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_breadcrumb' );
