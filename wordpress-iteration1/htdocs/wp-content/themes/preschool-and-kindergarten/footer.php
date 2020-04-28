<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Preschool_and_Kindergarten
 */

 /**
     * After Content
     * 
     * @hooked preschool_and_kindergarten_content_end - 20
    */
    do_action( 'preschool_and_kindergarten_after_content' );
    
    /**
     * preschool_and_kindergarten Footer
     * 
     * @hooked preschool_and_kindergarten_footer_start  - 10
     * @hooked preschool_and_kindergarten_footer_top    - 20
     * @hooked preschool_and_kindergarten_footer_bottom - 30
     * @hooked preschool_and_kindergarten_footer_end    - 40
    */
    do_action( 'preschool_and_kindergarten_footer' );
    
    /**
     * After Footer
     * 
     * @hooked preschool_and_kindergarten_page_end - 20
    */
    do_action( 'preschool_and_kindergarten_after_footer' );
   
    wp_footer(); ?>

</body>
</html>