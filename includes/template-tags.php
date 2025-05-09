<?php
/**
 * Template Tags
 *
 * @package tour-operator
 * @license GPL-3.0+
 */

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
		$start = gmdate( 'M j, Y', strtotime( $start ) );
		$end = gmdate( 'M j, Y', strtotime( $end ) );

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
				$start = gmdate( 'd M Y', strtotime( $start ) );
			}

			if ( ! empty( $end ) ) {
				$start .= ' - ' . gmdate( 'd M Y', strtotime( $end ) );
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
	// $valid_from = get_the_gmdate( 'M j, Y', get_the_ID() );
	// $valid_to = get_post_meta( get_the_ID(), '_expiration-date', true );

	$valid_from = get_post_meta( get_the_ID(), 'booking_validity_start', true );
	$valid_to = get_post_meta( get_the_ID(), 'booking_validity_end', true );

	if ( ! empty( $valid_from ) ) {
		$valid_from = gmdate( 'd M Y', strtotime( $valid_from ) );
	}

	if ( ! empty( $valid_to ) ) {
		$valid_from .= ' - ' . gmdate( 'd M Y', strtotime( $valid_to ) );
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
