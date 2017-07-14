<?php

/**
 * @package   Options_Framework
 * @license   GPL-2.0+
 */
class Pcs_Faq_Framework_Admin {

    
    /**
     * Hook in the scripts and styles
     *
     * @since 1.0
     */
    public function init() {

        // Gets options to load
        $options = & Pcs_Faq_Framework::_pcs_framework_options();

        // Checks if options are available
        if ($options) {
            // Settings need to be registered after admin_init
            add_action('admin_init', array($this, 'settings_init'));

        } 
    }


    /**
     * Registers the settings
     *
     * @since 1.0
     */
    function settings_init() {

        // Load Options Framework Settings
        $optionsframework_settings = Pc_Faq_Core::pc_setting_name();

        // Registers the settings fields and callback
        register_setting($optionsframework_settings, $optionsframework_settings, array($this, 'pcs_validate_options'));

        // Displays notice after options save
        add_action('optionsframework_after_validate', array($this, 'pcs_save_options_notice'));
    }
    

    

    /**
     * Validate Options.
     *
     * This runs after the submit/reset button has been clicked and
     * validates the inputs.
     *
     * @uses $_POST['reset'] to restore default options
     */
    function pcs_validate_options($input) {

        /*
         * Restore Defaults.
         *
         * In the event that the user clicked the "Restore Defaults"
         * button, the options defined in the theme's options.php
         * file will be added to the option for the active theme.
         */

        if (isset($_POST['reset'])) {
            add_settings_error('options-framework', 'restore_defaults', __('Default options restored.', 'options-framework'), 'updated fade');
            return $this->pc_get_default_values();
        }

        /*
         * Update Settings
         *
         * This used to check for $_POST['update'], but has been updated
         * to be compatible with the theme customizer introduced in WordPress 3.4
         */

        $clean = array();
        $options = & Pcs_Faq_Framework::_pcs_framework_options();
        foreach ($options as $option) {

            if (!isset($option['id'])) {
                continue;
            }

            if (!isset($option['type'])) {
                continue;
            }

            $id = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($option['id']));

            // Set checkbox to false if it wasn't sent in the $_POST
            if ('checkbox' == $option['type'] && !isset($input[$id])) {
                $input[$id] = false;
            }

            // Set each item in the multicheck to false if it wasn't sent in the $_POST
            if ('multicheck' == $option['type'] && !isset($input[$id])) {
                foreach ($option['options'] as $key => $value) {
                    $input[$id][$key] = false;
                }
            }

            // For a value to be submitted to database it must pass through a sanitization filter
            if (has_filter('of_sanitize_' . $option['type'])) {
                $clean[$id] = apply_filters('of_sanitize_' . $option['type'], $input[$id], $option);
            }
        }

        // Hook to run after validation
        do_action('optionsframework_after_validate', $clean);

        return $clean;
    }

    /**
     * Display message when options have been saved
     */
    function pcs_save_options_notice() {
        add_settings_error('options-framework', 'save_options', __('Options saved.', 'options-framework'), 'updated fade');
    }

    /**
     * Get the default values for all the theme options
     *
     * Get an array of all default values as set in
     * options.php. The 'id','std' and 'type' keys need
     * to be defined in the configuration array. In the
     * event that these keys are not present the option
     * will not be included in this function's output.
     *
     * @return array Re-keyed options configuration array.
     *
     */
    function pc_get_default_values() {
        $output = array();
        $config = & Pcs_Faq_Framework::_pcs_framework_options();
        foreach ((array) $config as $option) {
            if (!isset($option['id'])) {
                continue;
            }
            if (!isset($option['std'])) {
                continue;
            }
            if (!isset($option['type'])) {
                continue;
            }
            if (has_filter('of_sanitize_' . $option['type'])) {
                $output[$option['id']] = apply_filters('of_sanitize_' . $option['type'], $option['std'], $option);
            }
        }
        return $output;
    }

}
