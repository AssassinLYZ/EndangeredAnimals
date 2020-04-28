<?php 
/*
* lessons Section Theme Option.
*
* @Package  Preschool and Kindergarten
*/

 function preschool_and_kindergarten_customize_register_lessons( $wp_customize ) {
    
    global $preschool_and_kindergarten_options_posts;
    /** lesson Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_lessons_settings',
        array(
            'title' => __( 'Lessons Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable lessons Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_lessons_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_lessons_section',
        array(
            'label' => __( 'Enable Lessons Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_lessons_settings',
            'type' => 'checkbox',
        )
    );

   $wp_customize->add_setting(
        'preschool_and_kindergarten_lessons_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_lessons_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_lessons_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_lessons_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_lessons_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_lessons_settings',
            'type' => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'preschool_and_kindergarten_lesson_post_one',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_lesson_post_one',
        array(
            'label' => __( 'Select Post', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_lessons_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );


    $wp_customize->add_setting(
        'preschool_and_kindergarten_lesson_post_two',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_lesson_post_two',
        array(
            'label' => __( 'Select Post', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_lessons_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );


    $wp_customize->add_setting(
        'preschool_and_kindergarten_lesson_post_three',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_lesson_post_three',
        array(
            'label' => __( 'Select Post', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_lessons_settings',
            'type' => 'select',
            'choices' => $preschool_and_kindergarten_options_posts,
        )
    );
}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_lessons');