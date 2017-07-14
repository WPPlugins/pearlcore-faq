<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Save Settings
 * 
 * @since      1.0
 */
add_action('wp_ajax_pc_faq_save_setting', 'pc_faq_save_setting');

function pc_faq_save_setting() {
    $pc_data = $_POST['data'];
    $pc_form_data = $pc_data['pc_form_data'];
    $pc_setting_name = pc_faq_setting_name();
    $pc_store_data = array();
    if ($pc_form_data):
        foreach ($pc_form_data as $pc_data):
            $pc_field_name = $pc_data['name'];
            $pc_field_value = str_replace('"', "'", trim($pc_data['value']));
            $pc_id = pc_faq_string_before(pc_faq_string_after($pc_field_name, '['), ']');
            $pc_store_data[$pc_id] = $pc_field_value;
        endforeach;
    endif;
    if (get_option($pc_setting_name)):
        update_option($pc_setting_name, $pc_store_data);
    else:
        add_option($pc_setting_name, $pc_store_data);
    endif;
    pc_faq_css();
    $pc_status = array();
    $pc_status['status'] = 'success';
    $pc_status['message'] = 'Setting successfully changed.';
    echo json_encode($pc_status);
    die();
}

/**
 * Create Plugin Styling
 * 
 * @since      1.0
 */
