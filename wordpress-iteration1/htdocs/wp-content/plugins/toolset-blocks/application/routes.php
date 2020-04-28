<?php
/*
 * Routes used by Views.
 *
 * This should be the only file which loads the dependency injections container.
 */
namespace OTGS\Toolset\Views;


add_action( 'init', function() {
	$dic = apply_filters( 'toolset_common_es_dic', false );

	// Common ES Blocks Styles - Add Block Factory for blocks of Views.
	add_filter( 'toolset_common_es_block_factories', function( $block_factories ) use ( $dic ) {
		if( $block_factory = $dic->make( 'OTGS\Toolset\Views\Models\Block\Style\Block\Factory' ) ) {
			$block_factories[] = $block_factory;
		}
		return $block_factories;
	}, 10, 1 );
}, 1 );
