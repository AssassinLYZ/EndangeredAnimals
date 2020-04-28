<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Preschool_and_Kindergarten
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      
    <?php 
    /**
     * Before Page entry content
     * 
     * @hooked preschool_and_kindergarten_post_content_image - 10
     * @hooked preschool_and_kindergarten_post_entry_header  - 20 
    */
    do_action( 'preschool_and_kindergarten_before_post_entry_content' );    
    ?>

	<div class="entry-content" itemprop="text">
		<?php		
    		the_content( sprintf(
    			/* translators: %s: Name of current post. */
    			wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'preschool-and-kindergarten' ), array( 'span' => array( 'class' => array() ) ) ),
    			the_title( '<span class="screen-reader-text">"', '"</span>', false )
    		) );
    	
    		wp_link_pages( array(
    			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'preschool-and-kindergarten' ),
    			'after'  => '</div>',
    		) );
		?>
	</div><!-- .entry-content -->
    
    <footer class="entry-footer">
		<?php preschool_and_kindergarten_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
