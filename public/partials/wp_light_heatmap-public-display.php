<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/public/partials
 */

add_action( 'wp_footer', 'insert_display_heatmap_bar' );

/**
 * Add "Display" button to the main page of the blog
 *
 * --
 *
 * @since    1.0.0
 */
function insert_display_heatmap_bar() {
	if ( current_user_can( 'administrator' ) ) {
		$options = get_option( 'wp_light_heatmap_options' );
		wp_light_heatmap_pass_options( $options );
		if ( $options['wp_light_heatmap_display_tab'] == '1' ) {
			?>
			<div class="heatmap-bar-off">
				<span class="heatmap-bar-span">Display Heatmap</span>
				<div id="spinner" class="spinner">
					<img id="img-spinner" src="<?php echo plugins_url( 'images/ajax-loader.gif', __DIR__ ); ?>" alt="Loading"/>
				</div>
			</div>
			<?php
		}
	}
}
?>
