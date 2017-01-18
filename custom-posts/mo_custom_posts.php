<?php

/* ARTICLE TYPE */
//wp_die('no kurwa');
$labels = array(
	'name'               => __( 'Articles', 'rhythm'),
	'singular_name'      => __( 'Article', 'rhythm'),
	'add_new'            => __( 'Add new', 'rhythm'),
	'add_new_item'       => __( 'Add New Article', 'rhythm'),
	'edit_item'          => __( 'Edit Article', 'rhythm'),
	'new_item'           => __( 'New Article', 'rhythm'),
	'all_items'          => __( 'All Articles', 'rhythm'),
	'view_item'          => __( 'View Articles', 'rhythm'),
	'search_items'       => __( 'Search Articles', 'rhythm'),
	'not_found'          => __( 'No Articles found', 'rhythm'),
	'not_found_in_trash' => __( 'No Articles found in the Trash', 'rhythm'),
	'parent_item_colon'  => '',
	'menu_name'          => __( 'Articles', 'rhythm')
);

$args = array(
	'labels'         => $labels,
	'public'        => true,
	'show_ui'       => true,
	'description'   => 'Articles visible on our blog',
	'supports'      => array( 'title', 'thumbnail', 'editor', 'revisions', 'post-formats', 'comments' ),
	'menu_position' => 5,
	'has_archive'   => true,
	//'taxonomies' => array( 'post_tag', 'category '),
	'rewrite'         => array(
		'with_front' => false
	)
);

register_post_type('artykuly', $args);


$labels = array(
	'name'              => __( 'Articles categories', 'rhythm' ),
	'singular_name'     => __( 'Category', 'rhythm' ),
	'search_items'      => __( 'Search categories', 'rhythm' ),
	'all_items'         => __( 'All Categories', 'rhythm' ),
	'parent_item'       => __( 'Parent Category', 'rhythm' ),
	'parent_item_colon' => __( 'Parent Category:', 'rhythm' ),
	'edit_item'         => __( 'Edit Category', 'rhythm' ),
	'update_item'       => __( 'Update Category', 'rhythm' ),
	'add_new_item'      => __( 'Add New Category', 'rhythm' ),
	'new_item_name'     => __( 'New Category Name', 'rhythm' ),
	'menu_name'         => __( 'Categories', 'rhythm' ),
);
$args = array(
	'labels' => $labels,
	'hierarchical' => true,
);
register_taxonomy( 'artykuly-kategoria', 'artykuly', $args );


/* RECIPE TYPE */

$labels = array(
	'name'               => __( 'Recipes', 'rhythm'),
	'singular_name'      => __( 'Recipe', 'rhythm'),
	'add_new'            => __( 'Add new', 'rhythm'),
	'add_new_item'       => __( 'Add New Recipe', 'rhythm'),
	'edit_item'          => __( 'Edit Recipe', 'rhythm'),
	'new_item'           => __( 'New Recipe', 'rhythm'),
	'all_items'          => __( 'All Recipes', 'rhythm'),
	'view_item'          => __( 'View Recipes', 'rhythm'),
	'search_items'       => __( 'Search Recipes', 'rhythm'),
	'not_found'          => __( 'No Recipes found', 'rhythm'),
	'not_found_in_trash' => __( 'No Recipes found in the Trash', 'rhythm'),
	'parent_item_colon'  => '',
	'menu_name'          => __( 'Recipes', 'rhythm')
);

$args = array(
	'labels'         => $labels,
	'public'        => true,
	'show_ui'       => true,
	'description'   => 'Recipes available on our blog',
	'supports'      => array( 'title', 'thumbnail', 'editor', 'revisions', 'post-formats', 'comments' ),
	'menu_position' => 6,
	'has_archive'   => true,
	//'taxonomies' => array( 'post_tag', 'category '),
	'rewrite'         => array(
		'with_front' => false
	)
);

register_post_type('przepisy', $args);


$labels = array(
	'name'              => __( 'Recipes categories', 'rhythm' ),
	'singular_name'     => __( 'Category', 'rhythm' ),
	'search_items'      => __( 'Search categories', 'rhythm' ),
	'all_items'         => __( 'All Categories', 'rhythm' ),
	'parent_item'       => __( 'Parent Category', 'rhythm' ),
	'parent_item_colon' => __( 'Parent Category:', 'rhythm' ),
	'edit_item'         => __( 'Edit Category', 'rhythm' ),
	'update_item'       => __( 'Update Category', 'rhythm' ),
	'add_new_item'      => __( 'Add New Category', 'rhythm' ),
	'new_item_name'     => __( 'New Category Name', 'rhythm' ),
	'menu_name'         => __( 'Categories', 'rhythm' ),
);
$args = array(
	'labels' => $labels,
	'hierarchical' => true,
);
register_taxonomy( 'przepisy-kategoria', 'przepisy', $args );


