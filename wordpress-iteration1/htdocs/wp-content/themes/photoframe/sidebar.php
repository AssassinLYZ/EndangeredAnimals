<?php 
if (is_active_sidebar('sidebar')) {
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) : ?> <?php endif;
} ?>