<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    Pc_Faq
 * @subpackage Pc_Faq/includes
 */
class Pc_Faq_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0
     */
    public static function activate() {
        if (!get_option('pc_faq_install')):
            self::pc_faq_create_tabels();
            add_option('pc_faq_install', true);
        endif;
    }

    /**
     * Likes Table
     * 
     * @since      1.0
     * @global type $wpdb
     */
    public static function pc_faq_create_tabels() {
        global $wpdb;
        $collate = $wpdb->get_charset_collate();

        $pc_faq_table_sql = "
CREATE TABLE {$wpdb->prefix}pc_like_counts (
id INT(11) NOT NULL AUTO_INCREMENT,
user_ip VARCHAR(255) NOT NULL,
post_id VARCHAR(255) NOT NULL,
PRIMARY KEY (id)
) $collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($pc_faq_table_sql);
    }

}
