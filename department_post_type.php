<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/** Start code HERE **/

function departments_post_type() {

	$labels = array(
		'name' => __('Departments', DEPARTMENTS_TXT_DOMAIN),
		'singular_name' => __('Department', DEPARTMENTS_TXT_DOMAIN),
		'menu_name' => __('Departments', DEPARTMENTS_TXT_DOMAIN),
		'name_admin_bar' => __('Department', DEPARTMENTS_TXT_DOMAIN),
		'archives' => __('Departments', DEPARTMENTS_TXT_DOMAIN),
		'attributes' => '',
		'parent_item_colon' => '',
		'all_items' => __('View all department', DEPARTMENTS_TXT_DOMAIN),
		'add_new_item' => __('Create new department', DEPARTMENTS_TXT_DOMAIN),
		'add_new' => __('New department', DEPARTMENTS_TXT_DOMAIN),
		'new_item' => __('New department', DEPARTMENTS_TXT_DOMAIN),
		'edit_item' => __('Edit department', DEPARTMENTS_TXT_DOMAIN),
		'update_item' => __('Update department', DEPARTMENTS_TXT_DOMAIN),
		'view_item' => __('View Department', DEPARTMENTS_TXT_DOMAIN),
		'view_items' => __('View all departments', DEPARTMENTS_TXT_DOMAIN),
		'search_items' => __('search departments', DEPARTMENTS_TXT_DOMAIN),
		'not_found' => __('departments not_found', DEPARTMENTS_TXT_DOMAIN),
		'not_found_in_trash' => __('departments not_found_in_trash', DEPARTMENTS_TXT_DOMAIN),
		'featured_image' => __('Featured image', DEPARTMENTS_TXT_DOMAIN),
		'set_featured_image' => __('Set featured image', DEPARTMENTS_TXT_DOMAIN),
		'remove_featured_image' => __('Remove featured image', DEPARTMENTS_TXT_DOMAIN),
		'use_featured_image' => __('Use featured image', DEPARTMENTS_TXT_DOMAIN),
		'insert_into_item' => '',
		'uploaded_to_this_item' => '',
		'items_list' => '',
		'items_list_navigation' => '',
		'filter_items_list' => '',
	);
	$rewrite = array(
		'slug' => 'departments',
		'with_front' => true,
		'pages' => true,
		'feeds' => false,
	);
	$args = array(
		'label' => __('Departments ....', DEPARTMENTS_TXT_DOMAIN),
		'description' => __('departments ...', DEPARTMENTS_TXT_DOMAIN),
		'labels' => $labels,
		'supports' => array( 'title','thumbnail','editor', DEPARTMENTS_TXT_DOMAIN),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,		
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'rewrite' => $rewrite,
		'capability_type' => 'page',
		'show_in_rest' => true,
	);
	register_post_type(DEPARTMENTS_POST_TYPE, $args);
}

add_action( 'init', 'departments_post_type', 0 );