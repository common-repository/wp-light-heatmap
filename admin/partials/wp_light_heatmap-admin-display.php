<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/admin/partials
 */

add_action( 'admin_menu', 'wp_light_heatmap_admin_menu' );

/**
 * Add menu page for plugin options.
 *
 * --
 *
 * @since    1.0.0
 */
function wp_light_heatmap_admin_menu() {
	add_menu_page( 'WP Light Heatmap Options', 'Light Heatmap', 'manage_options', 'wp_light_heatmap_page', 'wp_light_heatmap_plugin_options' );
}

/**
 * Create plugin options page.
 *
 * This function is used to render the plugin settings menu.
 *
 * @since    1.0.0
 */
function wp_light_heatmap_plugin_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$options = get_option( 'wp_light_heatmap_options' );

	?>
	<div class="wrap">
		<h1 class="header-plugin-settings">WP Light Heatmap Settings</h1>
		<div class="postbox" id="basic-settings-block">
			<h3 class="hndle">
				<span class="postbox-title">Basic Options</span>
			</h3>
		
			<div class="inside">
				<p class="postbox-description">
					In this section of options, you can configure the main parameters of the WP Light Heatmap plugin:<br>
					1. <b>Enable Mouse Click Tracking</b> option allows you to track clicks of the visitors on your site<br>
					2. <b>Enable Cursor Move Tracking</b> option allows you to track mouse movements of the visitors on your site per fixed time interval<br>
					3. <b>Display Heatmap Button</b> option allows you to display a special button on the main page of the site that will turn on the heatmap overlay to show you saved positions (for administrators only)<br>
					4. <b>Interval of Position Saving</b> option allows you to set the interval for saving cursor positions when <b>Enable Cursor Move Tracking</b> option is selected<br></p>
				<hr />

				<form method="post" action="options.php">
					<?php settings_fields( 'wp_light_heatmap_plugin_options' ); ?>
					<div class="alignleft">
						<p class="onoff-block">
							<label class="label-suboption" for="display_on_off">Enable Mouse Click Tracking</label>
							<input type="checkbox" name="wp_light_heatmap_options[wp_light_heatmap_track_click]" class="onoffswitch-checkbox" id="track_click_button" value="1" 		
							<?php
							if ( isset( $options['wp_light_heatmap_track_click'] ) ) {
								checked( '1', $options['wp_light_heatmap_track_click'] );
							}
							?>
							/>
							<label class="onoffswitch-label" for="track_click_button">
								<span class="onoffswitch-inner"></span>
							</label>
						</p>
					</div> 

					<div class="alignleft">
						<p class="onoff-block">
							<label class="label-suboption" for="display_on_off">Enable Cursor Move Tracking</label>
							<input type="checkbox" name="wp_light_heatmap_options[wp_light_heatmap_track_move]" class="onoffswitch-checkbox" id="track_move_button" value="1" 		
							<?php
							if ( isset( $options['wp_light_heatmap_track_move'] ) ) {
								checked( '1', $options['wp_light_heatmap_track_move'] );
							}
							?>
							/>
							<label class="onoffswitch-label" for="track_move_button">
								<span class="onoffswitch-inner"></span>
							</label>
						</p>
					</div> 
					
					<div class="alignleft">
						<p class="onoff-block">
							<label class="label-suboption" for="clicktrack_on_off">Display Heatmap Button</label>
							<input type="checkbox" name="wp_light_heatmap_options[wp_light_heatmap_display_tab]" class="onoffswitch-checkbox" id="display_button" value="1" 		
							<?php
							if ( isset( $options['wp_light_heatmap_display_tab'] ) ) {
								checked( '1', $options['wp_light_heatmap_display_tab'] );
							}
							?>
							/>
							<label class="onoffswitch-label" for="display_button">
								<span class="onoffswitch-inner"></span>
							</label>
						</p>
					</div>

					<div class="alignleft">
						<p class="interval-block">
							<label class="label-suboption">Interval of Position Saving<br></label>
							<label class="label-suboption">
							<input class="text" id="interval-form" maxlength="4" name="wp_light_heatmap_options[wp_light_heatmap_requests_interval]" type="text" value="<?php echo $options['wp_light_heatmap_requests_interval']; ?>"
							/>
							</label>
						</p>
					</div>
					<div class="clear-block"></div>

					<hr />
					
					<p class="primary-settings-button-block">
						<input type="submit" class="button-primary primary-settings-button-override" value="<?php _e( 'Save Changes' ); ?>" />
					</p>

				</form>
			</div> <!-- end .inside -->
		</div> <!-- end .postbox -->
	
		<div class="postbox" id="clear-database-block">
			<h3 class="hndle">
				<span class="postbox-title">Clear Database</span>
			</h3>
			<div class="inside">
				<p class="postbox-description">This option allows you to delete all stored coordinates from the database.</p>
				<hr />
				<p class="secondary-settings-button-block">
					<input type="submit" name="Submit" class="button-secondary secondary-settings-button-override" id="clear-database" value="<?php esc_attr_e( 'Clear Heatmap Database' ); ?>" />
				</p>
			</div>
		</div> <!-- end .postbox -->
	</div>
	<?php
}
?>
