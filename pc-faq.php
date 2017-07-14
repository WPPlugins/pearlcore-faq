<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link:       http://pearlcore.com/
 * @since             1.0
 * @package           Pc_Faq
 *
 * @wordpress-plugin
 * Plugin Name:       Pearlcore FAQ
 * Plugin URI:        http://pearlcore.com/
 * Description:       Pearlcore FAQ is fully responsive with Unlimited Categories, Custom Styling and Like features. You Can easily add FAQ's on WooCommerce Product.
 * Version:           2.3
 * Author:            pearlcore
 * Author URI:        http://pearlcore.com/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:            pc-faq
 * Domain Path:            /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Declearation of the necessary constants for plugin
if (!defined('PC_FAQ_VERSION')) {
    define('PC_FAQ_VERSION', '2.3');
}

if (!defined('PC_FAQ_FREE')) {
    define('PC_FAQ_FREE', 'yes');
}

if (!defined('PC_FAQ_LANG_DIR')) {
    define('PC_FAQ_LANG_DIR', basename(dirname(__FILE__)) . '/languages/');
}

if (!defined('PC_FAQ_TEXT_DOMAIN')) {
    define('PC_FAQ_TEXT_DOMAIN', 'pc-faq');
}


if (!defined('PC_FAQ_PLUGIN_DIR')) {
    define('PC_FAQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('PC_FAQ_PLUGIN_URL')) {
    define('PC_FAQ_PLUGIN_URL', plugins_url('/', __FILE__));
}

if (!defined('PC_FAQ_ASSETS_URL')) {
    define('PC_FAQ_ASSETS_URL', PC_FAQ_PLUGIN_URL . 'assets/');
}

if (!defined('PC_FAQ_ASSETS_DIR')) {
    define('PC_FAQ_ASSETS_DIR', PC_FAQ_PLUGIN_DIR . 'assets/');
}

if (!defined('PC_FAQ_TEMPLATE_PATH')) {
    define('PC_FAQ_TEMPLATE_PATH', PC_FAQ_PLUGIN_DIR . 'templates/');
}

if (!defined('PC_FAQ_INC')) {
    define('PC_FAQ_INC', PC_FAQ_PLUGIN_DIR . '/includes/');
}

if (!defined('PC_FAQ_POST_TYPE')) {
    define('PC_FAQ_POST_TYPE', 'pc-faq');
}

class Pc_Faq {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0
     * @access   protected
     * @var      Pc_Faq_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0
     * @access   protected
     * @var      string    $Pc_Faq    The string used to uniquely identify this plugin.
     */
    protected $Pc_Faq;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0
     */
    public function __construct() {

        $this->plugin_name = 'pc-faq';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_backend_hooks();
        $this->define_frontend_hooks();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     * @since  1.0
     */
    private function init_hooks() {

        /** This action is documented in includes/class-pc-faq-activator.php */
        register_activation_hook(__FILE__, array('Pc_Faq_Activator', 'activate'));

        /** This action is documented in includes/class-pc-faq-deactivator.php */
        register_activation_hook(__FILE__, array('Pc_Faq_Deactivator', 'deactivate'));
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for core functionality of the plugin
         */
        require_once PC_FAQ_INC . 'class-pc-faq-core.php';


        // Loads the required Options Framework classes.
        require PC_FAQ_INC . 'settings/pc-faq-settings.php';
        require PC_FAQ_INC . 'setting-framework/class-pc-faq-framework.php';
        require PC_FAQ_INC . 'setting-framework/class-pc-faq-framework-admin.php';
        require PC_FAQ_INC . 'setting-framework/class-pc-faq-interface.php';
        require PC_FAQ_INC . 'setting-framework/class-pc-faq-sanitization.php';

        /**
         * The code that runs during plugin activation.
         */
        require_once PC_FAQ_INC . 'class-pc-faq-activator.php';



        /**
         * The code that runs during plugin deactivation.
         */
        require_once PC_FAQ_INC . 'class-pc-faq-deactivator.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once PC_FAQ_INC . 'class-pc-faq-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once PC_FAQ_INC . 'class-pc-faq-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once PC_FAQ_INC . 'class-pc-faq-frontend.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once PC_FAQ_INC . 'class-pc-faq-backend.php';

        /**
         * Backend Function
         */
        require_once PC_FAQ_INC . 'pc-faq-backend-functions.php';

        /**
         * The class responsible for widget.
         */
        require_once PC_FAQ_INC . 'widgets/class-pc-faq-widget.php';

        /**
         * The class responsible for VC Addon
         * 
         * @since       2.1
         */
        require_once PC_FAQ_INC . 'class-pc-faq-vc-addon.php';

        /**
         * Form Handler Class
         * 
         * @since       2.2
         */
        require_once( PC_FAQ_INC . 'class-pc-faq-form-handler.php' );

        $this->loader = new Pc_Faq_Loader();
    }

    /**
     * What type of request is this?
     * string $type ajax, frontend or admin
     * @return bool
     */
    private function is_request($type) {
        switch ($type) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined('DOING_AJAX');
            case 'cron' :
                return defined('DOING_CRON');
            case 'frontend' :
                return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
        }
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Pc_Faq_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Pc_Faq_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function define_backend_hooks() {

        $plugin_admin = new Pc_Faq_backend($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function define_frontend_hooks() {

        $plugin_public = new Pc_Faq_Frontend($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0
     * @return    Pc_Faq_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return PC_FAQ_VERSION;
    }

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_Pc_Faq() {

    $plugin = new Pc_Faq();
    $plugin->run();
}

run_Pc_Faq();
