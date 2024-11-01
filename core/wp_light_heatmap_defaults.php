<?php

/**
 * Set default values for plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/core
 */

/**
 * Set default values for plugin.
 *
 * This function is used to initialize
 * and set default plugin values.
 *
 * @since    1.0.0
 */
function wp_light_heatmap_get_default_values() {
	$defaults = array(
		'wp_light_heatmap_track_move'        => '1',
		'wp_light_heatmap_track_click'       => '0',
		'wp_light_heatmap_display_tab'       => '1',
		'wp_light_heatmap_requests_interval' => 5,
	);
	return $defaults;
}
