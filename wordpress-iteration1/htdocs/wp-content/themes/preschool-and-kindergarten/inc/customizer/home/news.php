<?php 
/*
* news Section Theme Option.
*
* @Package  preschool_and_kindergarten
*/

 function preschool_and_kindergarten_customize_register_news( $wp_customize ) {

   /** news Section */
    $wp_customize->add_section(
        'preschool_and_kindergarten_news_settings',
        array(
            'title' => __( 'News Section', 'preschool-and-kindergarten' ),
            'priority' => 30,
            'panel' => 'preschool_and_kindergarten_home_page_settings',
        )
    );
    
    /** Enable/Disable news Section */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_news_section',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_news_section',
        array(
            'label' => __( 'Enable News Section', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_news_settings',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'preschool_and_kindergarten_news_section_title',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_news_section_title',
        array(
            'label' => __( 'Section Title', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_news_settings',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting(
        'preschool_and_kindergarten_news_section_description',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_news_section_description',
        array(
            'label' => __( 'Section Description', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_news_settings',
            'type' => 'textarea',
        )
    );     
     

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_news' );
