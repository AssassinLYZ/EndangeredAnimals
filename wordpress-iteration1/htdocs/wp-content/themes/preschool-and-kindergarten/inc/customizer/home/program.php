<?php 
/*
* program Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

 function preschool_and_kindergarten_customize_register_program( $wp_customize ) {
   
   global $preschool_and_kindergarten_options_posts;
   /** program Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_program_settings',
        array(
            'title' => __( 'Program Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable program Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_program_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_program_section',
        array(
            'label' => __( 'Enable Program Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_program_settings',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'preschool_and_kindergarten_program_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_program_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_program_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_program_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_program_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_program_settings',
            'type' => 'textarea',
        )
    );

     /** program Post One */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_program_post_one',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_program_post_one',
        array(
            'label' => __( 'Select Program Post One', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_program_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );

     /** program Post two */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_program_post_two',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_program_post_two',
        array(
            'label' => __( 'Select Program Post Two', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_program_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );

    /** program Post three */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_program_post_three',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_program_post_three',
        array(
            'label' => __( 'Select Program Post Three', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_program_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_program' );
