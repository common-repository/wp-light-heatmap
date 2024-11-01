<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/includes
 * @author     WPLightHeatmap Team <wplightheatmap@gmail.com>
 */

/**
 * Define default table name
 */
if ( ! defined( 'WP_LIGHT_HEATMAP_DOTS_TABLE' ) ) {
	define( 'WP_LIGHT_HEATMAP_DOTS_TABLE', 'light_heatmap_dots' );
}

class Wp_light_heatmap_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Create the table in the database
		global $wpdb;
		$wp_light_heatmap_db_version = '1.0';
		$table_name                  = $wpdb->prefix . WP_LIGHT_HEATMAP_DOTS_TABLE;

		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . " (
			id INTEGER NOT NULL AUTO_INCREMENT,
			created_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
			x_coord INTEGER NOT NULL,
			y_coord INTEGER NOT NULL,
			timestamp TEXT NOT NULL,
			heatmap_user_id TEXT NOT NULL,
			neighbors INTEGER NOT NULL, 
			color TEXT NOT NULL,
			selector TEXT NOT NULL,
			url TEXT NOT NULL,
			UNIQUE KEY id (id)
		);";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		// Add the options for the plugin
		$defaults = wp_light_heatmap_get_default_values();
		$options  = get_option( 'wp_light_heatmap_options' );
		$options  = wp_parse_args( $options, $defaults );
		update_option( 'wp_light_heatmap_options', $options );
	}

}
