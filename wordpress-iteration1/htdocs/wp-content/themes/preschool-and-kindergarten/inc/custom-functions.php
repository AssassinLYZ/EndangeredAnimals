<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Preschool_and_Kindergarten
 */

if ( ! function_exists( 'preschool_and_kindergarten_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function preschool_and_kindergarten_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on Preschool and Kindergarten, use a find and replace
     * to change 'preschool-and-kindergarten' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'preschool-and-kindergarten', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

        /** Custom Logo */
    add_theme_support( 'custom-logo', array(        
        'header-text' => array( 'site-title', 'site-description' ),
    ) );

     //Add Excerpt support on page
    add_post_type_support( 'page', 'excerpt' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary', 'preschool-and-kindergarten' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    /*
     * Enable support for Post Formats.
     * See https://developer.wordpress.org/themes/functionality/post-formats/
     */
    add_theme_support( 'post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
    ) );

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'preschool_and_kindergarten_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );

    add_image_size( 'preschool-and-kindergarten-with-sidebar', 832, 475, true);
    add_image_size( 'preschool-and-kindergarten-without-sidebar', 1140, 475, true);
    add_image_size( 'preschool-and-kindergarten-banner-thumb', 1349, 447, true);
    add_image_size( 'preschool-and-kindergarten-about-thumb', 555, 335, true);
    add_image_size( 'preschool-and-kindergarten-lesson-thumb', 186, 185, true);
    add_image_size( 'preschool-and-kindergarten-program-thumb', 220, 220, true);
    add_image_size( 'preschool-and-kindergarten-testimonials-thumb', 570, 474, true);
    add_image_size( 'preschool-and-kindergarten-staff-thumb', 360, 385, true);
    add_image_size( 'preschool-and-kindergarten-popular-post-thumb', 60, 60, true);
    add_image_size( 'preschool-and-kindergarten-schema', 600, 60, true);
}
endif;
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function preschool_and_kindergarten_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'preschool_and_kindergarten_content_width', 832 );
}
/**
* Adjust content_width value according to template.
*
* @return void
*/
function preschool_and_kindergarten_template_redirect_content_width() {

    // Full Width in the absence of sidebar.
    if( is_page() ){
       $sidebar_layout = preschool_and_kindergarten_sidebar_layout();
       if( ( $sidebar_layout == 'no-sidebar' ) || ! ( is_active_sidebar( 'right-sidebar' ) ) ) $GLOBALS['content_width'] = 1140;
        
    }elseif ( ! ( is_active_sidebar( 'right-sidebar' ) ) ) {
        $GLOBALS['content_width'] = 1140;
    }

}
/**
 * Enqueue scripts and styles.
 */
function preschool_and_kindergarten_scripts() {
    // Use minified libraries if SCRIPT_DEBUG is false
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    $query_args = array(
        'family' => 'Lato:400,400italic,700|Pacifico',
        );
    
    
    wp_enqueue_style( 'preschool-and-kindergarten-google-fonts', add_query_arg($query_args, "//fonts.googleapis.com/css"));
    wp_enqueue_style( 'animate', get_template_directory_uri(). '/css' . $build . '/animate' . $suffix . '.css' );
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri(). '/css' . $build . '/owl.carousel' . $suffix . '.css' );
    
    wp_enqueue_style( 'preschool-and-kindergarten-style', get_stylesheet_uri() , array(), PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION );
    
    if( preschool_and_kindergarten_is_woocommerce_activated() )
    wp_enqueue_style( 'preschool-and-kindergarten-woocommerce-style', get_template_directory_uri(). '/css' . $build . '/woocommerce' . $suffix . '.css', PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION );
    
    wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery' ), '5.6.3', false );
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js' . $build . '/owl.carousel' . $suffix . '.js', array('jquery'), '2.2.1', true);

    wp_enqueue_script( 'owl-carousel-aria', get_template_directory_uri() . '/js' . $build . '/owl.carousel.aria' . $suffix . '.js', array('jquery'), '2.0.0', true);
    
    wp_enqueue_script('preschool-and-kindergarten-custom', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array('jquery'), PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION, true);
    
    //slider settings
    $slider_auto      = get_theme_mod( 'preschool_and_kindergarten_slider_auto', '1' );
    $slider_loop      = get_theme_mod( 'preschool_and_kindergarten_slider_loop', '1' );
    $slider_control   = get_theme_mod( 'preschool_and_kindergarten_slider_control', '1' );
    $slider_animation = get_theme_mod( 'preschool_and_kindergarten_slider_animation', 'slide' );
    $slider_speed     = get_theme_mod( 'preschool_and_kindergarten_slider_speed', '7000' );
    $animation_speed  = get_theme_mod( 'preschool_and_kindergarten_animation_speed', '600' );
      //testimonial autoplay
    $slider_testimonial_auto  = get_theme_mod( 'preschool_and_kindergarten_slider_testimonial_auto', '1' );
    
    $slider_array = array(
        'auto'      => esc_attr( $slider_auto ),
        'loop'      => esc_attr( $slider_loop ),
        'control'   => esc_attr( $slider_control ),
        'animation' => esc_attr( $slider_animation ),
        'speed'     => absint( $slider_speed ),
        'a_speed'   => absint( $animation_speed ),
        'rtl'       => is_rtl(),
        't_auto'    => esc_attr( $slider_testimonial_auto ), 
    );
    
    wp_localize_script( 'preschool-and-kindergarten-custom', 'preschool_and_kindergarten_data', $slider_array );
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

