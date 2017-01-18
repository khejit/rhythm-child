<?php
/**
 * Created by PhpStorm.
 * User: khejit
 * Date: 2016-08-01
 * Time: 12:24
 */

//VISUAL BUILDER TWEAKS

$elements_to_remove = array('contact-form-7', 'vc_toggle', 'rs_contact_form', 'rs_client_block', 'vc_toggle', 'vc_tta_tour', 'vc_tta_tabs', 'vc_tta_section', 'vc_btn', 'vc_cta', 'vc_tabs', 'vc_accordion', 'rs_banner_heading', 'rs_countdown_banner', 'rs_banner', 'rs_banner_slider', 'rs_bar', 'rs_image_carousel', 'rs_tilt_image', 'rs_blog_slider', 'rs_blog_magazine_alt', 'rs_blog_magazine', 'rs_blog_carousel');
array_push($elements_to_remove, 'rs_counters', 'rs_contact_details', 'rs_cta_2', 'rs_cta', 'rs_group_button', 'rs_buttons', 'rs_latest_works', 'rs_latest_news', 'rs_google_map', 'rs_fontawesome', 'rs_feature_box', 'rs_el_icon', 'rs_service_box', 'rs_promo_slider', 'rs_pricing_table', 'rs_portfolio_promo', 'rs_parallax', 'rs_logo_slider', 'rs_special_text', 'rs_service_detail', 'rs_slider', 'rs_testimonial', 'rs_tooltip');
array_push($elements_to_remove, 'rs_woo_products', 'vc_wp_tagcloud', 'vc_wp_search', 'rs_wp_follow_us', 'rs_wp_rhythm_categories', 'rs_wp_multi_tabs', 'rs_wp_latest_comments');
array_push($elements_to_remove, 'vc_tta_pageable', 'vc_tta_accordion', 'vc_line_chart', 'vc_round_chart');

foreach ($elements_to_remove as $element){
    vc_remove_element($element);
}

vc_remove_param( 'rs_blockquote', 'cite' );
$params = array(
    'type'        => 'textfield',
    'heading'     => 'Author',
    'param_name'  => 'cite',
    'admin_label' => true,
    'value'       => ''
);
vc_add_param('rs_blockquote', $params);

$params = array(
        'type'        => 'attach_image',
        'heading'     => 'Upload Quote Author Image',
        'param_name'  => 'image',
        'admin_label' => true
);
vc_add_param('rs_blockquote', $params);

vc_map( array(
    'name'          => 'Ciekawostka',
    'base'          => 'rs_ciekawostka',
    'icon'          => 'fa fa-warning',
    'description'   => 'Stwórz ciekawostkę.',
    'params'        => array(
        array(
            'type'        => 'textfield',
            'heading'     => 'Tytuł',
            'param_name'  => 'title'
        ),
        array(
            'type'        => 'textarea',
            'heading'     => 'Treść',
            'param_name'  => 'insides',
            'holder'      => 'div',
            'value'       => ''
        )

    )
) );

vc_map( array(
    'name'          => 'Lista treści',
    'base'          => 'rs_lista_tresci',
    'icon'          => 'fa fa-list',
    'description'   => 'Stwórz listę sekcji artykułu.',
    'params'        => array(
        array(
            'type'        => 'textarea',
            'heading'     => 'Podaj nazwy sekcji oddzielone przecinkami',
            'param_name'  => 'content_list',
            'holder'      => 'div',
            'value'       => ''
        )

    )
) );

vc_map( array(
    'name'          => 'Sekcja',
    'base'          => 'rs_sekcja',
    'icon'          => 'fa fa-indent',
    'description'   => 'Stwórz sekcję.',
    'params'        => array(
        array(
            'type'        => 'textfield',
            'heading'     => 'Nazwa sekcji',
            'param_name'  => 'content_sect',
            'holder'      => 'div',
            'value'       => ''
        )

    )
) );

vc_map( array(
    'name'          => 'Wypunktowanie ozdobne',
    'base'          => 'wypunktowanie_shortcode',
    'icon'          => 'fa fa-th-list',
    'description'   => 'Stwórz wypunktowanie ozdobne.',
    'params'        => array(
        array(
            'type'        => 'textarea_html',
            'heading'     => 'Wprowadź zawartość wypunktowania',
            'param_name'  => 'content',
            'holder'      => 'div',
            'value'       => ''
        )
    )
) );

vc_map( array(
    'name'          => 'Znak plus',
    'base'          => 'plussign_shortcode',
    'icon'          => 'fa fa-plus',
    'description'   => 'Dodaj znak plusa do tekstu lub bez tekstu',
    'params'        => array(
        array(
            'type'        => 'textfield',
            'heading'     => 'Wprowadź tekst który ma znaleźć się obok znaku lub zostaw puste',
            'param_name'  => 'content',
            'holder'      => 'div',
            'value'       => ''
        ),
        array(
            'type'        => 'textfield',
            'heading'     => 'Podaj rozmiar w px, em albo innych jednostkach',
            'param_name'  => 'size'
        )
    )
) );

vc_map( array(
    'name'          => 'Znak minusa',
    'base'          => 'minussign_shortcode',
    'icon'          => 'fa fa-minus',
    'description'   => 'Stwórz wypunktowanie ozdobne.',
    'params'        => array(
        array(
            'type'        => 'textfield',
            'heading'     => 'Wprowadź tekst który ma znaleźć się obok znaku lub zostaw puste',
            'param_name'  => 'content',
            'holder'      => 'div',
            'value'       => ''
        ),
        array(
            'type'        => 'textfield',
            'heading'     => 'Podaj rozmiar w px, em albo innych jednostkach',
            'param_name'  => 'size'
        )
    )
) );

//TINYMCE TWEAKS

add_action('init', 'newsletter_tinymce_addbutton');
function newsletter_tinymce_addbutton() {
    if(!current_user_can('edit_posts') && ! current_user_can('edit_pages')) {
        return;
    }
    if(get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'newsletter_tinymce_addplugin');
        add_filter('mce_buttons', 'newsletter_tinymce_registerbutton');
    }
}

function newsletter_tinymce_registerbutton($buttons) {
    array_push($buttons, 'separator', 'magiczny_buttons');
    return $buttons;
}
function newsletter_tinymce_addplugin($plugin_array) {
    $plugin_array['magiczny_buttons'] = get_stylesheet_directory_uri().'/js/magiczny_buttons.js';
    return $plugin_array;
}