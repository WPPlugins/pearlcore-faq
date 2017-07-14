<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-pc-faq-form-handler
 *
 * @author owaiskiani
 * @since      2.2
 */
class Pc_Faq_Form_handler {

    /**
     * Hook in methods
     */
    public static function init() {
        add_action('wp_loaded', array(__CLASS__, 'pc_faq_ask_process'), 20);
    }

    /**
     * Handel User submited question
     * 
     * @since      2.2
     * @global type $current_user
     */
    public static function pc_faq_ask_process() {
        $pc_faq_ask_data = $_POST;
        if (!empty($pc_faq_ask_data['pc_faq_ask']) && !empty($pc_faq_ask_data['_wpnonce']) && wp_verify_nonce($pc_faq_ask_data['_wpnonce'], 'pc-faq-ask-question')) {
            try {
                $pc_faq_current_id = isset($pc_faq_ask_data['pc_faq_current_id']) ? $pc_faq_ask_data['pc_faq_current_id'] : '';
                $faq_your_name = isset($pc_faq_ask_data['faq_your_name']) ? $pc_faq_ask_data['faq_your_name'] : '';
                $faq_your_email = isset($pc_faq_ask_data['faq_your_email']) ? $pc_faq_ask_data['faq_your_email'] : '';
                $faq_your_question = isset($pc_faq_ask_data['faq_your_question']) ? $pc_faq_ask_data['faq_your_question'] : '';

                $add_question_args = array(
                    'post_type' => 'pc-faq',
                    'post_title' => wp_strip_all_tags($faq_your_question),
                    'post_content' => '',
                    'post_status' => 'pending',
                    'post_author' => 1,
                );
                if (is_user_logged_in()):
                    global $current_user;
                    get_currentuserinfo();
                    $pc_user_id = $current_user->ID;
                    $add_question_args['post_author'] = $pc_user_id;
                endif;
                $post_id = wp_insert_post($add_question_args);
                add_post_meta($post_id, 'pc_faq_product_id', $pc_faq_current_id, true);
                if (!is_user_logged_in()):
                    add_post_meta($post_id, 'pc_faq_author_name', $faq_your_name, true);
                    add_post_meta($post_id, 'pc_faq_author_email', $faq_your_email, true);
                endif;
                $_SESSION['pc_faq_add_message'] = '<div class="pc_faq_add_success">FAQ Successfully Posted. Your question will be reviewed and answered soon!</div>';
            } catch (Exception $e) {
                wc_add_notice(apply_filters('login_errors', $e->getMessage()), 'error');
            }
        }
    }

}

Pc_Faq_Form_handler::init();
