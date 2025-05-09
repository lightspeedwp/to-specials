<?php
/**
 * LSX_TO_Specials_Frontend
 *
 * @package   LSX_TO_Specials_Frontend
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2018 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_Specials_Frontend
 * @author  LightSpeed
 */

class LSX_TO_Specials_Frontend {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Runs on init after all files have been parsed.
	 */
	public function init() {
		if ( ! class_exists( 'LSX_Currencies' ) ) {
			add_filter( 'lsx_to_custom_field_query', array( $this, 'price_filter' ), 5, 10 );
		}

		add_filter( 'lsx_to_custom_field_query', array( $this, 'terms_conditions_filter' ), 5, 10 );
	}

	/**
	 * Adds in additional info for the price custom field
	 */
	public function price_filter( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'special' && 'price' === $meta_key ) {
			$price_type = get_post_meta( get_the_ID(), 'price_type', true );
			$value = preg_replace( '/[^0-9,.]/', '', $value );
			$value = ltrim( $value, '.' );
			$value = str_replace( ', ', '', $value );
			$value = number_format( (int) $value, 2 );
			$tour_operator = tour_operator();
			$currency = '';

			if ( is_object( $tour_operator ) && isset( $tour_operator->options['general'] ) && is_array( $tour_operator->options['general'] ) ) {
				if ( isset( $tour_operator->options['general']['currency'] ) && ! empty( $tour_operator->options['general']['currency'] ) ) {
					$currency = $tour_operator->options['general']['currency'];
					$currency = '<span class="currency-icon ' . mb_strtolower( $currency ) . '">' . $currency . '</span>';
				}
			}

			switch ( $price_type ) {
				case 'per_person':
				case 'per_person_per_night':
				case 'per_person_sharing':
				case 'per_person_sharing_per_night':
					$value = $currency . $value . ' ' . ucwords( str_replace( '_', ' ', $price_type ) ) . '';
					$value = str_replace( 'Per Person', 'P/P', $value );
				break;

				case 'total_percentage':
					$value .= '% ' . __( 'Off', 'to-specials' ) . '';
					$before = str_replace( 'from price', '', $before );
				break;

				case 'none':
				default:
					$value = $currency . $value;
				break;
			}

			$html = $before . $value . $after;
		}

		return $html;
	}

	/**
	 * Filters text area type filters
	 */
	public function terms_conditions_filter( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'special' && 'terms_conditions' === $meta_key ) {
			$html = $before . '<div class="entry-content">' . apply_filters( 'the_content', wpautop( $value ) ) . '</div>' . $after;

		}

		return $html;
	}
}

new LSX_TO_Specials_Frontend();
