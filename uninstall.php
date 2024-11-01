<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 */

/**
 * Define default table name
 */
if ( ! defined( 'WP_LIGHT_HEATMAP_DOTS_TABLE' ) ) {
	define( 'WP_LIGHT_HEATMAP_DOTS_TABLE', 'light_heatmap_dots' );
}

/**
 * If uninstall not called from WordPress, then exit.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

class Wp_light_heatmap_Uninstaller {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
		// Remove the table from the database
		global $wpdb;
		$wp_light_heatmap_db_version = '1.0';
		$table_name                  = $wpdb->prefix . WP_LIGHT_HEATMAP_DOTS_TABLE;

		$sql = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		delete_option( 'wp_light_heatmap_options', $options );
	}

}

Wp_light_heatmap_Uninstaller::uninstall();
