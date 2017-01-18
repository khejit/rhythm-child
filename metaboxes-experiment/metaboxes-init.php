<?php

add_action('redux/metaboxes/'.RHYTHM_CHILD_OPT_NAME.'/boxes', 'rhythm_child_add_metaboxes');

function rhythm_child_add_metaboxes($metaboxes) {

	wp_die('kurwa jebana mac');
	// Variable used to store the configuration array of metaboxes
	$metaboxes = array();

	//$metaboxes[] = get_common_metaboxes();
	$metaboxes[] = get_article_metaboxes();
	$metaboxes[] = get_recipe_metaboxes();

	return $metaboxes;
}

function get_article_metaboxes() {

	// Variable used to store the configuration array of sections
	$sections = array();

	// Metabox used to overwrite theme options by page
	require(dirname(__FILE__)."/article/header.php");
	require(dirname(__FILE__)."/article/title-wrapper.php");

	return array(
		'id' => 'ts-page-options',
		'title' => __('Options', 'rhythm'),
		'post_types' => array('artykuly'),
		'position' => 'normal', // normal, advanced, side
		'priority' => 'high', // high, core, default, low
		'sections' => $sections
	);
};

function get_recipe_metaboxes() {

	// Variable used to store the configuration array of sections
	$sections = array();

	// Metabox used to overwrite theme options by page
	require dirname(__FILE__). '/recipe/header.php';
	require dirname(__FILE__).'/recipe/title-wrapper.php';

	return array(
		'id' => 'ts-page-options',
		'title' => __('Options', 'rhythm'),
		'post_types' => array('przepisy'),
		'position' => 'normal', // normal, advanced, side
		'priority' => 'high', // high, core, default, low
		'sections' => $sections
	);
};