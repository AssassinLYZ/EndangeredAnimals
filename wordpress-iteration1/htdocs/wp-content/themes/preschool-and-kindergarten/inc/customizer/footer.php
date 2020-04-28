<?php 
/**
 * Footer Option.
 *
 * @package Preschool and Kindergarten
 */
 
function preschool_and_kindergarten_customize_footer_settings( $wp_customize ) {

 /** Footer Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_footer_section',
        array(
            'title' => __( 'Footer Settings', 'preschool-and-kindergarten' ),
            'priority' => 70,
        )
    );
    
    /** Copyright Text */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_footer_copyright_text',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_footer_copyright_text',
        array(
            'label' => __( 'Copyright Info', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_footer_section',
            'type' => 'textarea',
        )
    );

}

add_action( 'customize_register', 'preschool_and_kindergarten_customize_footer_settings' );
 