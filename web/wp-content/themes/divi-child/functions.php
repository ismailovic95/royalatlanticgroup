<?php
function dt_enqueue_styles() {
    $parenthandle = 'divi-style'; 
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(), // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') 
    );
}
add_action( 'wp_enqueue_scripts', 'dt_enqueue_styles' );
// add_filter( 'jetpack_offline_mode', '__return_true' );
// add_filter( 'jetpack_is_staging_site', '__return_false' );

// Hide number of Theme Customizer cart items if cart is empty
add_action( 'wp_footer', function() {
    if ( WC()->cart->is_empty() ) {
        echo '<style type="text/css">.et_pb_menu__icon span:before { font-size:12px !important; } .et_pb_menu__icon span { font-size:0px !important;background-color:unset !important; }</style>';
    }
});
