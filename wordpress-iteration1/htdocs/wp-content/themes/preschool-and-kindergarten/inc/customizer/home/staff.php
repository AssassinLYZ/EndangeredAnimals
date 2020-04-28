<?php 
/*
* staff Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

 function preschool_and_kindergarten_customize_register_staff( $wp_customize ) {
    
    global $preschool_and_kindergarten_options_posts;
    /** staff Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_staff_settings',
        array(
            'title' => __( 'Team Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable staff Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_staff_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_staff_section',
        array(
            'label' => __( 'Enable Team Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_staff_settings',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'preschool_and_kindergarten_staff_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_staff_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_staff_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_staff_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_staff_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_staff_settings',
            'type' => 'textarea',
        )
    );

    /** staff Post One */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_staff_post_one',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_staff_post_one',
        array(
            'label' => __( 'Select Post One', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_staff_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );

     /** staff Post two */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_staff_post_two',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_staff_post_two',
        array(
            'label' => __( 'Select Post Two', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_staff_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );

    /** staff Post three */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_staff_post_three',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_staff_post_three',
        array(
            'label' => __( 'Select Post Three', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_staff_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );
}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_staff' );
