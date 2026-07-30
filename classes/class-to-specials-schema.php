<?php
/**
 * The Offer Schema for Specials
 *
 * @package tour-operator
 */

/**
 * Returns schema Offer data for Special posts.
 *
 * @since 10.2
 */
class LSX_TO_Specials_Schema extends LSX_TO_Schema_Graph_Piece {

	/**
	 * Constructor.
	 *
	 * @param \WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		$this->post_type = 'special';
		parent::__construct( $context );
	}

	/**
	 * Returns Offer data.
	 *
	 * @return array $data Offer data.
	 */
	public function generate() {
		$tour_list  = get_post_meta( $this->context->id, 'tour_to_special', false );
		$accom_list = get_post_meta( $this->context->id, 'accommodation_to_special', false );
		$data       = array(
			'@type'            => 'Offer',
			'@id'              => $this->context->canonical . '#/schema/offer/' . $this->post->ID,
			'name'             => get_the_title( $this->post->ID ),
			'description'      => \lsx\schema\Helpers::strip_to_text( apply_filters( 'the_content', $this->post->post_content ) ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'url'              => $this->post_url,
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
		);

		if ( $this->context->site_represents_reference ) {
			$data['offeredBy'] = $this->context->site_represents_reference;
		}

		$data = $this->add_availability( $data, 'availabilityStarts', 'booking_validity_start' );
		$data = $this->add_availability( $data, 'availabilityEnds', 'booking_validity_end' );
		$data = $this->add_availability( $data, 'priceValidUntil', 'booking_validity_end' );
		$data = $this->get_price( $data );
		$data = $this->add_items_offered( $data, $tour_list, $accom_list );

		$data = $this->add_articles( $data );
		$data = \lsx\legacy\Schema_Utils::add_image( $data, $this->context );

		return $data;
	}

	/**
	 * Adds a date-based field as an ISO 8601 date, converting the stored
	 * CMB2 date field value.
	 *
	 * @param array  $data     Offer data.
	 * @param string $data_key The schema property to set.
	 * @param string $meta_key The meta key to read the raw date from.
	 * @return array $data Offer data.
	 */
	public function add_availability( $data, $data_key, $meta_key ) {
		$raw  = get_post_meta( $this->context->id, $meta_key, true );
		$date = \lsx\schema\Helpers::format_iso_date( $raw );
		if ( '' !== $date ) {
			$data[ $data_key ] = $date;
		}

		return $data;
	}

	/**
	 * Gets the single special post and adds it as a special "Offer".
	 *
	 * @param  array $data An array of offers already added.
	 * @return array $data
	 */
	public function get_price( $data ) {
		$price         = get_post_meta( $this->context->id, 'price', true );
		$currency      = \lsx\schema\Helpers::get_currency();
		$numeric_price = \lsx\schema\Helpers::normalise_price( $price );

		if ( '' !== $numeric_price ) {
			$data['price']         = $numeric_price;
			$data['priceCurrency'] = $currency;
			$data['category']      = __( 'Special', 'to-specials' );
			$data['availability']  = 'https://schema.org/LimitedAvailability';

			$price_type = get_post_meta( $this->context->id, 'price_type', true );
			if ( false !== $price_type && '' !== $price_type && 'none' !== $price_type ) {
				$data['priceSpecification'] = array(
					'@type'         => 'PriceSpecification',
					'price'         => $numeric_price,
					'priceCurrency' => $currency,
					'unitText'      => lsx_to_get_price_type_label( $price_type ),
				);
			}
		}
		return $data;
	}

	/**
	 * Merges the related tour and accommodation posts into a single
	 * itemOffered value, typed appropriately, without overwriting each other.
	 *
	 * @param array $data       Offer data.
	 * @param array $tour_list  Related tour post IDs.
	 * @param array $accom_list Related accommodation post IDs.
	 * @return array $data Offer data.
	 */
	public function add_items_offered( $data, $tour_list, $accom_list ) {
		$items_offered = array();
		$items_offered = array_merge( $items_offered, \lsx\legacy\Schema_Utils::get_item_reviewed( $tour_list, 'TouristTrip' ) );
		$items_offered = array_merge( $items_offered, \lsx\legacy\Schema_Utils::get_item_reviewed( $accom_list, 'LodgingBusiness' ) );

		if ( ! empty( $items_offered ) ) {
			$data['itemOffered'] = 1 === count( $items_offered ) ? $items_offered[0] : $items_offered;
		}

		return $data;
	}
}
