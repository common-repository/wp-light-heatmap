<?php

/**
 * Defines possible dot colors, neighbors and other features.
 *
 * @since      1.0.0
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/core
 */

/**
 * Defines neighbors and other features.
 *
 * Set the maximum number of dots for
 * different colors.
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/core
 * @author     WPLightHeatmap Team <wplightheatmap@gmail.com>
 */
class wp_light_heatmap_dot {
	const default_color = '#7cf27e';

	// Set the number of neighbors for different colors
	private $number_of_pixels_for_neighbor = 10;

	public $x_coord             = 0;
	public $y_coord             = 0;
	public $created_date        = '';
	public $number_of_neighbors = 0;
	public $selector            = '';
	public $dot_color           = '';

	public function __construct( $x_coord = 0, $y_coord = 0, $created_date, $selector = '' ) {
		// User settable
		$this->x_coord      = $x_coord;
		$this->y_coord      = $y_coord;
		$this->created_date = $created_date;
		$this->selector     = $selector;
		// Self defined
		$this->number_of_neighbors = 0;
		$this->dot_color           = self::default_color;
	}

	public function __destruct() {
	}
}

/**
 * Defines possible dot collections and addition function.
 *
 * Defines possible dot collections and addition function,
 * creates new instance of wp_light_heatmap_dot.
 *
 * @package    Wp_light_heatmap
 * @subpackage Wp_light_heatmap/core
 * @author     WPLightHeatmap Team <wplightheatmap@gmail.com>
 */
class wp_light_heatmap_dot_collection {
	public $dot_collection = array();

	function add_dot( $x_coord, $y_coord, $created_date, $selector ) {
		$this->dot_collection[] = new wp_light_heatmap_dot( $x_coord, $y_coord, $created_date, $selector );
	}
}

