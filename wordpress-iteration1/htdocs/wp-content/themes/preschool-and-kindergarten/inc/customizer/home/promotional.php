<?php 
/*
* promotional Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

 function preschool_and_kindergarten_customize_register_promotional( $wp_customize ) {
  
     /** promotional Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_promotional_settings',
        array(
            'title' => __( 'Promotional Block Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable promotional Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_promotional_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_promotional_section',
        array(
            'label' => __( 'Enable Promotional Block Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_promotional_settings',
            'type' => 'checkbox',
        )
    );
    
    $wp_customize->add_setting(
        'preschool_and_kindergarten_promotional_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_promotional_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_promotional_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_promotional_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_promotional_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_promotional_settings',
            'type' => 'textarea',
        )
    );


    $wp_customize->add_setting(
        'preschool_and_kindergarten_button_label',
        array(
            'default' => 'View Details',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_button_label',
        array(
            'label' => __( 'Button Label', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_promotional_settings',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting(
        'preschool_and_kindergarten_button_link',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_button_link',
        array(
            'label' => __( 'Button Link', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_promotional_settings',
            'type' => 'text',
        )
    );
  

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_promotional' );