if ( is_admin() ) : // Load only if we are viewing an admin page

    function preschool_and_kindergarten_customize_scripts() {
        
        wp_enqueue_style( 'preschool-and-kindergarten-customize-style',get_template_directory_uri().'/inc/css/customize.css', '',PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION );
        wp_enqueue_script( 'preschool-and-kindergarten-customizer-js',get_template_directory_uri().'/inc/js/admin.js', array('jquery'), PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION, 'screen' );
        
    }

endif;

if( ! function_exists( 'preschool_and_kindergarten_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function preschool_and_kindergarten_admin_scripts(){
    wp_enqueue_style( 'preschool-and-kindergarten-admin', get_template_directory_uri() . '/inc/css/admin.css', '', PRESCHOOL_AND_KINDERGARTEN_THEME_VERSION );
}
endif; 
add_action( 'admin_enqueue_scripts', 'preschool_and_kindergarten_admin_scripts' );
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function preschool_and_kindergarten_body_classes( $classes ) {
	
  global $post;
    
    // Adds a class of group-blog to blogs with more than 1 published author.
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of custom-background-image to sites with a custom background image.
    if ( get_background_image() ) {
        $classes[] = 'custom-background-image';
    }

    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
        $classes[] = 'custom-background-color';
    }
    
    if( is_404()){
        $classes[] = 'full-width';
    }

     if( !( is_active_sidebar( 'right-sidebar' ) ) ) {
        $classes[] = 'full-width'; 
    }
    
    if( preschool_and_kindergarten_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || 'product' === get_post_type() ) && ! is_active_sidebar( 'shop-sidebar' ) ){
        $classes[] = 'full-width';
    }

    if( is_page() ){
        $sidebar_layout = preschool_and_kindergarten_sidebar_layout();
        if( $sidebar_layout == 'no-sidebar' )
        $classes[] = 'full-width';
    }

    return $classes;
}
/**
 * Flush out the transients used in preschool_and_kindergarten_categorized_blog.
 */
function preschool_and_kindergarten_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'preschool_and_kindergarten_categories' );
}

if ( ! function_exists( 'preschool_and_kindergarten_excerpt_more' ) ) :
    /**
    * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
    */
    function preschool_and_kindergarten_excerpt_more( $more ) {
        return is_admin() ? $more : ' &hellip; ';
    }

endif;


if ( ! function_exists( 'preschool_and_kindergarten_excerpt_length' ) ) :
    /**
    * Changes the default 55 character in excerpt 
    */
    function preschool_and_kindergarten_excerpt_length( $length ) {
        return is_admin() ? $length : 40;
    }

endif;


/**
 * Custom CSS
*/
if ( function_exists( 'wp_update_custom_css_post' ) ) {
    // Migrate any existing theme CSS to the core option added in WordPress 4.7.
    $css = get_theme_mod( 'preschool_and_kindergarten_custom_css' );
    if ( $css ) {
        $core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
        $return = wp_update_custom_css_post( $core_css . $css );
        if ( ! is_wp_error( $return ) ) {
            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
            remove_theme_mod( 'preschool_and_kindergarten_custom_css' );
        }
    }
} else {
    // Back-compat for WordPress < 4.7.
  function preschool_and_kindergarten_custom_css(){
    $custom_css = get_theme_mod( 'preschool_and_kindergarten_custom_css' );
    if( ! empty( $custom_css ) ){
      echo '<style type="text/css">';
      echo wp_strip_all_tags( $custom_css );
      echo '</style>';
    }
  }
  add_action( 'wp_head', 'preschool_and_kindergarten_custom_css', 100 );
}


