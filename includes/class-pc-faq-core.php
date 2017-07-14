<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!function_exists('pc_faq_locate_template')) {

    /**
     * Locate the templates and return the path of the file found
     *
     * @param string $plugin_basename
     * @param string $path
     * @param array  $var
     *
     * @return string
     * @since 2.3
     */
    function pc_faq_locate_template($plugin_basename, $path, $var = NULL) {

        $template_path = '/templates/' . $path;

        $located = locate_template(array(
            $template_path
        ));

        if (!$located) {
            $located = $plugin_basename . '/templates/' . $path;
        }

        return $located;
    }

}

if (!function_exists('pc_faq_get_template')) {

    /**
     * Retrieve a template file.
     *
     * @param string $plugin_basename
     * @param string $path
     * @param mixed  $var
     * @param bool   $return
     *
     * @return string
     * @since 2.3
     */
    function pc_faq_get_template($plugin_basename, $path, $var = null, $return = false) {

        $located = pc_faq_locate_template($plugin_basename, $path, $var);

        if ($var && is_array($var)) {
            extract($var);
        }

        if ($return) {
            ob_start();
        }

        // include file located
        if (file_exists($located)) {
            include( $located );
        }

        if ($return) {
            return ob_get_clean();
        }
    }

}

/**
 * Extract after some string
 * 
 * @param string $string
 * @param string $substring
 * @return String
 * 
 * @since      1.0
 */
function pc_faq_string_after($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false):
        return $string;
    else:
        return(substr($string, $pos + strlen($substring)));
    endif;
}

/**
 * Extract Before some string
 * 
 * @param string $string
 * @param string $substring
 * @return String
 * 
 * @since      1.0
 */
function pc_faq_string_before($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false):
        return $string;
    else:
        return(substr($string, 0, $pos));
    endif;
}

/**
 * Get Custom Categories
 * 
 * @global type $wpdb
 * @return type
 * @since      1.0
 */
function pc_faq_get_custom_categories() {
    global $wpdb;
    $term_table = $wpdb->prefix . 'terms';
    $taxonomy_table = $wpdb->prefix . 'term_taxonomy';
    $pc_query = "SELECT * FROM $term_table WHERE term_id in(SELECT term_id FROM $taxonomy_table WHERE taxonomy = 'fa_category')";
    $pc_categories = $wpdb->get_results($pc_query);
    $category_list = array();
    $category_list[''] = "Show all FAQ's";
    foreach ($pc_categories as $pc_category):
        $pc_slug = $pc_category->slug;
        $pc_name = $pc_category->name;
        $category_list[$pc_slug] = $pc_name;
    endforeach;
    return $category_list;
}

/**
 * Get Custom Categories for VC Addon
 * 
 * @global type $wpdb
 * @return type
 * @since      2.1
 */
function pc_faq_get_custom_categories_for_addon() {
    global $wpdb;
    $term_table = $wpdb->prefix . 'terms';
    $taxonomy_table = $wpdb->prefix . 'term_taxonomy';
    $pc_query = "SELECT * FROM $term_table WHERE term_id in(SELECT term_id FROM $taxonomy_table WHERE taxonomy = 'fa_category')";
    $pc_categories = $wpdb->get_results($pc_query);
    $category_list = array();
    $category_list["Show All FAQ's"] = '';
    foreach ($pc_categories as $pc_category):
        $pc_slug = $pc_category->slug;
        $pc_name = $pc_category->name;
        $category_list[$pc_name] = $pc_slug;
    endforeach;
    return $category_list;
}

/**
 * Get Faq's
 * 
 * @since      1.0
 * @global type $wpdb
 * @param type $query_args
 * @param type $pc_settings
 * @return string
 */
