<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Preschool_and_Kindergarten
 */
$sidebar_layout = preschool_and_kindergarten_sidebar_layout();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php 
    /**
     * Before Page entry content
     * 
     * @hooked preschool_and_kindergarten_page_content_image 
    */
    do_action( 'preschool_and_kindergarten_before_page_entry_content' );    
    ?>
    
	<div class="entry-content" itemprop="text">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'preschool-and-kindergarten' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		preschool_and_kindergarten_entry_footer();
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
