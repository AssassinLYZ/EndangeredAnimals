<?php

function ilovewp_option_defaults() {
    $defaults = array(

        // Copyright
        /* translators: copyright line in footer. 1: year 2: blogname */
        'footer-text'                         => sprintf( esc_html__( 'Copyright &copy; %1$s %2$s.', 'photoframe' ), date( 'Y' ), get_bloginfo( 'name' ) ),
    );

    return $defaults;
}

function ilovewp_get_default( $option ) {
    $defaults = ilovewp_option_defaults();
    $default  = ( isset( $defaults[ $option ] ) ) ? $defaults[ $option ] : false;

    return $default;
}
