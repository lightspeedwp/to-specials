<?php
/**
 * Template Tags
 *
 * @package tour-operator
 * @license GPL-3.0+
 */

/**
 * Outputs the posts attached special
 *
 * @package 	to-specials
 * @subpackage	template-tags
 * @category 	special
 */
if ( ! function_exists( 'lsx_to_special_posts' ) ) {
	function lsx_to_special_posts() {
		global $lsx_to_archive;

		$args = array(
			'from'		=> 'post',
			'to'		=> 'special',
			'column'	=> '3',
			'before'	=> '<section id="posts" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-posts">' . esc_html__( 'Featured Posts', 'to-specials' ) . '</h2><div id="collapse-posts" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Find the content part in the plugin.
 *
 * @package    tour-operator
 * @subpackage template-tag
 * @category   to-specials
 */
function lsx_to_special_content( $slug, $name = null ) {
	do_action( 'lsx_special_content', $slug, $name );
}

/**
 * Outputs the terms and conditions.
 *
 * @param  $before | string
 * @param  $after | string
 * @param  $echo | boolean
 * @return string
 *
 * @package    tour-operator
 * @subpackage template-tag
 * @category   to-specials
 */
function lsx_to_specials_terms_conditions( $before = '', $after = '', $echo = true ) {
	lsx_to_custom_field_query( 'terms_conditions', $before, $after, $echo );
}

/**
 * Outputs the travel dates.
 *
 * @param  $before | string
 * @param  $after | string
 * @param  $echo | boolean
 * @return string
 *
 * @package    tour-operator
 * @subpackage template-tag
 * @category   to-specials
 */
function lsx_to_travel_dates( $before = '', $after = '', $echo = true ) {
	// --- START: [legacy] migrate content from old fields to new repeatable fields (grouped fields)

	$start = get_post_meta( get_the_ID(), 'travel_dates_start', true );
	$end = get_post_meta( get_the_ID(), 'travel_dates_end', true );

	if ( ! empty( $start ) && ! empty( $end ) ) {
		$start = date( 'M j, Y', strtotime( $start ) );
		$end = date( 'M j, Y', strtotime( $end ) );

		$new_field = array(
			'travel_dates_start' => $start,
			'travel_dates_end'   => $end,
		);

		delete_post_meta( get_the_ID(), 'travel_dates' );
		update_post_meta( get_the_ID(), 'travel_dates', $new_field );

		delete_post_meta( get_the_ID(), 'travel_dates_start' );
		delete_post_meta( get_the_ID(), 'travel_dates_end' );
	}

	// --- END: [legacy] migrate content from old fields to new repeatable fields (grouped fields)

	$return = '';
	$multiple = false;
	$dates = get_post_meta( get_the_ID(), 'travel_dates', false );

	if ( is_array( $dates ) && ! empty( $dates ) ) {
		foreach ( $dates as $key => $value ) {
			$start = false;
			$end = false;

			if ( isset( $value['travel_dates_start'] ) ) {
				$start = $value['travel_dates_start'];
			}

			if ( isset( $value['travel_dates_end'] ) ) {
				$end = $value['travel_dates_end'];
			}

			if ( ! empty( $start ) ) {
				$start = date( 'd M Y', strtotime( $start ) );
			}

			if ( ! empty( $end ) ) {
				$start .= ' - ' . date( 'd M Y', strtotime( $end ) );
			}

			if ( ! empty( $start ) ) {
				if ( ! empty( $return ) ) {
					$multiple = true;
					$return .= '<br>';
				}

				$return .= $start;
			}
		}
	}

	if ( ! empty( $return ) ) {
		if ( true === $multiple ) {
			$return = '<br>' . $return;
		}

		$return = $before . $return . $after;

		if ( $echo ) {
			echo wp_kses_post( $return );
		} else {
			return $return;
		}
	}
}

/**
 * Outputs the specials validity.
 *
 * @param  $before | string
 * @param  $after | string
 * @param  $echo | boolean
 * @return string
 *
 * @package    tour-operator
 * @subpackage template-tag
 * @category   to-specials
 */
function lsx_to_specials_validity( $before = '', $after = '', $echo = true ) {
	// $valid_from = get_the_date( 'M j, Y', get_the_ID() );
	// $valid_to = get_post_meta( get_the_ID(), '_expiration-date', true );

	$valid_from = get_post_meta( get_the_ID(), 'booking_validity_start', true );
	$valid_to = get_post_meta( get_the_ID(), 'booking_validity_end', true );

	if ( ! empty( $valid_from ) ) {
		$valid_from = date( 'd M Y', strtotime( $valid_from ) );
	}

	if ( ! empty( $valid_to ) ) {
		$valid_from .= ' - ' . date( 'd M Y', strtotime( $valid_to ) );
	}

	if ( ! empty( $valid_from ) ) {
		$return = $before . $valid_from . $after;

		if ( $echo ) {
			echo wp_kses_post( $return );
		} else {
			return $return;
		}
	}
}

/**
 * Outputs the connected specials for an accommodation
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	special
 */
function lsx_to_accommodation_specials() {
	global $lsx_archive;

	if ( post_type_exists( 'special' ) && is_singular( 'accommodation' ) ) {
		$args = array(
			'from'		=> 'special',
			'to'		=> 'accommodation',
			'column'	=> '3',
			'before'	=> '<section id="special" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-special">' . __( lsx_to_get_post_type_section_title( 'special', '', 'Featured Specials' ), 'to-specials' ) . '</h2><div id="collapse-special" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected specials for a tour
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	special
 */
function lsx_to_tour_specials() {
	global $lsx_archive;

	if ( post_type_exists( 'special' ) && is_singular( 'tour' ) ) {
		$args = array(
			'from'		=> 'special',
			'to'		=> 'tour',
			'column'	=> '3',
			'before'	=> '<section id="special" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-special">' . __( lsx_to_get_post_type_section_title( 'special', '', 'Featured Specials' ), 'to-specials' ) . '</h2><div id="collapse-special" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected specials for a destination
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	special
 */
function lsx_to_destination_specials() {
	global $lsx_archive;

	if ( post_type_exists( 'special' ) && is_singular( 'destination' ) ) {
		$args = array(
			'from'		=> 'special',
			'to'		=> 'destination',
			'column'	=> '3',
			'before'	=> '<section id="special" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-special">' . __( lsx_to_get_post_type_section_title( 'special', '', 'Featured Specials' ), 'to-specials' ) . '</h2><div id="collapse-special" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}
