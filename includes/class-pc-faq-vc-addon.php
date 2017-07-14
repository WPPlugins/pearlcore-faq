<?php

/*
 * Add-on Name: Pearlcore FAQ
 * Add-on URI: http://pearlcore.com/
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * Add-on Name: Pearlcore FAQ
 * Add-on URI: http://pearlcore.com/
 * 
 * @since      2.1
 * @package    Pc_Faq
 * @subpackage Pc_Faq_Vc_Addon/include
 */
if (!class_exists('Pc_Faq_Vc_Addon')) {

    class Pc_Faq_Vc_Addon {

        function __construct() {
            add_action('admin_init', array(&$this, 'pc_faq_addon_init'));
        }

        /**
         * FAQ vc addon
         */
        function pc_faq_addon_init() {
            if (function_exists('vc_map')) {
                vc_map(
                        array(
                            "name" => __("Pearlcore FAQ", PC_FAQ_TEXT_DOMAIN),
                            "base" => "Pc_Faq",
                            "icon" => "pc_faq_icon",
                            "class" => "pc_faq_addon",
                            "category" => "Pearlcore VC Addons",
                            "description" => __("Add FAQ", PC_FAQ_TEXT_DOMAIN),
                            "controls" => "full",
                            "show_settings_on_create" => true,
                            "params" => array(
                                array(
                                    "type" => "textfield",
                                    "class" => "",
                                    "heading" => __("FAQ Title", PC_FAQ_TEXT_DOMAIN),
                                    "param_name" => "title",
                                    "value" => 'Frequently Asked Questions',
                                    "admin_label" => true,
                                ),
                                array(
                                    "type" => "dropdown",
                                    "class" => "",
                                    "heading" => __("Faq Category:", PC_FAQ_TEXT_DOMAIN),
                                    "param_name" => "category",
                                    "value" => pc_faq_get_custom_categories_for_addon(),
                                ),
                            ) // end params array
                        ) // end vc_map array
                ); // end vc_map
            } // end function check 'vc_map'
        }

// end function icon_box_init
    }

    //Class end
}
if (class_exists('Pc_Faq_Vc_Addon')) {
    $Pc_Faq_Vc_Addon = new Pc_Faq_Vc_Addon;
}