function pc_get_faq($query_args, $pc_settings) {
    global $wpdb;
    $pc_delete = $pc_settings['pc_delete'];
    $pc_faq_main_class = $pc_settings['pc_class'];
    $pc_show_likes = $pc_settings['pc_show_likes'];
    $pc_faq_title = $pc_settings['pc_title'];
    $pc_table_name = $wpdb->prefix . 'pc_like_counts';
    $pc_user_ip = get_faq_client_ip();
    $pc_posts = new WP_Query($query_args);
    $pc_post_html = '<div class="pc_faq_wrapper ' . $pc_faq_main_class . '">';
    if (isset($pc_delete) && ($pc_delete == '' || $pc_delete == null)):
        $pc_post_html .= '<div class="pc_faq_title">';
        $pc_post_html .= $pc_faq_title;
        $pc_post_html .= '</div>';
    endif;
    $pc_faq_count = 0;
    if ($pc_posts->have_posts()):
        while ($pc_posts->have_posts()) : $pc_posts->the_post();
            $pc_id = get_the_ID();
            $pc_count = $wpdb->get_results("SELECT * FROM $pc_table_name WHERE post_id = '$pc_id' AND user_ip = '$pc_user_ip'");
            if ($pc_count):
                $pc_count_class = 'pc_faq_dislike';
            else:
                $pc_count_class = 'pc_faq_like';
            endif;
            $pc_faq_count++;
            $pc_title = get_the_title();
            $pc_content = get_the_content();
            $pc_date = get_the_date('d M Y', $pc_id);
            $pc_author = get_post_meta($pc_id, 'pc_faq_author_name', true);
            if (!$pc_author):
                $pc_author_link = get_author_posts_url(get_the_author_meta('ID'));
                $pc_author = '<a href="' . $pc_author_link . '">' . get_the_author() . '</a>';
            endif;

            $pc_post_like = get_post_meta($pc_id, 'pc_faq_like', true);
            if (!$pc_post_like):
                $pc_post_like = 0;
            endif;
            if ($pc_faq_count == 1):
                $pc_faq_class = 'pc_faq_open';
                $pc_faq_icon_class = 'fa fa-minus-circle';
            else:
                $pc_faq_class = '';
                $pc_faq_icon_class = 'fa fa-plus-circle';
            endif;

            $pc_post_html .= '<div class="pc_faq_single_question ' . $pc_faq_class . '" id="pc_faq_' . $pc_id . '">';
            $pc_post_html .= '<div class="pc_faq_question_wrapper">';
            $pc_post_html .= '<div class="pc_faq_question">' . $pc_title . '</div>';
            $pc_post_html .= '<span class="pc_faq_icon"><i class="' . $pc_faq_icon_class . '"></i></span>';
            $pc_post_html .= '</div>';
            $pc_post_html .= '<div class="pc_faq_answer_wrapper">';
            $pc_post_html .= '<div class="pc_faq_answer">' . $pc_content . '</div>';
            if (isset($pc_delete) && $pc_delete != ''):
                $pc_post_html .= '<div class="pc_faq_delete_wrapper">';
                $pc_post_html .= '<div class="pc_delete_button">'
                        . '<span class="pc_faq_delete" id="' . $pc_id . '">Delete'
                        . '</span>'
                        . '</div>';
                $pc_post_html .= '</div>';
            else:
                $pc_post_html .= '<div class="pc_faq_like_wrapper">';
                if (isset($pc_show_likes) && $pc_show_likes == 'yes'):
                    $pc_post_html .= '<div class="pc_faq_like_button">'
                            . '<span class="count">' . $pc_post_like . '</span>'
                            . '<span class="pc_faq_like_icon ' . $pc_count_class . '" post_id="' . $pc_id . '" pc_type="' . $pc_count_class . '">'
                            . '<i class="fa fa-thumbs-o-up"></i>'
                            . '</span>'
                            . '</div>';
                endif;
                $pc_post_html .= '<div class="pc_faq_date_author_detail">';
                $pc_post_html .= '<span class="pc_faq_date"> ' . $pc_date . ', </span>';
                $pc_post_html .= '<span class="pc_faq_author"> BY  ' . $pc_author . '</span>';
                $pc_post_html .= '</div>';
                $pc_post_html .= '</div>';
            endif;

            $pc_post_html .= '</div>';
            $pc_post_html .= '</div>';
        endwhile;
    else:
        $pc_post_html .= 'There is No FAQ yet.';
    endif;
    wp_reset_postdata();
    $pc_post_html .= '</div>';
    return $pc_post_html;
}

/**
 * Get Woocommerce Product Faq's
 * 
 * @param type $pc_id
 * @param type $pc_delete
 * @param type $pc_faq_class
 * @return type
 * 
 * @since      2.0
 */
