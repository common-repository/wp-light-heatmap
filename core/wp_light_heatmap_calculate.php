<?php

/**
 * Calculation of the position of the dots.
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/core
 */

add_action( 'wp_ajax_nopriv_wp_light_heatmap_calculate_neighbors', 'wp_light_heatmap_calculate_neighbors' );
add_action( 'wp_ajax_wp_light_heatmap_calculate_neighbors', 'wp_light_heatmap_calculate_neighbors' );

/**
 * AJAX function for calculating dots positions.
 *
 * This function is used to identify neighboring
 * points and determine the relationship between them
 * for displaying ("neighbors" dots positions).
 *
 * @since    1.0.0
 */
function wp_light_heatmap_calculate_neighbors() {
	// Get the data from the post
	$_POST = array_map( 'stripslashes_deep', $_POST );

	$dots           = json_decode( $_POST['dot_collection'] );
	$dot_collection = $dots->dot_collection;

	// Get the number of dots
	$number_of_dots = count( $dot_collection );

	// Set up constants for neighborhood distances
	$RED_NEIGHBORHOOD          = $number_of_dots * 0.110;
	$DARK_ORANGE_NEIGHBORHOOD  = $number_of_dots * 0.100;
	$ORANGE_NEIGHBORHOOD       = $number_of_dots * 0.090;
	$LIGHT_ORANGE_NEIGHBORHOOD = $number_of_dots * 0.080;
	$YELLOW_NEIGHBORHOOD       = $number_of_dots * 0.050;
	$LIGHT_YELLOW_NEIGHBORHOOD = $number_of_dots * 0.03;
	$LIGHT_GREEN_NEIGHBORHOOD  = $number_of_dots * 0.025;
	$GREEN_NEIGHBORHOOD        = $number_of_dots * 0.02;

	// Set up the color profile
	$RED_ID          = 'red';
	$DARK_ORANGE_ID  = 'dark-orange';
	$ORANGE_ID       = 'orange';
	$LIGHT_ORANGE_ID = 'light-orange';
	$YELLOW_ID       = 'yellow';
	$LIGHT_YELLOW_ID = 'light-yellow';
	$LIGHT_GREEN_ID  = 'light-green';
	$GREEN_ID        = 'green';
	$DARK_GREEN_ID   = 'dark-green';

	// Decide on the dot size
	$DOT_SIZE = 10 . 'px';

	// Determine the max distance for the neighborhood
	$max_distance_to_neighbor = 20 * 20; // squared to remove the sq. root from distance calc

	// Sanitize and escape fields
	foreach ( $dot_collection as &$dot ) {
		$dot->x_coord = intval( esc_attr( $dot->x_coord ) );
		$dot->y_coord = intval( esc_attr( $dot->y_coord ) );
		$dot->timestamp = intval( esc_attr( $dot->timestamp ) );
		$dot->heatmap_user_id = esc_attr( $dot->heatmap_user_id );
		$dot->created_date = esc_attr( $dot->created_date );
		$dot->selector = sanitize_text_field( $dot->selector );
		$dot->url = esc_url( $dot->url );
	}

	// Loop through each dot
	$returner = '';
	foreach ( $dot_collection as $dot ) {
		// Clear number of neighbors
		$dot->number_of_neighbors = 0;

		// Inner Loop through each dot
		foreach ( $dot_collection as $neighbor_dot ) {
			// Calculate the distance and add a neighbor if appropriate
			$distance = ( $dot->x_coord - $neighbor_dot->x_coord ) * ( $dot->x_coord - $neighbor_dot->x_coord ) +
						( $dot->y_coord - $neighbor_dot->y_coord ) * ( $dot->y_coord - $neighbor_dot->y_coord );
			if ( $distance <= $max_distance_to_neighbor ) {
				$dot->number_of_neighbors = $dot->number_of_neighbors + 1;
			}
		}

		// Calculate the current ID for a dot with this many neighbors
		if ( $dot->number_of_neighbors > $RED_NEIGHBORHOOD ) {
			$dot->dot_id = $RED_ID; } elseif ( $dot->number_of_neighbors > $DARK_ORANGE_NEIGHBORHOOD ) {
			$dot->dot_id = $DARK_ORANGE_ID; } elseif ( $dot->number_of_neighbors > $ORANGE_NEIGHBORHOOD ) {
				$dot->dot_id = $ORANGE_ID; } elseif ( $dot->number_of_neighbors > $LIGHT_ORANGE_NEIGHBORHOOD ) {
				$dot->dot_id = $LIGHT_ORANGE_ID; } elseif ( $dot->number_of_neighbors > $YELLOW_NEIGHBORHOOD ) {
					$dot->dot_id = $YELLOW_ID; } elseif ( $dot->number_of_neighbors > $LIGHT_YELLOW_NEIGHBORHOOD ) {
						$dot->dot_id = $LIGHT_YELLOW_ID; } elseif ( $dot->number_of_neighbors > $GREEN_NEIGHBORHOOD ) {
						$dot->dot_id = $GREEN_ID; } else {
							$dot->dot_id = $DARK_GREEN_ID; }

						// Build the dot for the response
						$top       = ( $dot->y_coord - ( $DOT_SIZE / 2 ) ) . 'px';
						$left      = ( $dot->x_coord - ( $DOT_SIZE / 2 ) ) . 'px';
						$id        = $dot->dot_id;
						$returner .= "<div class='dot' id='$id' style='top: $top; left: $left; width: $DOT_SIZE; height: $DOT_SIZE; border-radius: $DOT_SIZE; position: absolute;'></div>";
	}

	echo $returner;
	exit;
}

