<?php
/**
 * The Trip Schema for Tours
 *
 * @package tour-operator
 */

/**
 * Returns schema Review data.
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
	 * Returns Review data.
	 *
	 * @return array $data Review data.
	 */
	public function generate() {
		$tour_list  = get_post_meta( get_the_ID(), 'tour_to_special', false );
		$accom_list = get_post_meta( get_the_ID(), 'accommodation_to_special', false );
		$data       = array(
			'@type'            => array(
				'Offer',
			),
			'@id'              => $this->context->canonical . '#special',
			'name'             => $this->post->post_title,
			'description'      => wp_strip_all_tags( $this->post->post_content ),
			'url'              => $this->post_url,
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
		);

		if ( $this->context->site_represents_reference ) {
			$data['offeredBy'] = $this->context->site_represents_reference;
		}

		$data = $this->add_custom_field( $data, 'availabilityStarts', 'booking_validity_start' );
		$data = $this->add_custom_field( $data, 'availabilityEnds', 'booking_validity_end' );
		$data = $this->add_custom_field( $data, 'priceValidUntil', 'booking_validity_end' );
		$data = $this->add_custom_field( $data, 'priceValidUntil', 'booking_validity_end' );
		$data = $this->get_price( $data );

		$data['itemOffered'] = \lsx\legacy\Schema_Utils::get_item_reviewed( $tour_list, 'Product' );
		$data['itemOffered'] = \lsx\legacy\Schema_Utils::get_item_reviewed( $accom_list, 'Product' );

		$data = $this->add_articles( $data );
		$data = \lsx\legacy\Schema_Utils::add_image( $data, $this->context );

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
		$currency      = 'USD';
		$tour_operator = tour_operator();
		if ( is_object( $tour_operator ) && isset( $tour_operator->options['general'] ) && is_array( $tour_operator->options['general'] ) ) {
			if ( isset( $tour_operator->options['general']['currency'] ) && ! empty( $tour_operator->options['general']['currency'] ) ) {
				$currency = $tour_operator->options['general']['currency'];
			}
		}
		if ( false !== $price && '' !== $price ) {
			$data['price']         = $price;
			$data['priceCurrency'] = $currency;
			$data['category']      = __( 'Special', 'tour-operator-specials' );
			$data['availability']  = 'https://schema.org/LimitedAvailability';

			$price_type = get_post_meta( $this->context->id, 'price_type', true );
			if ( false !== $price_type && '' !== $price_type && 'none' !== $price_type ) {
				$data['PriceSpecification'] = lsx_to_get_price_type_label( $price_type );
			}
		}
		return $data;
	}
}
