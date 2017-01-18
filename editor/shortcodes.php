<?php
/**
 * Created by PhpStorm.
 * User: khejit
 * Date: 2016-08-01
 * Time: 14:45
 */

function overwrite_shortcodes(){

    /* BLOCKQUOTE */

    function fjarrett_get_attachment_id_by_url( $url ) {
        // Split the $url into two parts with the wp-content directory as the separator
        $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
        // Get the host of the current site and the host of the $url, ignoring www
        $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
        $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
        // Return nothing if there aren't any $url parts or if the current host and $url host do not match
        if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
            return;
        }
        // Now we're going to quickly search the DB for any attachment GUID with a partial path match
        // Example: /uploads/2013/05/test-image.jpg
        global $wpdb;
        $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );
        // Returns null if no attachment is found
        return $attachment[0];
    }

    function generate_blockquote_output($atts){

        $id = ($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';
        $class = ($atts['class']) ? ' ' . sanitize_html_classes($atts['class']) : '';
        $el_text_style = ($atts['el_text_style']) ? $atts['el_text_style'] : '';
        $el_cite_style = ($atts['el_cite_style']) ? $atts['el_cite_style'] : '';

        $output = '<blockquote class="magiczny-blockquote ' . $class . '" ' . $id . '>';
        if(isset($atts['img_source'][0])){
            $output .= '<img src="'.esc_url($atts['img_source'][0]).'" alt="" />';
        } else if(isset($atts['image_link'])){
            $image_link = wp_get_attachment_image_src( fjarrett_get_attachment_id_by_url($atts['image_link']), 'medium');
            $output .= '<img src="'.$image_link[0].'" alt="" />';
        }
        //$output .= '<img src="'.get_stylesheet_directory_uri().'/img/quote-mark.png'.'" />';
        $output .= '<p ' . $el_text_style . '>' . do_shortcode(wp_kses_data($atts['content'])) . '</p>';
        $output .= ($atts['cite']) ? '<footer ' . $el_cite_style . '>' . esc_html($atts['cite']) . '</footer>' : '';
        $output .= '</blockquote>';

        return $output;
    };

    function rs_blockquote_with_image($atts, $content = '', $id = ''){
        extract(shortcode_atts(array(
            'id' => '',
            'class' => '',
            'cite' => '',
            'image' => '',

            //color
            'text_color' => '',
            'cite_color' => '',

            //size
            'text_size' => '',
            'cite_size' => '',

        ), $atts));

        /*$id = ($id) ? ' id="' . esc_attr($id) . '"' : '';
        $class = ($class) ? ' ' . sanitize_html_classes($class) : ''; */
        if(is_numeric($image)&& !empty($image)){
            $img_source = wp_get_attachment_image_src($image, 'medium');
        }


        $text_size = ($text_size) ? 'font-size:' . $text_size . ';' : '';
        $cite_size = ($text_size) ? 'font-size:' . $cite_size . ';' : '';
        $text_color = ($text_color) ? 'color:' . $text_color . ';' : '';
        $cite_color = ($text_color) ? 'color:' . $cite_color . ';' : '';

        $el_text_style = ($text_color || $text_size) ? 'style="' . esc_attr($text_size . $text_color) . '"' : '';
        $el_cite_style = ($cite_color || $cite_size) ? 'style="' . esc_attr($cite_size . $cite_color) . '"' : '';

        $atts = array(
            'id' => $id,
            'class' => $class,
            'cite' => $cite,
            'img_source' => $img_source,
            'content' => $content,

            'el_text_size' => $el_text_style,
            'el_cite_size' => $el_cite_style,
        );

        /* $output = '<blockquote class="magiczny-blockquote ' . $class . '" ' . $id . '>';
        if(isset($img_source[0])){
            $output .= '<img src="'.esc_url($img_source[0]).'" alt="" />';
        }
        $output .= '<img src="'.get_stylesheet_directory_uri().'/img/quote-mark.png'.'" />';
        $output .= '<p ' . $el_text_style . '>' . do_shortcode(wp_kses_data($content)) . '</p>';
        $output .= ($cite) ? '<footer ' . $el_cite_style . '>' . esc_html($cite) . '</footer>' : '';
        $output .= '</blockquote>'; */

        $output = generate_blockquote_output($atts);

        return $output;
    };

    remove_shortcode('rs_blockquote');
    add_shortcode('rs_blockquote','rs_blockquote_with_image');


    /* SPECIAL SHORTCODE FOR BLOCKQUOTE FOR TINYMCE */
    function rs_blockquote_for_tinymce($atts, $content = ''){
        extract(shortcode_atts(array(
            'cite' => '',
            'image_link' => '',
            'content' => ''
        ), $atts ));

        /* $output = '<blockquote class="magiczny-blockquote">';
        if(isset($image_link)){
            $image_link = explode('.jpg', $image_link)[0].'-300x300.jpg';
            $output .= '<img src="'.$image_link.'" alt="" />';
        }
        $output .= '<img src="'.get_stylesheet_directory_uri().'/img/shapes.png'.'" />';
        $output .= '<p>' . do_shortcode(wp_kses_data($content)) . '</p>';
        $output .= ($cite) ? '<footer>' . esc_html($cite) . '</footer>' : '';
        $output .= '</blockquote>'; */

        $atts = array(
            'cite' => $cite,
            'image_link' => $image_link,
            'content' => $content
        );

        $output = generate_blockquote_output($atts);

        return $output;
    };

    add_shortcode('rs_blockquote_for_tinymce', 'rs_blockquote_for_tinymce');


    /* VIDEO */

    function rs_media_400px( $atts, $content = '', $id = '' ) {

        extract( shortcode_atts( array(
            'id'                  => '',
            'class'               => '',
            'media_type'          => 'vimeo',
            'v_id'                => '',
            'y_url'               => '',
            's_id'                => '',
            'width'               => '',
            'height'              => ''
        ), $atts ) );

        $id    = ( $id ) ? ' id="'. esc_attr($id) .'"' : '';
        $class = ( $class ) ? ' '. sanitize_html_classes($class) : '';

        $width      = ( $width ) ? esc_attr($width) : '100%';
        $height      = ( $height ) ? esc_attr($height) : '100%';


        $output = '';
        switch ($media_type) {
            case 'vimeo':
                $output .=  '<div class="mb-xs-40">';
                $output .=  '<div '.$id.' class="video'.$class.'">';
                $output .=  '<iframe src="http://player.vimeo.com/video/'.esc_html($v_id).'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="1170" height="658" allowfullscreen></iframe>';
                $output .=  '</div>';
                $output .=  '</div>';
                break;

            case 'youtube':
                $output .=  '<div class="mb-xs-40">';
                $output .=  '<div'.$id.' class="video'.$class.'">';
                $output .=  '<iframe width="'.$width.'" height="'.$height.'" src="'.esc_url($y_url).'" allowfullscreen></iframe>';
                $output .=  '</div>';
                $output .=  '</div>';
                break;

            default:
                $output .=  '<iframe width="'.$width.'" height="'.$height.'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.esc_html($s_id).'&amp;color=111111&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>';
                break;
        }
        wp_enqueue_script( 'jquery-fitvids' );
        return $output;
    } //TODO modify video with preceding function

    //remove_shortcode('rs_media');
    //add_shortcode('rs_media', 'rs_media_400px');


    /* CIEKAWOSTKA */
    function rs_ciekawostka($atts, $content = ''){
        extract(shortcode_atts(array(
            'title' => '',
            'insides' => ''
        ), $atts ));

        $output = '<div class="magiczny-callout">';
        $output .= '<h4>'.$title.'</h4>';
        $output .= '<p>'.do_shortcode(wp_kses_data($insides)).'</p>';
        $output .= '</div>';

        return $output;
    };

    add_shortcode('rs_ciekawostka', 'rs_ciekawostka');


    /* lista treści */
    function rs_lista_tresci($atts, $content = ''){
        extract(shortcode_atts(array(
            'content_list' => ''
        ), $atts ));

        //$content = preg_replace('/\s+/', '', $content);
        $content_list = explode(',', $content_list);

        //if(!empty($content_list)){
            $output = '<ul class="magiczny-spis-tresci">';
            foreach ($content_list as $value){
                $output .= '<li><a href="#'.preg_replace('/\s+/', '-',trim((strtolower($value)))).'">'.trim($value).'</a></li>';
            };
            $output .= '</ul>';
        //}

        return $output;
    }

    add_shortcode('rs_lista_tresci', 'rs_lista_tresci');


    /* sekcja */
    function rs_sekcja($atts, $content = ''){
        extract(shortcode_atts(array(
            'content_sect' => ''
        ), $atts ));

        $output = '<span class="magiczny-section-span" id="'.preg_replace('/\s+/', '-',trim((strtolower($content_sect)))).'"></span>';

        return $output;
    }

    add_shortcode('rs_sekcja', 'rs_sekcja');


    /* wypunktowanie */
    function wypunktowanie_shortcode( $atts, $content = null ) {
        return '<div class="magiczny_ozdobna_lista">' . do_shortcode($content) . '</div>';
    }
    add_shortcode('wypunktowanie_shortcode', 'wypunktowanie_shortcode');


    /* plus & minus */
    function plussign_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'size' => ''
        ), $atts ));
        if(!empty($size)) {
            $size = ' style="font-size:'.$size.'"';
        } else {
            $size= '';
        };
        return '<span class="plus-sign"'.$size.'>' . do_shortcode($content) . '</span>';
    }
    add_shortcode('plussign_shortcode', 'plussign_shortcode');

    function minussign_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'size' => ''
        ), $atts ));
        if(!empty($size)) {
            $size = ' style="font-size:'.$size.'"';
        } else {
            $size= '';
        };
        return '<span class="minus-sign"'.$size.'>' . do_shortcode($content) . '</span>';
    }
    add_shortcode('minussign_shortcode', 'minussign_shortcode');

};

add_action( 'wp_loaded', 'overwrite_shortcodes' );