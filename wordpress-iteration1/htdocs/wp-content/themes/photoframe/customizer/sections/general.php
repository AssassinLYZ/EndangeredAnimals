<?php

function ilovewp_customizer_define_general_sections( $sections ) {
    $panel           = 'ilovewp' . '_general';
    $general_sections = array();

    $theme_header_style = array(
        'default' => esc_html__('Default', 'photoframe'),
        'centered' => esc_html__('Centered', 'photoframe')
    );

    $general_sections['general'] = array(
        'title'     => esc_html__( 'Theme Settings', 'photoframe' ),
        'priority'  => 4900,
        'options'   => array(

            'theme-header-style'     => array(
                'setting' => array(
                    'default' => 'default',
                    'sanitize_callback' => 'ilovewp_sanitize_text'
                ),
                'control' => array(
                    'label' => esc_html__( 'Header Layout', 'photoframe' ),
                    'type'  => 'radio',
                    'choices' => $theme_header_style
                ),
            ),

            'photoframe-display-featured-hero' => array(
                'setting'               => array(
                    'sanitize_callback' => 'absint',
                    'default'           => 0
                ),
                'control'               => array(
                    'label'             => __( 'Display Featured Images on Single Pages', 'photoframe' ),
                    'type'              => 'checkbox'
                )
            ),

            'photoframe-display-recentposts'    => array(
                'setting'               => array(
                    'sanitize_callback' => 'absint',
                    'default'           => 1
                ),
                'control'               => array(
                    'label'             => __( 'Display Recent Posts on Homepage', 'photoframe' ),
                    'type'              => 'checkbox'
                )
            ),

            'photoframe-display-pages'    => array(
                'setting'               => array(
                    'sanitize_callback' => 'absint',
                    'default'           => 0
                ),
                'control'               => array(
                    'label'             => __( 'Display Featured Pages on Homepage', 'photoframe' ),
                    'type'              => 'checkbox'
                )
            ),

            'photoframe-featured-pages-headline' => array(
                'setting' => array(
                    'sanitize_callback' => 'ilovewp_sanitize_text',
                    'default'           => esc_html__( 'Portfolio Categories', 'photoframe' ),
                ),
                'control' => array(
                    'label'             => esc_html__( 'Featured Pages Headline', 'photoframe' ),
                    'type'              => 'text',
                ),
            ),

            'photoframe-featured-page-1'  => array(
                'setting'               => array(
                    'default'           => 'none',
                    'sanitize_callback' => 'photoframe_sanitize_pages'
                ),
                'control'               => array(
                    'label'             => esc_html__( 'Featured Page #1', 'photoframe' ),
                                            /* translators: Link to dashboard pages page */
                    'description'       => sprintf( wp_kses( __( 'This list is populated with <a href="%1$s">Pages</a>.', 'photoframe' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'edit.php?post_type=page' ) ) ),
                    'type'              => 'select',
                    'choices'           => photoframe_get_pages()
                ),
            ),

            'photoframe-featured-page-2'  => array(
                'setting'               => array(
                    'default'           => 'none',
                    'sanitize_callback' => 'photoframe_sanitize_pages'
                ),
                'control'               => array(
                    'label'             => esc_html__( 'Featured Page #2', 'photoframe' ),
                    'type'              => 'select',
                    'choices'           => photoframe_get_pages()
                ),
            ),

            'photoframe-featured-page-3'  => array(
                'setting'               => array(
                    'default'           => 'none',
                    'sanitize_callback' => 'photoframe_sanitize_pages'
                ),
                'control'               => array(
                    'label'             => esc_html__( 'Featured Page #3', 'photoframe' ),
                    'type'              => 'select',
                    'choices'           => photoframe_get_pages()
                ),
            ),

        ),
    );

    return array_merge( $sections, $general_sections );
}

$wp_customize->add_setting( 
    'theme-logo-white', 
    array(
        'sanitize_callback' => 'esc_url_raw'
    )
);

$wp_customize->add_control(
    new WP_Customize_Upload_Control(
        $wp_customize,
        'file-upload-footer-logo',
        array(
            'label' => esc_html__( 'Alternative Logo', 'photoframe' ),
            'description' => esc_html__( 'This logo will appear when the slideshow contains dark images, so the logo will be automatically switched to the Alternative version.', 'photoframe' ),
            'section' => 'ilovewp_general',
            'settings' => 'theme-logo-white'
        )
    )
);

add_filter( 'ilovewp_customizer_sections', 'ilovewp_customizer_define_general_sections' );