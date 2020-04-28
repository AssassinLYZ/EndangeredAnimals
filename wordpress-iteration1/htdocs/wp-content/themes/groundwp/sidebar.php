<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

if ( ! groundwp()->is_primary_sidebar_active() || 'sidebar-none' === groundwp()->get_sidebar_style() ) {
	return;
}

//groundwp()->print_styles( 'groundwp-sidebar', 'groundwp-widgets' );


?>
<aside id="secondary" class="primary-sidebar widget-area">
	<?php groundwp()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