if( ! function_exists('preschool_and_kindergarten_social_cb')):
/** Callback for Social Links */
 function preschool_and_kindergarten_social_cb(){
    $facebook  = get_theme_mod( 'preschool_and_kindergarten_facebook' );
    $twitter   = get_theme_mod( 'preschool_and_kindergarten_twitter' );
    $google    = get_theme_mod( 'preschool_and_kindergarten_google_plus' );
    $pinterest = get_theme_mod( 'preschool_and_kindergarten_pinterest' );
    $linkedin  = get_theme_mod( 'preschool_and_kindergarten_linkedin' );
    $instagram = get_theme_mod( 'preschool_and_kindergarten_instagram' );
    $youtube   = get_theme_mod( 'preschool_and_kindergarten_youtube' );
    $ok        = get_theme_mod( 'preschool_and_kindergarten_ok' );
    $vk        = get_theme_mod( 'preschool_and_kindergarten_vk' );
    $xing      = get_theme_mod( 'preschool_and_kindergarten_xing' );
    
    if( $facebook || $twitter || $google || $linkedin || $pinterest || $instagram || $youtube || $ok || $vk || $xing ){
    ?>
        <ul class="social-networks">
              
          <?php if( $facebook ){ ?>
                
                <li><a href="<?php echo esc_url( $facebook );?>" target="_blank" title="<?php esc_attr_e( 'Facebook', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-facebook"></span></a></li>
          
          <?php } if( $twitter ){?>    
               
                <li><a href="<?php echo esc_url( $twitter );?>" target="_blank" title="<?php esc_attr_e( 'Twitter', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-twitter"></span></a></li>
          
          <?php } if( $google ){?>
                
                <li><a href="<?php echo esc_url( $google );?>" target="_blank" title="<?php esc_attr_e( 'Google Plus', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-google-plus"></span></a></li>
              
          <?php } if( $linkedin ){?>
                
                <li><a href="<?php echo esc_url( $linkedin );?>" target="_blank" title="<?php esc_attr_e( 'LinkedIn', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-linkedin"></span></a></li>

          <?php } if( $pinterest ){?>
                
                <li><a href="<?php echo esc_url( $pinterest );?>" target="_blank" title="<?php esc_attr_e( 'Pinterest', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-pinterest"></span></a></li>

          <?php } if( $instagram ){?>
                
                <li><a href="<?php echo esc_url( $instagram );?>" target="_blank" title="<?php esc_attr_e( 'Instagram', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-instagram"></span></a></li>

          <?php } if( $youtube ){?>
                
                <li><a href="<?php echo esc_url( $youtube );?>" target="_blank" title="<?php esc_attr_e( 'Youtube', 'preschool-and-kindergarten' ); ?>"><span class="fa fa-youtube"></span></a></li>
            
        <?php } if( $ok ){ ?>

                <li><a href="<?php echo esc_url( $ok ); ?>" target="_blank" title="<?php esc_attr_e( 'OK', 'preschool-and-kindergarten' );?>"><i class="fa fa-odnoklassniki"></i></a></li>

        <?php } if( $vk ){ ?>
                <li><a href="<?php echo esc_url( $vk ); ?>" target="_blank" title="<?php esc_attr_e( 'VK', 'preschool-and-kindergarten' );?>"><i class="fa fa-vk"></i></a></li>
                
        <?php } if( $xing ){ ?>
                <li><a href="<?php echo esc_url( $xing ); ?>" target="_blank" title="<?php esc_attr_e( 'Xing', 'preschool-and-kindergarten' );?>"><i class="fa fa-xing"></i></a></li>
        <?php } ?>
        </ul>
    <?php
    }
 }
 endif;
 
if( ! function_exists( 'preschool_and_kindergarten_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function preschool_and_kindergarten_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'preschool-and-kindergarten-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = preschool_and_kindergarten_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( preschool_and_kindergarten_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => "http://schema.org",
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => get_permalink( $post->ID )
            ),
            "headline"  => get_the_title( $post->ID ),
            "image"     => array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            ),
            "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
            "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => preschool_and_kindergarten_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "publisher" => array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if ( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if ( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">' , PHP_EOL;
        if ( version_compare( PHP_VERSION, '5.4.0' , '>=' ) ) {
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
        } else {
            echo wp_json_encode( $args ) , PHP_EOL;
        }
        echo '</script>' , PHP_EOL;
    }
}
endif;
add_action( 'wp_head', 'preschool_and_kindergarten_single_post_schema' );

if( ! function_exists( 'preschool_and_kindergarten_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function preschool_and_kindergarten_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'preschool_and_kindergarten_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'preschool-and-kindergarten' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'preschool-and-kindergarten' ), $name ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=preschool-and-kindergarten-getting-started' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to the getting started.', 'preschool-and-kindergarten' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?preschool_and_kindergarten_admin_notice=1"><?php esc_html_e( 'Dismiss', 'preschool-and-kindergarten' ); ?></a></strong></p>
                </div>
            </div>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'preschool_and_kindergarten_admin_notice' );

if( ! function_exists( 'preschool_and_kindergarten_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function preschool_and_kindergarten_update_admin_notice(){
    if ( isset( $_GET['preschool_and_kindergarten_admin_notice'] ) && $_GET['preschool_and_kindergarten_admin_notice'] = '1' ) {
        update_option( 'preschool_and_kindergarten_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'preschool_and_kindergarten_update_admin_notice' );

if( ! function_exists( 'preschool_and_kindergarten_restore_admin_notice' ) ) :
/**
 * Restoring admin notice on theme switch
 */
function preschool_and_kindergarten_restore_admin_notice(){
    update_option( 'preschool_and_kindergarten_admin_notice', false );
}
endif;
add_action( 'after_switch_theme', 'preschool_and_kindergarten_restore_admin_notice' );
