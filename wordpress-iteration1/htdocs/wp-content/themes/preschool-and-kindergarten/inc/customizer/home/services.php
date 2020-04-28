<?php 
/*
* services Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

 function preschool_and_kindergarten_customize_register_services( $wp_customize ) {
    
    global $preschool_and_kindergarten_options_posts;
    /** services Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_services_settings',
        array(
            'title' => __( 'Services Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable services Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_services_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_services_section',
        array(
            'label' => __( 'Enable Services Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_services_settings',
            'type' => 'checkbox',
        )
    );

   $wp_customize->add_setting(
        'preschool_and_kindergarten_services_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_services_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_services_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_services_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_services_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_services_settings',
            'type' => 'textarea',
        )
    );


     /** services Post One */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_services_post',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_services_post',
        array(
            'label' => __( 'Select Service Post', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_services_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );  

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_services' );
