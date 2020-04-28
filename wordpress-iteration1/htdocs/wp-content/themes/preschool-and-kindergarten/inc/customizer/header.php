<?php
/**
 * Header Theme Option.
 *
 * @package preschool_and_kindergarten
 */

function preschool_and_kindergarten_customize_register_header( $wp_customize ) {

    /** Phone Number Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_header_setting',
        array(
            'title'      => __( 'Header Settings', 'preschool-and-kindergarten' ),
            'priority'   => 20,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Email Address  */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_email_address',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_email_address',
        array(
            'label' => __( 'Email Address', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_header_setting',
            'type' => 'text',
        )
    );
    
    /** Phone Number  */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_phone',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_phone',
        array(
            'label' => __( 'Phone Number', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_header_setting',
            'type' => 'text',
        )
    );
    
}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_header' );