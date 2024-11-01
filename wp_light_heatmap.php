<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Wp_light_heatmap
 *
 * @wordpress-plugin
 * Plugin Name:       WP Light Heatmap
 * Description:       This plugin allows you to create a heatmap based on mouse clicks and cursor movements.
 * Version:           1.0.0
 * Author:            WPLightHeatmap Team
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp_light_heatmap
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_LIGHT_HEATMAP_VERSION', '1.0.0' );

/**
 * Define default table name
 */
define( 'WP_LIGHT_HEATMAP_DOTS_TABLE', 'light_heatmap_dots' );

/**
 * Include default plugin values
 */
require 'core/wp_light_heatmap_defaults.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp_light_heatmap-activator.php
 */
register_activation_hook( __FILE__, 'activate_wp_light_heatmap' );
function activate_wp_light_heatmap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp_light_heatmap-activator.php';
	Wp_light_heatmap_Activator::activate();
}

/**
 * Pass options to javascript part of module
 */
function wp_light_heatmap_pass_options( $options ) {
	$str  = '<script type="text/javascript">';
	$str .= 'var lightHeatmapOptionsArray = ';
	$str .= json_encode( $options );
	$str .= '; </script>';
	echo $str;
}

/**
 * Include additional .php sub-modules
 */
require 'core/wp_light_heatmap_includes.php';
require 'core/wp_light_heatmap_classes.php';
require 'core/wp_light_heatmap_calculate.php';

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp_light_heatmap-deactivator.php
 */
register_deactivation_hook( __FILE__, 'deactivate_wp_light_heatmap' );
function deactivate_wp_light_heatmap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp_light_heatmap-deactivator.php';
	Wp_light_heatmap_Deactivator::deactivate();
}

/**
 * Init plugin options to white list our options
 */
add_action( 'admin_init', 'wp_light_heatmap_init' );
function wp_light_heatmap_init() {
	register_setting( 'wp_light_heatmap_plugin_options', 'wp_light_heatmap_options', 'wp_light_heatmap_options_validate' );
}

/**
 * Validate passed options if needed
 */
function wp_light_heatmap_options_validate( $input ) {
	/*
	 * Write your input validator here if
	 * the input validation feature is required.
	 */
	return $input;
}

/**
 * Initialize REST API endpoint to save added dots
 */
add_action( 'rest_api_init', 'init_rest_route' );
function init_rest_route() {
	register_rest_route(
		'heatmap/v1',
		'/endpoint',
		array(
			'methods'  => 'POST',
			'callback' => 'wp_light_heatmap_add_dot',
		)
	);
}

/**
 * Define callback request handler to process fields
 * and other data of our added dots
 */
add_action( 'wp_ajax_nopriv_wp_light_heatmap_add_dot', 'wp_light_heatmap_add_dot' );
add_action( 'wp_ajax_wp_light_heatmap_add_dot', 'wp_light_heatmap_add_dot' );
function wp_light_heatmap_add_dot( $request ) {
	// WP Database setup
	global $wpdb;
	$table_name = $wpdb->prefix . WP_LIGHT_HEATMAP_DOTS_TABLE;

	// Get POST Variables from AJAX
	$data = array(
		'x_coord'         => intval( esc_attr( $request['x_coord'] ) ),
		'y_coord'         => intval( esc_attr( $request['y_coord'] ) ),
		'timestamp'       => intval( esc_attr( $request['timestamp'] ) ),
		'heatmap_user_id' => esc_attr( $request['heatmap_user_id'] ),
		'created_date'    => date( 'Y-m-d H:i:s' ),
		'selector'        => sanitize_text_field( $request['selector'] ),
		'url'             => esc_url( $request['current_url'] ),
	);
	$wpdb->insert( $table_name, $data );

	// Ensure created data
	$response = rest_ensure_response( array('status' => 'ok') );
	$response->set_status( 200 );
	return $response;
}

/**
 * Display dots in interface or any other place data gatherer
 */
add_action( 'wp_ajax_nopriv_wp_light_heatmap_display', 'wp_light_heatmap_display' );
add_action( 'wp_ajax_wp_light_heatmap_display', 'wp_light_heatmap_display' );
function wp_light_heatmap_display() {
	// Strip slashes on POST
	if ( get_magic_quotes_gpc() ) {
		$_POST = array_map( 'stripslashes_deep', $_POST );
	}

	// WP Database setup
	global $wpdb;
	$table_name = $wpdb->prefix . WP_LIGHT_HEATMAP_DOTS_TABLE;

	// Get the current URL
	$url = esc_url( $_POST['current_url'] );

	// Get the date options for the plugin
	$dots           = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE url = %s", $url ) );
	$dot_collection = new wp_light_heatmap_dot_collection();
	foreach ( $dots as $dot ) {
		$dot_collection->add_dot( $dot->x_coord, $dot->y_coord, $dot->created_date, $dot->selector );
	}

	echo json_encode( $dot_collection );
	exit;
}

/**
 * Clear database handler
 */
add_action( 'wp_ajax_nopriv_wp_light_heatmap_clear_database', 'wp_light_heatmap_clear_database' );
add_action( 'wp_ajax_wp_light_heatmap_clear_database', 'wp_light_heatmap_clear_database' );
function wp_light_heatmap_clear_database() {
	global $wpdb;
	$table_name = $wpdb->prefix . WP_LIGHT_HEATMAP_DOTS_TABLE;

	$wpdb->query( "TRUNCATE TABLE $table_name" );

	exit;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp_light_heatmap.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_light_heatmap() {

	$plugin = new Wp_light_heatmap();
	$plugin->run();

}
run_wp_light_heatmap();
