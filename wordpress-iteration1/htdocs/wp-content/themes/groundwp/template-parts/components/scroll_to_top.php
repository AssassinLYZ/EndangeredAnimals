<?php
/**
 * Scroll-to-top component template part
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

?>
<div id="<?php echo esc_attr( groundwp_schema()->get_schema( 'scroll_to_top-element_id' ) ); ?>" class="groundwp_scroll_to_top_wrapper" style="<?php groundwp()->scroll_to_top_styles(); ?>">
	<a>
		<img src="<?php echo esc_attr( get_theme_file_uri( '/assets/images/up-arrow.svg' ) ); ?>">
	</a>
</div>