function pc_get_product_faq($pc_id, $pc_delete = NULL, $pc_faq_class = NULL) {
    $type = 'pc-faq';
    $query_args = array(
        'post_type' => $type,
        'post_status' => 'publish',
        'numberposts' => 10,
        'meta_query' => array(
            array(
                'key' => 'pc_faq_product_id',
                'value' => $pc_id
            )
        )
    );
    $pc_faq_setting_name = pc_faq_setting_name();
    $pc_faq_settings = get_option($pc_faq_setting_name);
    $pc_faq_title = $pc_faq_settings['pc_faq_product_title'];
    $pc_show_likes = $pc_faq_settings['pc_faq_product_like_show'] ? $pc_faq_settings['pc_faq_product_like_show'] : '';
    $pc_date_show = $pc_faq_settings['pc_date_show'] ? $pc_faq_settings['pc_date_show'] : '';
    $pc_author_show = $pc_faq_settings['pc_author_show'] ? $pc_faq_settings['pc_author_show'] : '';
    $pc_setting_args = array();
    $pc_setting_args['pc_show_likes'] = $pc_show_likes;
    $pc_setting_args['pc_date_show'] = $pc_date_show;
    $pc_setting_args['pc_author_show'] = $pc_author_show;
    $pc_setting_args['pc_title'] = $pc_faq_title;
    $pc_setting_args['pc_delete'] = $pc_delete;
    $pc_setting_args['pc_class'] = $pc_faq_class;
    $pc_setting_args['pc_call_type'] = 'product';
    $pc_setting_args['query_args'] = $query_args;
    
    $pc_faq_theme = $pc_faq_settings['pc_faq_theme'] ? $pc_faq_settings['pc_faq_theme'] : '';
    if (isset($pc_faq_theme) && !empty($pc_faq_theme)):
        $pc_theme_name = $pc_faq_theme;
    else:
        $pc_theme_name = 'theme-a';
    endif;
    return pc_faq_get_template(PC_FAQ_PLUGIN_DIR, $pc_theme_name . '.php', $pc_setting_args, true);
}

/**
 * Get Site Faq's
 * 
 * @param type $query_args
 * @param type $pc_title
 * @return type
 * 
 * @since      2.2
 */
function pc_get_site_faq($query_args, $pc_title) {
    $pc_faq_setting_name = pc_faq_setting_name();
    $pc_faq_settings = get_option($pc_faq_setting_name);
    $pc_show_likes = $pc_faq_settings['pc_like_show'] ? $pc_faq_settings['pc_like_show'] : '';
    $pc_date_show = $pc_faq_settings['pc_date_show'] ? $pc_faq_settings['pc_date_show'] : '';
    $pc_author_show = $pc_faq_settings['pc_author_show'] ? $pc_faq_settings['pc_author_show'] : '';
    $pc_setting_args = array();
    $pc_setting_args['pc_date_show'] = $pc_date_show;
    $pc_setting_args['pc_author_show'] = $pc_author_show;
    $pc_setting_args['pc_show_likes'] = $pc_show_likes;
    $pc_setting_args['pc_title'] = $pc_title;
    $pc_setting_args['pc_delete'] = '';
    $pc_setting_args['pc_class'] = '';
    $pc_setting_args['pc_call_type'] = 'site';
    $pc_setting_args['query_args'] = $query_args;

    $pc_faq_theme = $pc_faq_settings['pc_faq_theme'] ? $pc_faq_settings['pc_faq_theme'] : '';
    if (isset($pc_faq_theme) && !empty($pc_faq_theme)):
        $pc_theme_name = $pc_faq_theme;
    else:
        $pc_theme_name = 'theme-a';
    endif;
    return pc_faq_get_template(PC_FAQ_PLUGIN_DIR, $pc_theme_name . '.php', $pc_setting_args, true);
}

/**
 * Store Likes
 * 
 * @since      1.0
 */
add_action('wp_ajax_pc_faq_store_likes', 'pc_faq_store_likes');

