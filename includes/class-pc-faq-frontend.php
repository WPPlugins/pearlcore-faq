<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since      1.0
 * @package    Pc_Faq
 * @subpackage Pc_Faq_Frontend/include
 */
class Pc_Faq_Frontend {

    /**
     * The ID of this plugin.
     *
     * @since    1.0
     * @access   private
     * @var      string    $name    The ID of this plugin.
     */
    private $name;

    /**
     * The version of this plugin.
     *
     * @since    1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0
     * @var      string    $name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($name, $version) {

        $this->name = $name;
        $this->version = $version;

        add_shortcode('Pc_Faq', array($this, 'pc_faq_shortcode'));

        add_filter('woocommerce_product_tabs', array($this, 'pc_faq_new_product_tab'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->name . '-font-awesome.min', PC_FAQ_ASSETS_URL . 'css/font-awesome.min.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-theme-a', PC_FAQ_ASSETS_URL . 'css/theme-a.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-pc-faq-frontend', PC_FAQ_ASSETS_URL . 'css/pc-faq-frontend.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-pc-faq-user-style', PC_FAQ_ASSETS_URL . 'css/pc-faq-user-style.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->name . '-pc-faq-frontend', PC_FAQ_ASSETS_URL . 'js/pc-faq-frontend.js', array('jquery'), $this->version, true);

        wp_localize_script($this->name . '-pc-faq-frontend', 'pc_frontend', array('pc_ajax' => admin_url('admin-ajax.php')), PC_FAQ_VERSION);
    }

    /**
     * Faq Shortcode
     * 
     * @since    1.0
     * @param type $atts
     * @return type
     */
    function pc_faq_shortcode($atts) {
        $pc_user_attr = shortcode_atts(array(
            'title' => 'Frequently Asked Questions',
            'category' => '',
            'ask_question' => '',
                ), $atts);
        $type = 'pc-faq';
        $pc_shortcode_title = (isset($pc_user_attr['title'])) ? $pc_user_attr['title'] : '';
        $pc_category = (isset($pc_user_attr['category'])) ? $pc_user_attr['category'] : '';
        $ask_question = (isset($pc_user_attr['ask_question'])) ? $pc_user_attr['ask_question'] : '';
        if ($pc_category != 'select'):
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'fa_category' => $pc_category,
                'numberposts' => 10,
            );
        else:
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'numberposts' => 10,
            );
        endif;
        $pc_faq_ask = pc_faq_ask_question_form();
        $pc_faq_content = pc_get_site_faq($args, $pc_shortcode_title);
        if ($ask_question == TRUE):
            return $pc_faq_content . $pc_faq_ask;
        else:
            return $pc_faq_content;
        endif;
    }

    /**
     * Faq Woocommerce Tab
     * 
     * @since    2.0
     * @global type $post
     * @global type $product
     * @param type $tabs
     * @return type
     */
    function pc_faq_new_product_tab($tabs) {
        global $post, $product;
        $custom_tab_options = array(
            'id' => $post->ID,
            'pc_enabled' => get_post_meta($post->ID, 'pc_enable_single_product', true),
            'pc_enable_ask_question' => get_post_meta($post->ID, 'pc_enable_ask_question', true),
            'pc_product_faq' => pc_get_product_faq($post->ID, ''),
        );
        $pc_faq_setting_name = pc_faq_setting_name();
        $pc_faq_settings = get_option($pc_faq_setting_name);
        $pc_show_faq_product = $pc_faq_settings['pc_show_faq_product'];
        $pc_tab_title = isset($pc_faq_settings['pc_tab_title']) ? $pc_faq_settings['pc_tab_title'] : '';
        if (!$pc_tab_title):
            $pc_tab_title = 'FAQ';
        endif;
        if ($custom_tab_options['pc_enabled'] != 'no' && isset($pc_show_faq_product) && $pc_show_faq_product === 'yes'):
            $tabs['pc_faq_tab'] = array(
                'title' => __($pc_tab_title, PC_FAQ_TEXT_DOMAIN),
                'priority' => 50,
                'callback' => array($this, 'pf_faq_new_product_tab_content'),
                'content' => $custom_tab_options,
            );
        endif;
        return $tabs;
    }

    /**
     * Single product Faq's
     * 
     * @since    2.0
     * @param type $key
     * @param type $custom_tab_options
     */
    function pf_faq_new_product_tab_content($key, $custom_tab_options) {
        $content = $custom_tab_options['content']['pc_product_faq'];
        $pc_enable_ask_question = $custom_tab_options['content']['pc_enable_ask_question'];
        $pc_faq_ask = pc_faq_ask_question_form();
        if ($pc_enable_ask_question == 'yes'):
            echo $content . $pc_faq_ask;
        else:
            echo $content;
        endif;
    }

}
