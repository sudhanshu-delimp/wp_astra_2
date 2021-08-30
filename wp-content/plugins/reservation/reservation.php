<?php
/*
Plugin Name: My Reservation
Description: Add Reservation related attributes
Author: Sudhanshu
*/
// Hook <strong>lc_custom_post_custom_article()</strong> to the init action hook

add_action( 'init', 'crunchify_addons_custom_post_type', 0 );
add_action( 'init', 'crunchify_activity_custom_post_type', 0 );
// The custom function to register a custom article post type
function crunchify_addons_custom_post_type() {
	$labels = array(
		'name'                => __( 'Addons' ),
		'singular_name'       => __( 'Addon'),
		'menu_name'           => __( 'Addons'),
		'parent_item_colon'   => __( 'Parent Addon'),
		'all_items'           => __( 'All Addons'),
		'view_item'           => __( 'View Addon'),
		'add_new_item'        => __( 'Add New Addon'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Addon'),
		'update_item'         => __( 'Update Addon'),
		'search_items'        => __( 'Search Addon'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'Addons'),
		'description'         => __( 'Addons'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor','author', 'thumbnail'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	    'yarpp_support'       => true,
		'menu_icon' => 'dashicons-randomize',
		//'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'Addons', $args );
}

// Let us create Taxonomy for Custom Post Type
// add_action( 'init', 'crunchify_create_Addons_custom_taxonomy', 0 );

// //create a custom taxonomy name it "type" for your posts
// function crunchify_create_Addons_custom_taxonomy() {

//   $labels = array(
//     'name' => _x( 'Types', 'taxonomy general name' ),
//     'singular_name' => _x( 'Type', 'taxonomy singular name' ),
//     'search_items' =>  __( 'Search Types' ),
//     'all_items' => __( 'All Types' ),
//     'parent_item' => __( 'Parent Type' ),
//     'parent_item_colon' => __( 'Parent Type:' ),
//     'edit_item' => __( 'Edit Type' ),
//     'update_item' => __( 'Update Type' ),
//     'add_new_item' => __( 'Add New Type' ),
//     'new_item_name' => __( 'New Type Name' ),
//     'menu_name' => __( 'Types' ),
//   );

//   register_taxonomy('types',array('Addons'), array(
//     'hierarchical' => true,
//     'labels' => $labels,
//     'show_ui' => true,
//     'show_admin_column' => true,
//     'query_var' => true,
//     'rewrite' => array( 'slug' => 'type' ),
//   ));
// }


function crunchify_activity_custom_post_type() {
	$labels = array(
		'name'                => __( 'Activities' ),
		'singular_name'       => __( 'Activity'),
		'menu_name'           => __( 'Activities'),
		'parent_item_colon'   => __( 'Parent Activity'),
		'all_items'           => __( 'All Activities'),
		'view_item'           => __( 'View Activity'),
		'add_new_item'        => __( 'Add New Activity'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Activity'),
		'update_item'         => __( 'Update Activity'),
		'search_items'        => __( 'Search Activity'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'Activities'),
		'description'         => __( 'Activities'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor','author', 'thumbnail'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	  'yarpp_support'       => true,
	  'menu_icon' => 'dashicons-randomize',
		//'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'Activities', $args );
}

// Let us create Taxonomy for Custom Post Type
// add_action( 'init', 'crunchify_create_Activities_custom_taxonomy', 0 );

// //create a custom taxonomy name it "type" for your posts
// function crunchify_create_Activities_custom_taxonomy() {

//   $labels = array(
//     'name' => _x( 'Types', 'taxonomy general name' ),
//     'singular_name' => _x( 'Type', 'taxonomy singular name' ),
//     'search_items' =>  __( 'Search Types' ),
//     'all_items' => __( 'All Types' ),
//     'parent_item' => __( 'Parent Type' ),
//     'parent_item_colon' => __( 'Parent Type:' ),
//     'edit_item' => __( 'Edit Type' ),
//     'update_item' => __( 'Update Type' ),
//     'add_new_item' => __( 'Add New Type' ),
//     'new_item_name' => __( 'New Type Name' ),
//     'menu_name' => __( 'Types' ),
//   );

//   register_taxonomy('types',array('Activities'), array(
//     'hierarchical' => true,
//     'labels' => $labels,
//     'show_ui' => true,
//     'show_admin_column' => true,
//     'query_var' => true,
//     'rewrite' => array( 'slug' => 'type' ),
//   ));
// }