function pc_faq_store_likes() {
    global $wpdb;
    $pc_table_name = $wpdb->prefix . 'pc_like_counts';
    $pc_data = $_POST['data'];
    $pc_type = $pc_data['pc_type'];
    $pc_post_id = $pc_data['pc_post_id'];
    $pc_user_ip = get_faq_client_ip();
    $pc_like_meta = 'pc_faq_like';
    $prev_value = get_post_meta($pc_post_id, $pc_like_meta, true);
    if ($pc_type == 'pc_faq_dislike'):
        $pc_new_value = $prev_value - 1;
        update_post_meta($pc_post_id, $pc_like_meta, $pc_new_value, $prev_value);
        $pc_query = 'DELETE FROM ' . $pc_table_name . ' WHERE post_id = "' . $pc_post_id . '" AND user_ip = "' . $pc_user_ip . '"';
    else:
        $pc_new_value = $prev_value + 1;
        update_post_meta($pc_post_id, $pc_like_meta, $pc_new_value, $prev_value);
        $pc_query = 'INSERT INTO ' . $pc_table_name . ' (user_ip,post_id) VALUES ("' . $pc_user_ip . '","' . $pc_post_id . '") ';
    endif;
    $wpdb->query($pc_query);
    $pc_status = array();
    $pc_status['status'] = 'success';
    $pc_status['count'] = $pc_new_value;
    $pc_status['message'] = 'Setting successfully changed.';
    echo json_encode($pc_status);
    die();
}

function pc_faq_setting_name($call_option = NULL) {
    return 'pc_faq_setting';
}

/**
 * Get Client IP
 * 
 * @return string
 * @since      1.0
 */
function get_faq_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Return Font Style/Weight
 * 
 * @param type $pc_style
 * @return string
 * @since      1.0
 */
function pc_faq_font_face($pc_style) {
    if ($pc_style == 'italic'):
        $pc_font = 'font-style:italic;';
    elseif ($pc_style == 'bold_italic'):
        $pc_font = 'font-style:italic;font-weight:bold;';
    else:
        $pc_font = 'font-weight:' . $pc_style . ';';
    endif;
    return $pc_font;
}

/**
 * @since      2.2
 * @return string
 */
function pc_faq_ask_question_form() {
    $pc_faq_setting_name = pc_faq_setting_name();
    $pc_faq_settings = get_option($pc_faq_setting_name);
    $pc_faq_ask_question_title = isset($pc_faq_settings['pc_faq_ask_question_title']) ? $pc_faq_settings['pc_faq_ask_question_title'] : '';

    $pc_faq_html = isset($_SESSION['pc_faq_add_message']) ? $_SESSION['pc_faq_add_message'] : '';
    $_SESSION['pc_faq_add_message'] = '';
    unset($_SESSION['pc_faq_add_message']);
    $pc_faq_html .= '<div class="pc_faq_ask_question_wrapper">';
    $pc_faq_html .= '<div class="pc_faq_ask_head">';
    $pc_faq_html .= '<span>' . $pc_faq_ask_question_title . '</sapn>';
    $pc_faq_html .= '</div>';
    $pc_faq_html .= '<div class="pc_faq_ask_form_wrapper">';

    $pc_faq_html .= '<form method="post">';

    if (!is_user_logged_in()):
        $pc_faq_html .= '<div class="pc_faq_ask_single_input">';
        $pc_faq_html .= '<label>You Name*</label>';
        $pc_faq_html .= '<input type="text" name="faq_your_name" required/>';
        $pc_faq_html .= '</div>';

        $pc_faq_html .= '<div class="pc_faq_ask_single_input">';
        $pc_faq_html .= '<label>You Email*</label>';
        $pc_faq_html .= '<input type="text" name="faq_your_email" required/>';
        $pc_faq_html .= '</div>';
    endif;

    $pc_faq_html .= '<div class="pc_faq_ask_single_input">';
    $pc_faq_html .= '<label>You Question*</label>';
    $pc_faq_html .= '<textarea type="text" name="faq_your_question" rows="5" required></textarea>';
    $pc_faq_html .= '</div>';

    $pc_faq_html .= '<div class="pc_faq_ask_single_input">';
    $pc_faq_html .= '<input type="submit" name="pc_faq_ask" value="Submit">';
    $pc_faq_html .= '<input type="hidden" name="pc_faq_current_id" value="' . get_the_ID() . '">';
    $pc_faq_html .= wp_nonce_field('pc-faq-ask-question');
    $pc_faq_html .= '</div>';

    $pc_faq_html .= '</form>';

    $pc_faq_html .= '</div>';
    $pc_faq_html .= '</div>';
    return $pc_faq_html;
}
