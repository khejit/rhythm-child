<?php 

function filter_plugin_updates( $value ) {
	if ( isset( $value ) && is_object( $value ) ) {
		unset($value->response[ 'hello.php' ]);
		unset($value->response[ 'js_composer/js_composer.php' ]);
	}
	/*unset( $value->response['users-ultra/xoousers.php'] );
	return $value;*/
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

wp_register_script( 'child_all', get_stylesheet_directory_uri().'/js/all.js',array('jquery'),TS_THEME_VERSION,true);
wp_register_script( 'regulamin', get_stylesheet_directory_uri().'/js/regulamin.js',array('jquery'),TS_THEME_VERSION,true);

add_action('wp_enqueue_scripts', 'script_fix',20);

function script_fix(){
	wp_dequeue_script('all');
//	wp_deregister_script('all');
	wp_enqueue_script('child_all');
};

function script_regulamin(){
	if(is_page(array(151,'regulamin')))
	wp_enqueue_script('regulamin');
};

add_action('wp_enqueue_scripts', 'script_regulamin',20);

add_action('init', 'mo_add_custom_posts_types');

function mo_add_custom_posts_types (){
	require_once(dirname(__FILE__)."/custom-posts/mo_custom_posts.php");
};

set_post_thumbnail_size( 150, 150, true );

//define ('RHYTHM_CHILD_OPT_NAME', 'rhythm_child_options'); //@TODO I have no fucking clue

//USEFUL FOR DEBUGGING, RIGHT?
function mickey_console_log( $data ){
	echo '<script>';
	echo 'console.log('. json_encode( $data ) .')';
	echo '</script>';
};


// below: show only articles and recipes on main page
function let_only_articles_and_recipes($query)
{
    if (is_home() && $query->is_main_query()) $query->set('post_type', array('artykuly', 'przepisy'));
    return $query;
}
add_filter('pre_get_posts', 'let_only_articles_and_recipes');


//overwrites, fixes and customizations to visual builder and wordpress editor

require_once(dirname(__FILE__)."/editor/shortcodes.php");
require_once(dirname(__FILE__)."/editor/editor.php");


/* QUICK TEST */

add_action('admin_enqueue_scripts', 'enqueue_scripts_styles_admin');
function enqueue_scripts_styles_admin(){
    wp_enqueue_media();
};
