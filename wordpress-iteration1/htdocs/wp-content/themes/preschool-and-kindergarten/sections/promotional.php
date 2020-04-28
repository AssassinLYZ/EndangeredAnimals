<?php 
/**
 * Promotional Section
 * 
 * @package Preschool_and_Kindergarten
*/ 
    $section_title = get_theme_mod( 'preschool_and_kindergarten_promotional_section_title' );
    $description   = get_theme_mod( 'preschool_and_kindergarten_promotional_section_description' );
    $button_label  = get_theme_mod( 'preschool_and_kindergarten_button_label' );
    $button_link   = get_theme_mod( 'preschool_and_kindergarten_button_link', __( 'View Details', 'preschool-and-kindergarten' ) ); 
?>
<div class="promotional-block">

    <div class="container">
        
        <?php 
        preschool_and_kindergarten_get_section_header( $section_title, $description );
        
        if( $button_link ){ ?>
		    <a href="<?php echo esc_url( $button_link ); ?>" class="btn-detail"><?php echo esc_html( $button_label ); ?></a>
	    <?php 
	    } ?>
	
	</div>
</div>