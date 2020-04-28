<?php
/**
 * Template part for displaying call-to-action button
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

if ( groundwp()->call_to_action_enable() ) :
	?>
	<div class="wp-block-button call-to-action">
		<a class="wp-block-button__link call-to-action-anchor"
		   href="<?php echo esc_attr( groundwp()->call_to_action_url() ); ?>">
			<?php echo esc_html( groundwp()->call_to_action_text() ); ?>
		</a>
	</div>
	<?php
endif;