function pc_faq_css() {
    $pc_user_style = '';
    $pc_setting_name = pc_faq_setting_name();
    $pc_all_setting = get_option($pc_setting_name);
    $pc_title_style_size = isset($pc_all_setting['pc_title_style_size']) ? $pc_all_setting['pc_title_style_size'] : '';
    $pc_title_style_color = isset($pc_all_setting['pc_title_style_color']) ? $pc_all_setting['pc_title_style_color'] : '';
    $pc_title_style_style = isset($pc_all_setting['pc_title_style_style']) ? $pc_all_setting['pc_title_style_style'] : '';
    $pc_title_style_face = isset($pc_all_setting['pc_title_style_face']) ? $pc_all_setting['pc_title_style_face'] : '';
    $pc_user_style .= '.pc_faq_wrapper .pc_faq_title{'
            . 'font-size:' . $pc_title_style_size . ';'
            . 'font-family:' . $pc_title_style_face . ';'
            . 'color:' . $pc_title_style_color . ';'
            . pc_faq_font_face($pc_title_style_style)
            . '}';
    $pc_question_bg = isset($pc_all_setting['pc_question_bg']) ? $pc_all_setting['pc_question_bg'] : '';
    $pc_user_style .= '.pc_faq_question_wrapper{'
            . 'background-color:' . $pc_question_bg . ';'
            . '}';

    $pc_question_style_size = isset($pc_all_setting['pc_question_style_size']) ? $pc_all_setting['pc_question_style_size'] : '';
    $pc_question_style_color = isset($pc_all_setting['pc_question_style_color']) ? $pc_all_setting['pc_question_style_color'] : '';
    $pc_question_style_style = isset($pc_all_setting['pc_question_style_style']) ? $pc_all_setting['pc_question_style_style'] : '';
    $pc_question_style_face = isset($pc_all_setting['pc_question_style_face']) ? $pc_all_setting['pc_question_style_face'] : '';
    $pc_user_style .= '.pc_faq_question_wrapper .pc_faq_question,.pc_faq_wrapper .cd-faq-trigger{'
            . 'font-size:' . $pc_question_style_size . ';'
            . 'font-family:' . $pc_question_style_face . ';'
            . 'color:' . $pc_question_style_color . ';'
            . pc_faq_font_face($pc_question_style_style)
            . '}';
    $pc_question_icon_color = isset($pc_all_setting['pc_question_icon_color']) ? $pc_all_setting['pc_question_icon_color'] : '';
    $pc_user_style .= '.pc_faq_question_wrapper .pc_faq_icon i{'
            . 'color:' . $pc_question_icon_color . ';'
            . '}';

    $pc_count_style_size = isset($pc_all_setting['pc_count_style_size']) ? $pc_all_setting['pc_count_style_size'] : '';
    $pc_count_style_color = isset($pc_all_setting['pc_count_style_color']) ? $pc_all_setting['pc_count_style_color'] : '';
    $pc_count_style_style = isset($pc_all_setting['pc_count_style_style']) ? $pc_all_setting['pc_count_style_style'] : '';
    $pc_count_style_face = isset($pc_all_setting['pc_count_style_face']) ? $pc_all_setting['pc_count_style_face'] : '';
    $pc_user_style .= '.pc_faq_wrapper .pc_faq_like_button .count{'
            . 'font-size:' . $pc_count_style_size . ';'
            . 'font-family:' . $pc_count_style_face . ';'
            . 'color:' . $pc_count_style_color . ';'
            . pc_faq_font_face($pc_count_style_style)
            . '}';

    $pc_like_icon_color = isset($pc_all_setting['pc_like_icon_color']) ? $pc_all_setting['pc_like_icon_color'] : '';
    $pc_user_style .= '.pc_faq_like_icon.pc_faq_like .fa-thumbs-o-up{'
            . 'color:' . $pc_like_icon_color . ';'
            . '}';

    $pc_after_like_icon_color = isset($pc_all_setting['pc_after_like_icon_color']) ? $pc_all_setting['pc_after_like_icon_color'] : '';
    $pc_user_style .= '.pc_faq_like_icon.pc_faq_dislike .fa-thumbs-o-up{'
            . 'color:' . $pc_after_like_icon_color . ';'
            . '}';

    /**
     * Products FAQ's
     */
    $pc_faq_product_title_style_size = isset($pc_all_setting['pc_faq_product_title_style_size']) ? $pc_all_setting['pc_faq_product_title_style_size'] : '';
    $pc_faq_product_title_style_color = isset($pc_all_setting['pc_faq_product_title_style_color']) ? $pc_all_setting['pc_faq_product_title_style_color'] : '';
    $pc_faq_product_title_style_style = isset($pc_all_setting['pc_faq_product_title_style_style']) ? $pc_all_setting['pc_faq_product_title_style_style'] : '';
    $pc_faq_product_title_style_face = isset($pc_all_setting['pc_faq_product_title_style_face']) ? $pc_all_setting['pc_faq_product_title_style_face'] : '';

    $pc_user_style .= '.pc_faq_product_wrapper .pc_faq_title{'
            . 'font-size:' . $pc_faq_product_title_style_size . ';'
            . 'font-family:' . $pc_faq_product_title_style_face . ';'
            . 'color:' . $pc_faq_product_title_style_color . ';'
            . pc_faq_font_face($pc_faq_product_title_style_style)
            . '}';
    
    /**
     * Ask Question
     */
    $pc_faq_ask_question_title_style_size = isset($pc_all_setting['pc_faq_ask_question_title_style_size']) ? $pc_all_setting['pc_faq_ask_question_title_style_size'] : '';
    $pc_faq_ask_question_title_style_color = isset($pc_all_setting['pc_faq_ask_question_title_style_color']) ? $pc_all_setting['pc_faq_ask_question_title_style_color'] : '';
    $pc_faq_ask_question_title_style_style = isset($pc_all_setting['pc_faq_ask_question_title_style_style']) ? $pc_all_setting['pc_faq_ask_question_title_style_style'] : '';
    $pc_faq_ask_question_title_style_face = isset($pc_all_setting['pc_faq_ask_question_title_style_face']) ? $pc_all_setting['pc_faq_ask_question_title_style_face'] : '';

    $pc_user_style .= '.pc_faq_ask_question_wrapper .pc_faq_ask_head span{'
            . 'font-size:' . $pc_faq_ask_question_title_style_size . ';'
            . 'font-family:' . $pc_faq_ask_question_title_style_face . ';'
            . 'color:' . $pc_faq_ask_question_title_style_color . ';'
            . pc_faq_font_face($pc_faq_ask_question_title_style_style)
            . '}';
    
    $pc_date_style_size = isset($pc_all_setting['pc_date_style_size']) ? $pc_all_setting['pc_date_style_size'] : '';
    $pc_date_style_color = isset($pc_all_setting['pc_date_style_color']) ? $pc_all_setting['pc_date_style_color'] : '';
    $pc_date_style_style = isset($pc_all_setting['pc_date_style_style']) ? $pc_all_setting['pc_date_style_style'] : '';
    $pc_date_style_face = isset($pc_all_setting['pc_date_style_face']) ? $pc_all_setting['pc_date_style_face'] : '';

    $pc_user_style .= '.pc_faq_wrapper .pc_faq_date{'
            . 'font-size:' . $pc_date_style_size . ';'
            . 'font-family:' . $pc_date_style_face . ';'
            . 'color:' . $pc_date_style_color . ';'
            . pc_faq_font_face($pc_date_style_style)
            . '}';
    
    $pc_author_style_size = isset($pc_all_setting['pc_author_style_size']) ? $pc_all_setting['pc_author_style_size'] : '';
    $pc_author_style_color = isset($pc_all_setting['pc_author_style_color']) ? $pc_all_setting['pc_author_style_color'] : '';
    $pc_author_style_style = isset($pc_all_setting['pc_author_style_style']) ? $pc_all_setting['pc_author_style_style'] : '';
    $pc_author_style_face = isset($pc_all_setting['pc_author_style_face']) ? $pc_all_setting['pc_author_style_face'] : '';

    $pc_user_style .= '.pc_faq_wrapper .pc_faq_author_name,.pc_faq_wrapper .pc_faq_author_name a{'
            . 'font-size:' . $pc_author_style_size . ';'
            . 'font-family:' . $pc_author_style_face . ';'
            . 'color:' . $pc_author_style_color . ';'
            . pc_faq_font_face($pc_author_style_style)
            . '}';

    $pc_file_name = 'pc-faq-user-style.css';
    $pc_file_path = PC_FAQ_ASSETS_DIR . 'css/';

    pc_faq_write_file_content($pc_file_path, $pc_file_name, $pc_user_style);
}

