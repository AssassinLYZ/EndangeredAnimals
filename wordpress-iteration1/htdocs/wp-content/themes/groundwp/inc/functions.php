<?php
/**
 * The `groundwp()` function.
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP;

/**
 * Provides access to all available template tags of the theme.
 *
 * When called for the first time, the function will initialize the theme.
 *
 * @return Template_Tags Template tags instance exposing template tag methods.
 */
function groundwp() {
	static $theme = null;

	if ( null === $theme ) {
		$theme = new Theme();
		$theme->initialize();
	}

	return $theme->template_tags();
}

/**
 * Provides access to all available component schemas
 *
 * @return Schema|null Schema instance
 */
function groundwp_schema() {
	static $schema = null;

	if ( null === $schema ) {
		$schema = new Schema();
	}

	return $schema;
}
