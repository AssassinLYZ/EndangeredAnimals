<?php
/**
 * The template for displaying the footer widgets
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

if ( ! groundwp()->is_footer_widget_area_active() ) {
	return;
}

//groundwp()->print_styles( 'groundwp-widgets' );

?>

<div class="footer-area">

	<?php

	if ( is_active_sidebar( 'footer-1' ) ) {
		dynamic_sidebar( 'footer-1' );
	}

	if ( is_active_sidebar( 'footer-2' ) ) {
		dynamic_sidebar( 'footer-2' );
	}
	if ( is_active_sidebar( 'footer-3' ) ) {
		dynamic_sidebar( 'footer-3' );
	}

	?>

</div><!-- .footer-area -->