/**
 * Write Content in File
 * 
 * @since      1.0
 * @param type $pc_file_path
 * @param type $pc_file_name
 * @param type $file_content
 */
function pc_faq_write_file_content($pc_file_path, $pc_file_name, $file_content) {
    try {


        if (!file_exists($pc_file_path . $pc_file_name)) :
            $fp = fopen($pc_file_path . $pc_file_name, "w");
            if (!$fp) :
                $pc_status['status'] = 'error';
                $pc_status['message'] = 'Filed to open file. Change Permission';
            else:
                $pc_status['status'] = 'success';
                $pc_status['message'] = 'Setting Saved';
                fwrite($fp, $file_content);
                fclose($fp);
            endif;
        else:
            $fp = fopen($pc_file_path . $pc_file_name, "w");
            if (!$fp) :
                $pc_status['status'] = 'error';
                $pc_status['message'] = 'Filed to open file. Change Permission';
            else:
                $pc_status['status'] = 'success';
                $pc_status['message'] = 'Setting Saved';
                fwrite($fp, $file_content);
                fclose($fp);
            endif;
        endif;
    } catch (Exception $e) {
        $pc_status['status'] = 'error';
        $pc_status['message'] = 'Please Try Again';
    }
}

/**
 * WooCommerce Add Faq
 * 
 * @since      2.0
 */
add_action('wp_ajax_pc_faq_add_question', 'pc_faq_add_question');

function pc_faq_add_question() {
    $pc_data = $_POST['data'];
    $pc_post_id = $pc_data['pc_post_id'];
    $pc_product_question = $pc_data['pc_product_question'];
    $pc_product_answer = $pc_data['pc_product_answer'];
    $pc_status = array();
    $add_question_args = array(
        'post_type' => 'pc-faq',
        'post_title' => wp_strip_all_tags($pc_product_question),
        'post_content' => $pc_product_answer,
        'post_status' => 'publish',
        'post_author' => 1,
    );

    $post_id = wp_insert_post($add_question_args);
    add_post_meta($post_id, 'pc_faq_product_id', $pc_post_id, true);
    $pc_status['status'] = 'success';
    $pc_status['message'] = 'Successfully Added Question.';
    $pc_status['pc_questions'] = pc_get_product_faq($pc_post_id, true);
    echo json_encode($pc_status);
    die();
}

/**
 * Delete Faq
 * 
 * @since      2.0
 */
add_action('wp_ajax_pc_faq_delete_question', 'pc_faq_delete_question');

function pc_faq_delete_question() {
    $pc_data = $_POST['data'];
    $pc_post_id = $pc_data['pc_post_id'];
    $pc_product_id = $pc_data['pc_product_id'];
    $pc_status = array();
    $post_id = wp_delete_post($pc_post_id, true);
    $pc_status['status'] = 'success';
    $pc_status['message'] = 'Successfully Deleted Question.';
    $pc_status['pc_questions'] = pc_get_product_faq($pc_product_id, true);
    echo json_encode($pc_status);
    die();
}

/**
 * Approve User Question
 * 
 * @since      2.2
 */
add_action('wp_ajax_pc_faq_approve', 'pc_faq_approve');
function pc_faq_approve() {

    $pc_data = $_POST['data'];
    $post_id = $pc_data['pc_post_id'];
    $nonce = $pc_data['nonce'];

    $result = array();

    /**
     * todo: move this cap to product author
     */
    if (!current_user_can('publish_post', $post_id)) {
        $result['status'] = 'error';
        $result['message'] = __('Current user does not have permissions over this post', PC_FAQ_TEXT_DOMAIN);
        echo json_encode($result);
        die();
    }

    /**
     * verify the posted nonce
     */
    if (!wp_verify_nonce($nonce, 'publish-post_' . $post_id)) {
        $result['status'] = 'error';
        $result['message'] = __('Cheatin&#8217; uh?');
        echo json_encode($result);
        die();
    }

    /**
     * if we got this far, publish the post and generate the success
     */
    wp_publish_post($post_id);

    $result['status'] = 'success';
    $result['message'] = __('Approved...reloading now.', PC_FAQ_TEXT_DOMAIN);
    $result['redirect'] = admin_url('edit.php?post_type=' . PC_FAQ_POST_TYPE);

    echo json_encode($result);
    die();
}
