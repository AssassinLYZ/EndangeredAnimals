<?php
/**
 * Template part for displaying a pagination
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

the_posts_pagination(
	[
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'groundwp' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'groundwp' ),
		'screen_reader_text' => __( 'Page navigation', 'groundwp' ),
	]
);
