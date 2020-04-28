<?php 
/*
* Testmonial Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

function preschool_and_kindergarten_customize_register_testmonial( $wp_customize ) {

    global $preschool_and_kindergarten_option_categories;
    
     /** testimonials Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_testimonials_settings',
        array(
            'title' => __( 'Testimonials Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable testimonials Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_testimonials_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_testimonials_section',
        array(
            'label' => __( 'Enable Testimonials Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_testimonials_settings',
            'type' => 'checkbox',
        )
    );

      $wp_customize->add_setting(
        'preschool_and_kindergarten_testimonials_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_testimonials_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_testimonials_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_testimonials_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_testimonials_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_testimonials_settings',
            'type' => 'textarea',
        )
    );


    /** Select Category */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_testimonial_slider_cat',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_testimonial_slider_cat',
        array(
            'label' => __( 'Choose Testimonials Category', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_testimonials_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_option_categories,
        )
    );

    /** Enable/Disable Slider Auto Transition for testimonial*/
    $wp_customize->add_setting(
        'preschool_and_kindergarten_slider_testimonial_auto',
        array(
            'default' => '1',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_slider_testimonial_auto',
        array(
            'label' => __( 'Enable Slider Auto Transition', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_testimonials_settings',
            'type' => 'checkbox',
        )
    );

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_testmonial' );
