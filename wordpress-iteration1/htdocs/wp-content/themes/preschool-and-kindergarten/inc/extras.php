<?php 
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Preschool and Kindergarten
 */

if( ! function_exists( 'preschool_and_kindergarten_get_post_meta' ) ) :
/**
 * Post meta info
*/
function preschool_and_kindergarten_get_post_meta(){
    
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'On %s', 'post date', 'preschool-and-kindergarten' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'By %s', 'post author', 'preschool-and-kindergarten' ),
		'<span class="authors vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

		// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'preschool-and-kindergarten' ) );
		if ( $categories_list && preschool_and_kindergarten_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'In %1$s', 'preschool-and-kindergarten' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
	}

	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'preschool-and-kindergarten' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

}
endif;


if ( ! function_exists( 'preschool_and_kindergarten_entry_footer' ) ) :
/**
 * Prints edit links
 */
function preschool_and_kindergarten_entry_footer() {	

    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() && is_single() ) {
        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html__( ', ', 'preschool-and-kindergarten' ) );
        if ( $tags_list ) {
            echo '<span class="tags-links"><i class="fa fa-tags" aria-hidden="true"></i>' . $tags_list . '</span>'; // WPCS: XSS OK.
        }
    }
	
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'preschool-and-kindergarten' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function preschool_and_kindergarten_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'preschool_and_kindergarten_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'preschool_and_kindergarten_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so preschool_and_kindergarten_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so preschool_and_kindergarten_categorized_blog should return false.
		return false;
	}
}


