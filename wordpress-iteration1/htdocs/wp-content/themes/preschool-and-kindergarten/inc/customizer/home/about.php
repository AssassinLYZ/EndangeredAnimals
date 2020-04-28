<?php 
/*
* About Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

 function preschool_and_kindergarten_customize_register_about( $wp_customize ) {

    global $preschool_and_kindergarten_options_pages;

    $wp_customize->add_section(
        'preschool_and_kindergarten_about_settings',
        array(
            'title' => __( 'About Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable about Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_about_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_about_section',
        array(
            'label' => __( 'Enable About Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_about_settings',
            'type' => 'checkbox',
        )
    );

     /** About Page */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_about_page',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_about_page',
        array(
            'label' => __( 'Select Page', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_about_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_pages,
        )
    );

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_about' );
