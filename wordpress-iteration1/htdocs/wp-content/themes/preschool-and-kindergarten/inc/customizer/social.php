<?php
/*
*  * Social Link Theme Option.
*
* @package Preschool_and_Kindergarten
*/

function preschool_and_kindergarten_customize_register_social( $wp_customize ) {
 /** Social Settings */
    $wp_customize->add_section(
        'preschool_and_kindergarten_social_settings',
        array(
            'title' => __( 'Social Settings', 'preschool-and-kindergarten' ),
            'description' => __( 'Leave blank if you do not want to show the social link.', 'preschool-and-kindergarten' ),
            'priority' => 50,
            'capability' => 'edit_theme_options',
        )
    );
    
    /** Enable/Disable Social in Header */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ed_social',
        array(
            'default' => '',
            'sanitize_callback' => 'preschool_and_kindergarten_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ed_social',
        array(
            'label' => __( 'Enable Social Links in Header', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'checkbox',
        )
    );

    /** Facebook */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_facebook',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_facebook',
        array(
            'label' => __( 'Facebook', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    
    
        /** Twitter */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_twitter',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_twitter',
        array(
            'label' => __( 'Twitter', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    
    /** Google Plus */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_google_plus',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_google_plus',
        array(
            'label' => __( 'Google Plus', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    
    /** LinkedIn */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_linkedin',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_linkedin',
        array(
            'label' => __( 'LinkedIn', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    /** Pinterest */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_pinterest',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_pinterest',
        array(
            'label' => __( 'Pinterest', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    
/** Instagram */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_instagram',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_instagram',
        array(
            'label' => __( 'Instagram', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    
    /** YouTube */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_youtube',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_youtube',
        array(
            'label' => __( 'YouTube', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );

    
    /** Ok */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_ok',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_ok',
        array(
            'label' => __( 'OK', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );
    /** Vk */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_vk',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_vk',
        array(
            'label' => __( 'VK', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );

    /** Xing */
    $wp_customize->add_setting(
        'preschool_and_kindergarten_xing',
        array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'preschool_and_kindergarten_xing',
        array(
            'label' => __( 'Xing', 'preschool-and-kindergarten' ),
            'section' => 'preschool_and_kindergarten_social_settings',
            'type' => 'text',
        )
    );

}
add_action( 'customize_register', 'preschool_and_kindergarten_customize_register_social' );