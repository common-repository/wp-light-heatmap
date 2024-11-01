<?php

/**
 * Include libraries, scripts and styles.
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/core
 */

add_action( 'wp_enqueue_scripts', 'wp_light_heatmap_script' );

/**
 * Include front-end scripts to process clicks.
 *
 * This function is used to load the necessary
 * libraries and scripts for front-end part.
 *
 * @since    1.0.0
 */
function wp_light_heatmap_script() {
	// Script for tracking user clicks
	$options = get_option( 'wp_light_heatmap_options' );
	wp_light_heatmap_pass_options( $options );
	if ( array_key_exists( 'wp_light_heatmap_track_click', $options )
		 or array_key_exists( 'wp_light_heatmap_track_move', $options ) ) {
		wp_enqueue_script( 'wp_light_heatmap_click', plugins_url( 'public/js/wp_light_heatmap_click-public.js', __DIR__ ), array( 'jquery' ), null, false );
	}

	// Script for processing the heatmap
	wp_enqueue_script( 'wp_light_heatmap', plugins_url( 'public/js/wp_light_heatmap-public.js', __DIR__ ), array( 'jquery' ), null, false );

	// JS Cookie plugin
	wp_enqueue_script( 'wp_light_heatmap_cookie', plugins_url( 'public/js/cookie/js.cookie.min.js', __DIR__ ), array( 'jquery' ), null, false );

	// Declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
	wp_localize_script(
		'wp_light_heatmap',
		'MyAjax',
		array(
			'displayurl' => get_rest_url( 0, '/heatmap/v1/endpoint' ),
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		)
	);

	// The CSS file used for displaying clicks
	wp_enqueue_style( 'wp_light_heatmap_style', plugins_url( 'public/css/wp_light_heatmap-public.css', __DIR__ ), null, null, false );
}

/**
 * Include back-end scripts and styles.
 *
 * This function is used to load the necessary
 * scripts and styles for back-end part.
 *
 * @since    1.0.0
 */
add_action( 'admin_init', 'wp_light_heatmap_admin_script' );
function wp_light_heatmap_admin_script() {
	// Admin Heatmap jQuery
	wp_enqueue_script( 'wp_light_heatmap_admin', plugins_url( 'admin/js/wp_light_heatmap-admin.js', __DIR__ ), array( 'jquery' ), null, false );

	// JSON for jQuery and Javascript
	wp_enqueue_script( 'wp_light_heatmap_json', plugins_url( 'public/js/json/json.js', __DIR__ ), array( 'jquery' ), null, false );

	// Admin Heatmap CSS
	wp_enqueue_style( 'wp_light_heatmap_admin_style', plugins_url( 'public/css/wp_light_heatmap-public.css', __DIR__ ), null, null, false );
}