if( !function_exists( 'preschool_and_kindergarten_breadcrumbs_cb' ) ):
/**
 * Breadcrumb
*/
    function preschool_and_kindergarten_breadcrumbs_cb(){
      
        $showOnHome    = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $delimiter     = esc_html( get_theme_mod( 'preschool_and_kindergarten_breadcrumb_separator', __( '>', 'preschool-and-kindergarten' ) ) ); // delimiter between crumbs
        $home          = esc_html( get_theme_mod( 'preschool_and_kindergarten_breadcrumb_home_text', __( 'Home', 'preschool-and-kindergarten' ) ) ); // text for the 'Home' link
        $showCurrent   = get_theme_mod( 'preschool_and_kindergarten_ed_current', '1' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
        $before        = '<span class="current">'; // tag before the current crumb
        $after         = '</span>'; // tag after the current crumb
        $ed_breadcrumb = get_theme_mod( 'preschool_and_kindergarten_ed_breadcrumb' );
        
        global $post;
        $homeLink = esc_url( home_url( ) );
        
        if( $ed_breadcrumb ){
            if ( is_front_page() ) {
            
                if ( $showOnHome == 1 ) echo '<div id="crumbs"><a href="' . esc_url( $homeLink ) . '">' . esc_html( $home ) . '</a></div>';
            
            } else {
            
                 echo '<div id="crumbs"><a href="' . esc_url( $homeLink ) . '">' . esc_html( $home ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
        
            if ( is_category() ) {
                $thisCat = get_category( get_query_var( 'cat' ), false );
                if ( $thisCat->parent != 0 ) echo get_category_parents( $thisCat->parent, TRUE, ' <span class="separator">' . $delimiter . '</span> ' );
                echo $before .  esc_html( single_cat_title( '', false ) ) . $after;
            
            } elseif( preschool_and_kindergarten_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ //For Woocommerce archive page
        
                $current_term = $GLOBALS['wp_query']->get_queried_object();
                if( is_product_category() ){
                    $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo ' <a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                        }
                    }
                }           
                echo $before . esc_html( $current_term->name ) . $after;
                
            } elseif( preschool_and_kindergarten_is_woocommerce_activated() && is_shop() ){ //Shop Archive page
                if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                    return;
                }
                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
        
                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }
                echo $before . esc_html( $_name ) . $after;
                
            } elseif ( is_search() ) {
                echo $before . esc_html__( 'Search Results for "', 'preschool-and-kindergarten' ) . esc_html( get_search_query() ) . esc_html__( '"', 'preschool-and-kindergarten' ) . $after;
            
            } elseif ( is_day() ) {
                echo '<a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'preschool-and-kindergarten' ) ) ) ) . '">' . esc_html( get_the_time( __( 'Y', 'preschool-and-kindergarten' ) ) ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                echo '<a href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'preschool-and-kindergarten' ) ), get_the_time( __( 'm', 'preschool-and-kindergarten' ) ) ) ) . '">' . esc_html( get_the_time( __( 'F', 'preschool-and-kindergarten' ) ) ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                echo $before . esc_html( get_the_time( __( 'd', 'preschool-and-kindergarten' ) ) ) . $after;
            
            } elseif ( is_month() ) {
                echo '<a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'preschool-and-kindergarten' ) ) ) ) . '">' . esc_html( get_the_time( __( 'Y', 'preschool-and-kindergarten' ) ) ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                echo $before . esc_html( get_the_time( __( 'F', 'preschool-and-kindergarten' ) ) ) . $after;
            
            } elseif ( is_year() ) {
                echo $before . esc_html( get_the_time( __( 'Y', 'preschool-and-kindergarten' ) ) ) . $after;
        
            } elseif ( is_single() && !is_attachment() ) {
                
                if( preschool_and_kindergarten_is_woocommerce_activated() && 'product' === get_post_type() ){ //For Woocommerce single product
                    if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                        $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                        $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                        $ancestors = array_reverse( $ancestors );
                        foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );    
                            if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                                echo ' <a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                            }
                        }
                        echo ' <a href="' . esc_url( get_term_link( $main_term ) ) . '">' . esc_html( $main_term->name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                    }
                    
                    echo $before . esc_html( get_the_title() ) . $after;
                
                } elseif ( get_post_type() != 'post' ) {
                    
                    $post_type = get_post_type_object( get_post_type() );
                    
                    if( $post_type->has_archive == true ){
                       
                        // Add support for a non-standard label of 'archive_title' (special use case).
                       $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                       // Core filter hook.
                       $label = apply_filters( 'post_type_archive_title', $label, $post_type->name );
                       printf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type ) ), $label );
                       echo '<span class="separator">' . esc_html( $delimiter ) . '</span> ';
        
                    }
                    if ( $showCurrent == 1 ){ 
                       
                        echo $before . esc_html( get_the_title() ) . $after;
                    }

                } else {
                    $cat = get_the_category(); 
                    if( $cat ){
                        $cat = $cat[0];
                        $cats = get_category_parents( $cat, TRUE, ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' );
                        if ( $showCurrent == 0 ) $cats = preg_replace( "#^(.+)\s$delimiter\s$#", "$1", $cats );
                        echo $cats;
                    }
                    if ( $showCurrent == 1 ) echo $before . esc_html( get_the_title() ) . $after;
                }
            
            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                if ( get_query_var('paged') ) {
                    echo '<a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '">' . esc_html( $post_type->label ) . '</a>';
                    if( $showCurrent == 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' . $before . sprintf( __('Page %s','preschool-and-kindergarten'), get_query_var('paged') ) . $after;
                } else {
                    if ( $showCurrent == 1 ) echo $before . esc_html( $post_type->label ) . $after;
                }

            } elseif ( is_attachment() ) {
                $parent = get_post( $post->post_parent );
                $cat = get_the_category( $parent->ID ); 
                if( $cat ){
                    $cat = $cat[0];
                    echo get_category_parents( $cat, TRUE, ' <span class="separator">' . esc_html( $delimiter ) . '</span> ');
                    echo '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( $parent->post_title ) . '</a>' . ' <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                }
                if ( $showCurrent == 1 ) echo  $before . esc_html( get_the_title() ) . $after;
            
            } elseif ( is_page() && !$post->post_parent ) {
                if ( $showCurrent == 1 ) echo $before . esc_html( get_the_title() ) . $after;
        
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while( $parent_id ){
                    $page = get_post( $parent_id );
                    $breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
                    $parent_id  = $page->post_parent;
                }
                $breadcrumbs = array_reverse( $breadcrumbs );
                for ( $i = 0; $i < count( $breadcrumbs) ; $i++ ){
                    echo $breadcrumbs[$i];
                    if ( $i != count( $breadcrumbs ) - 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ';
                }
                if ( $showCurrent == 1 ) echo ' <span class="separator">' . esc_html( $delimiter ) . '</span> ' . $before . esc_html( get_the_title() ) . $after;
            
            } elseif ( is_tag() ) {
                echo $before . esc_html( single_tag_title( '', false ) ) . $after;
            
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata( $author );
                echo $before . esc_html( $userdata->display_name ) . $after;
            
            } elseif ( is_404() ) {
                echo $before . esc_html__( '404 Error - Page not Found', 'preschool-and-kindergarten' ) . $after;
            } elseif( is_home() ){
                echo $before;
                single_post_title();
                echo $after;
            }
        
            echo '</div>';
            
            }
        }   
    } // end preschool_and_kindergarten_breadcrumbs()
