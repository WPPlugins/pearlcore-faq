<?php

/**
 * @package   Options_Framework
 * @license   GPL-2.0+
 */
class Pcs_Faq_Framework {

    /**
     * Initialize the plugin.
     *
     * @since 1.7.0
     */
    public function init() {

        // Needs to run every time in case theme has been changed
        add_action('admin_init', array($this, 'pcs_set_theme_option'));
    }

    /**
     * Sets option defaults
     *
     * @since 1.7.0
     */
    function pcs_set_theme_option() {

        // Load settings
        $optionsframework_settings = get_option('optionsframework');

        // Updates the unique option id in the database if it has changed
        if (function_exists('pcs_option_name')) {
            pcs_option_name();
        } elseif (has_action('pcs_option_name')) {
            do_action('pcs_option_name');
        }
        // If the developer hasn't explicitly set an option id, we'll use a default
        else {
            $default_themename = 'optionsframework_' . $default_themename;
            if (isset($optionsframework_settings['id'])) {
                if ($optionsframework_settings['id'] == $default_themename) {
                    // All good, using default theme id
                } else {
                    $optionsframework_settings['id'] = $default_themename;
                    update_option('optionsframework', $optionsframework_settings);
                }
            } else {
                $optionsframework_settings['id'] = $default_themename;
                update_option('optionsframework', $optionsframework_settings);
            }
        }
    }

    /**
     * Wrapper for optionsframework_options()
     *
     * Allows for manipulating or setting options via 'of_options' filter
     * For example:
     *
     * <code>
     * add_filter( 'of_options', function( $options ) {
     *     $options[] = array(
     *         'name' => 'Input Text Mini',
     *         'desc' => 'A mini text input field.',
     *         'id' => 'example_text_mini',
     *         'std' => 'Default',
     *         'class' => 'mini',
     *         'type' => 'text'
     *     );
     *
     *     return $options;
     * });
     * </code>
     *
     * Also allows for setting options via a return statement in the
     * options.php file.  For example (in options.php):
     *
     * <code>
     * return array(...);
     * </code>
     *
     * @return array (by reference)
     */
    static function &_pcs_framework_options() {
        static $options = null;

        if (!$options) {
            // Load options from options.php file (if it exists)
            $options = pcs_faq_options();
            // Allow setting/manipulating options via filters
            $options = apply_filters('pcs_faq_options', $options);
        }

        return $options;
    }

}
