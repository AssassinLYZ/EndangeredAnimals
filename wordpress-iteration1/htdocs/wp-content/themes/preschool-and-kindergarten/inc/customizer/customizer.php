<?php
/**
 * Preschool and Kindergarten Theme Customizer.
 *
 * @package Preschool_and_Kindergarten
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
 
    $preschool_and_kindergarten_sections = array( 'banners', 'about', 'lessons', 'services', 'promotional', 'program', 'testimonials', 'staff', 'news' );
    
    $preschool_and_kindergarten_settings = array( 'default', 'header', 'home', 'breadcrumb', 'social', 'footer', 'custom' );

   /* Option list of all post */	
    $preschool_and_kindergarten_options_posts = array();
    $preschool_and_kindergarten_options_posts_obj = get_posts('posts_per_page=-1');
    $preschool_and_kindergarten_options_posts[''] = __( 'Choose Post', 'preschool-and-kindergarten' );
    foreach ( $preschool_and_kindergarten_options_posts_obj as $psk_posts ) {
    	$preschool_and_kindergarten_options_posts[$psk_posts->ID] = $psk_posts->post_title;
    }

     /** Option list of all pages */ 
    $preschool_and_kindergarten_options_pages = array();
    $preschool_and_kindergarten_options_pages_obj = get_posts('posts_per_page=-1&post_type=page');
    $preschool_and_kindergarten_options_pages[''] = __( 'Choose Page', 'preschool-and-kindergarten' );
    foreach ( $preschool_and_kindergarten_options_pages_obj as $psk_pages ) {
        $preschool_and_kindergarten_options_pages[$psk_pages->ID] = $psk_pages->post_title; 
    }
    
    /* Option list of all categories */
    $args = array(
	   'type'                     => 'post',
	   'orderby'                  => 'name',
	   'order'                    => 'ASC',
	   'hide_empty'               => 1,
	   'hierarchical'             => 1,
	   'taxonomy'                 => 'category'
    ); 
    $preschool_and_kindergarten_option_categories = array();
    $category_lists = get_categories( $args );
    $preschool_and_kindergarten_option_categories[''] = __( 'Choose Category', 'preschool-and-kindergarten' );
    foreach( $category_lists as $category ){
        $preschool_and_kindergarten_option_categories[$category->term_id] = $category->name;
    }
     

    foreach( $preschool_and_kindergarten_settings as $setting ){
        require get_template_directory() . '/inc/customizer/' . $setting . '.php';
    }

    foreach( $preschool_and_kindergarten_sections as $section ){
        require get_template_directory() . '/inc/customizer/home/' . $section . '.php';
    }

    /**
     * Sanitization Functions
    */
    require get_template_directory() . '/inc/customizer/sanitization-functions.php';



    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     */
    function preschool_and_kindergarten_customize_preview_js() {
        // Use minified libraries if SCRIPT_DEBUG is false
        $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        wp_enqueue_script( 'preschool_and_kindergarten_customizer', get_template_directory_uri() . '/js' . $build . '/customizer' . $suffix . '.js', array( 'customize-preview' ), '20151215', true );
    }
    add_action( 'customize_preview_init', 'preschool_and_kindergarten_customize_preview_js' );