endif;


/**
 * Return sidebar layouts for pages
*/
function preschool_and_kindergarten_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'preschool_and_kindergarten_sidebar_layout', true ) ){
        return get_post_meta( $post->ID, 'preschool_and_kindergarten_sidebar_layout', true );    
    }else{
        return 'right-sidebar';
    }
}

if( ! function_exists( 'preschool_and_kindergarten_pagination' ) ) :
/**
 * Pagination
*/
function preschool_and_kindergarten_pagination(){
    
    if( is_single() ){
        the_post_navigation();
    }else{
        the_posts_pagination( array(
            'prev_text'   => __( ' Prev ', 'preschool-and-kindergarten' ),
            'next_text'   => __( ' Next ', 'preschool-and-kindergarten' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'preschool-and-kindergarten' ) . ' </span>',
         ) );

    }
    
}
endif;


/**
 * Returns Section header
*/
function preschool_and_kindergarten_get_section_header( $title, $description ){
    if( $title || $description ){ ?>
        <header class="header">
            <?php 
                if( $title ) echo '<h2 class="title">' . esc_html( $title ) . '</h2>';
                if( $description ) echo wpautop( wp_kses_post( $description ) ); 
            ?>
        </header>
    <?php
    }
}

/**
 * Query WooCommerce activation
 */
function preschool_and_kindergarten_is_woocommerce_activated() {
    return class_exists( 'woocommerce' ) ? true : false;
}
if( ! function_exists( 'preschool_and_kindergarten_ed_section') ):
    /**
     * Check if home page section are enable or not.
    */

    function preschool_and_kindergarten_ed_section(){
        $preschool_and_kindergarten_page_sections = array( 'banners', 'about', 'lessons', 'services', 'promotional', 'program', 'testimonials', 'staff', 'news' );
        $en_sec = false;
        foreach( $preschool_and_kindergarten_page_sections as $section ){ 
            if( get_theme_mod( 'preschool_and_kindergarten_ed_' . $section . '_section' ) == 1 ){
                $en_sec = true;
            }
        }
        return $en_sec;
    }

endif;

if( ! function_exists( 'preschool_and_kindergarten_change_comment_form_default_fields' ) ) :
/**
* Change Comment form default fields i.e. author, email & url.
*/
function preschool_and_kindergarten_change_comment_form_default_fields( $fields ){    
   // get the current commenter if available
   $commenter = wp_get_current_commenter();

   // core functionality
   $req = get_option( 'require_name_email' );
   $aria_req = ( $req ? " aria-required='true'" : '' );    

   // Change just the author field
   $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name*', 'preschool-and-kindergarten' ) . '</label><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'preschool-and-kindergarten' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
   
   $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email*', 'preschool-and-kindergarten' ) . '</label><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'preschool-and-kindergarten' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
   
   $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'preschool-and-kindergarten' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'preschool-and-kindergarten' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';
   
   return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'preschool_and_kindergarten_change_comment_form_default_fields' );

if( ! function_exists( 'preschool_and_kindergarten_change_comment_form_defaults' ) ) :
/**
* Change Comment Form defaults
*/
function preschool_and_kindergarten_change_comment_form_defaults( $fields ){
   $comment_field = $fields['comment'];  
   $fields['comment'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'preschool-and-kindergarten' ) . '</label>
<textarea id="comment" name="comment" cols="40" rows="8" required="required" placeholder="' . esc_attr__( 'Comment','preschool-and-kindergarten' ) . '"></textarea></p>';;
   
   return $fields;    
}
endif;
add_filter( 'comment_form_fields', 'preschool_and_kindergarten_change_comment_form_defaults' );

if( ! function_exists( 'preschool_and_kindergarten_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function preschool_and_kindergarten_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
	/**
	 * Triggered after the opening <body> tag.
    */
	do_action( 'wp_body_open' );
}
endif;