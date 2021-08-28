<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
 //echo get_stylesheet_directory_uri() . '/style.css';
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	// wp_enqueue_style( 'astra-child-ie-editor-css', get_stylesheet_directory_uri() . '/assets/css/ie-editor.css');
	// wp_enqueue_style( 'astra-child-ie-css', get_stylesheet_directory_uri() . '/assets/css/ie.css');

	// wp_enqueue_style( 'astra-child-custom-color-overrides-css', get_stylesheet_directory_uri() . '/assets/css/custom-color-overrides.css');
	// wp_enqueue_style( 'astra-child-print-css', get_stylesheet_directory_uri() . '/assets/css/print.css');
	// wp_enqueue_style( 'astra-child-style-dark-mode-rtl-css', get_stylesheet_directory_uri() . '/assets/css/style-dark-mode-rtl.css');
	// wp_enqueue_style( 'astra-child-style-dark-mode-css', get_stylesheet_directory_uri() . '/assets/css/style-dark-mode.css');
	// wp_enqueue_style( 'astra-child-style-editor-customizer-css', get_stylesheet_directory_uri() . '/assets/css/style-editor-customizer.css');
	// wp_enqueue_style( 'astra-child-style-editor-css', get_stylesheet_directory_uri() . '/assets/css/style-editor.css');

	//wp_enqueue_script( 'astra-child-script-bootstrap.min', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js');
	//wp_enqueue_script( 'astra-child-script-customize', get_stylesheet_directory_uri() . '/assets/js/customize.js');
	//wp_enqueue_script( 'astra-child-script-customize-helpers', get_stylesheet_directory_uri() . '/assets/js/customize-helpers.js');

	//wp_enqueue_script( 'astra-child-script-customize-preview', get_stylesheet_directory_uri() . '/assets/js/customize-preview.js');
	//wp_enqueue_script( 'astra-child-script-dark-mode-toggler', get_stylesheet_directory_uri() . '/assets/js/dark-mode-toggler.js');
	//wp_enqueue_script( 'astra-child-script-editor', get_stylesheet_directory_uri() . '/assets/js/editor.js');
	//wp_enqueue_script( 'astra-child-script-editor-dark-mode-support', get_stylesheet_directory_uri() . '/assets/js/editor-dark-mode-support.js');
	// wp_enqueue_script( 'astra-child-script-hello-editor', get_stylesheet_directory_uri() . '/assets/js/hello-editor.js');
	// wp_enqueue_script( 'astra-child-script-hello-editor.min', get_stylesheet_directory_uri() . '/assets/js/hello-editor.min.js');
	//wp_enqueue_script( 'astra-child-script-hello-frontend', get_stylesheet_directory_uri() . '/assets/js/hello-frontend.js');
	//wp_enqueue_script( 'astra-child-script-hello-frontend.min', get_stylesheet_directory_uri() . '/assets/js/hello-frontend.min.js');
	//wp_enqueue_script( 'astra-child-script-jquery.3.3.1.min', get_stylesheet_directory_uri() . '/assets/js/jquery.3.3.1.min.js');
	//wp_enqueue_script( 'astra-child-script-owl.carousel.min', get_stylesheet_directory_uri() . '/assets/js/owl.carousel.min.js');
	//wp_enqueue_script( 'astra-child-script-palette-colorpicker', get_stylesheet_directory_uri() . '/assets/js/palette-colorpicker.js');
	//wp_enqueue_script( 'astra-child-script-polyfills', get_stylesheet_directory_uri() . '/assets/js/polyfills.js');
	//wp_enqueue_script( 'astra-child-script-popper.min', get_stylesheet_directory_uri() . '/assets/js/popper.min.js');
	//wp_enqueue_script( 'astra-child-script-primary-navigation', get_stylesheet_directory_uri() . '/assets/js/primary-navigation.js');
	//wp_enqueue_script( 'astra-child-script-responsive-embeds', get_stylesheet_directory_uri() . '/assets/js/responsive-embeds.js');
	//wp_enqueue_script( 'astra-child-script-skip-link-focus-fix', get_stylesheet_directory_uri() . '/assets/js/skip-link-focus-fix.js');

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/*pre developer code*/
function product_init() {
    // set up product labels
    $labels = array(
        'name' => 'Reservations',
        'singular_name' => 'Reservation',
        'add_new' => 'Add New Reservation',
        'add_new_item' => 'Add New Reservation',
        'edit_item' => 'Edit Reservation',
        'new_item' => 'New Reservation',
        'all_items' => 'All Reservation',
        'view_item' => 'View Reservation',
        'search_items' => 'Search Reservations',
        'not_found' =>  'No Reservations Found',
        'not_found_in_trash' => 'No Reservations found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Reservation',
    );

    // register post type
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'product'),
        'query_var' => true,
        'menu_icon' => 'dashicons-randomize',
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes'
        )
    );
    register_post_type( 'product', $args );

    
    register_taxonomy('product_category', 'product', array('hierarchical' => true, 'label' => 'Reservation Category', 'query_var' => true, 'rewrite' => array( 'slug' => 'product-category' )));
}
add_action( 'init', 'product_init' );
//dd_filter('acf/settings/remove_wp_meta_box', '__return_false');
