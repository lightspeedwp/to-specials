<?php
/**
 * Specials Offer Schema Graph Piece
 *
 * Outputs schema.org Offer markup for the `special` post type. When Yoast SEO
 * is active the class is registered as a graph piece via
 * LSX_TO_Specials::add_graph_pieces(). When Yoast is inactive
 * LSX_TO_Specials::output_standalone_schema() calls generate() directly.
 *
 * The piece is self-contained and depends only on the Tour Operator core
 * schema helpers (lsx\schema\Helpers, core 2.2+), following the same pattern
 * as the core Trip, Accommodation and Destination pieces.
 *
 * @package to-specials
 */

use lsx\schema\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates schema.org Offer data for a single special.
 */
class LSX_TO_Specials_Schema {

	/**
	 * Yoast schema context (null when Yoast is not active).
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null
	 */
	protected $context;

	/**
	 * Post ID being processed.
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * Post object being processed.
	 *
	 * @var \WP_Post|null
	 */
	protected $post;

	/**
	 * Canonical URL for the current post.
	 *
	 * @var string
	 */
	protected $canonical;

	/**
	 * Constructor.
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null $context Yoast context, or null when Yoast is inactive.
	 */
	public function __construct( $context = null ) {
		$this->context   = $context;
		$this->post_id   = ( null !== $context ) ? (int) $context->id : (int) get_the_ID();
		$this->post      = get_post( $this->post_id );
		$this->canonical = (string) get_permalink( $this->post_id );
	}

	/**
	 * Determines whether this piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {
		return is_singular( 'special' );
	}

	/**
	 * Generates and returns the Offer schema data array.
	 *
	 * @return array Schema.org Offer data.
	 */
	public function generate() {
		if ( ! is_object( $this->post ) ) {
			return array();
		}

		$data = array(
			'@type'            => 'Offer',
			'@id'              => $this->canonical . '#/schema/offer/' . $this->post_id,
			'name'             => get_the_title( $this->post_id ),
			'description'      => Helpers::strip_to_text( apply_filters( 'the_content', $this->post->post_content ) ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'url'              => $this->canonical,
			'mainEntityOfPage' => array( '@id' => $this->canonical ),
		);

		if ( null !== $this->context && ! empty( $this->context->site_represents_reference ) ) {
			$data['offeredBy'] = $this->context->site_represents_reference;
		}

		$data = $this->add_date( $data, 'availabilityStarts', 'booking_validity_start' );
		$data = $this->add_date( $data, 'availabilityEnds', 'booking_validity_end' );
		$data = $this->add_date( $data, 'priceValidUntil', 'booking_validity_end' );
		$data = $this->add_price( $data );
		$data = $this->add_items_offered( $data );
		$data = $this->add_articles( $data );
		$data = $this->add_image( $data );

		/**
		 * Filter the complete Special Offer schema data array.
		 *
		 * @param array $data    Offer schema data.
		 * @param int   $post_id Current special post ID.
		 */
		return (array) apply_filters( 'lsx_to_schema_special_data', $data, $this->post_id );
	}

	/**
	 * Adds a date property as an ISO 8601 date from a stored date field.
	 *
	 * @param array  $data     Offer data.
	 * @param string $data_key Schema property.
	 * @param string $meta_key Meta key holding the raw date or timestamp.
	 * @return array
	 */
	protected function add_date( array $data, $data_key, $meta_key ) {
		$date = Helpers::format_iso_date( Helpers::get_meta( $this->post_id, $meta_key ) );
		if ( '' !== $date ) {
			$data[ $data_key ] = $date;
		}
		return $data;
	}

	/**
	 * Adds the price, currency and structured price specification.
	 *
	 * @param array $data Offer data.
	 * @return array
	 */
	protected function add_price( array $data ) {
		$price = Helpers::normalise_price( Helpers::get_meta( $this->post_id, 'price' ) );
		if ( '' === $price ) {
			return $data;
		}

		$currency = Helpers::get_currency();

		$data['price']         = $price;
		$data['priceCurrency'] = $currency;
		$data['category']      = __( 'Special', 'to-specials' );
		$data['availability']  = 'https://schema.org/LimitedAvailability';

		$price_type = Helpers::get_meta( $this->post_id, 'price_type' );
		if ( '' !== $price_type && 'none' !== $price_type ) {
			$data['priceSpecification'] = array(
				'@type'         => 'PriceSpecification',
				'price'         => $price,
				'priceCurrency' => $currency,
				'unitText'      => lsx_to_get_price_type_label( $price_type ),
			);
		}

		return $data;
	}

	/**
	 * Merges the related tours and accommodation into a single itemOffered
	 * value, typed appropriately, without overwriting each other.
	 *
	 * @param array $data Offer data.
	 * @return array
	 */
	protected function add_items_offered( array $data ) {
		$items = array_merge(
			$this->get_related_items( 'tour_to_special', 'TouristTrip' ),
			$this->get_related_items( 'accommodation_to_special', 'LodgingBusiness' )
		);

		if ( ! empty( $items ) ) {
			$data['itemOffered'] = 1 === count( $items ) ? $items[0] : $items;
		}

		return $data;
	}

	/**
	 * Builds typed nodes for the posts linked through a relationship field.
	 *
	 * @param string $meta_key Relationship meta key.
	 * @param string $type     Schema.org type for the nodes.
	 * @return array[]
	 */
	protected function get_related_items( $meta_key, $type ) {
		$items = array();
		foreach ( Helpers::get_meta_array( $this->post_id, $meta_key ) as $related_id ) {
			$title = get_the_title( (int) $related_id );
			if ( '' !== $title ) {
				$items[] = array(
					'@type' => $type,
					'name'  => $title,
				);
			}
		}
		return $items;
	}

	/**
	 * Adds the related blog posts as subjectOf articles.
	 *
	 * @param array $data Offer data.
	 * @return array
	 */
	protected function add_articles( array $data ) {
		$articles = array();
		foreach ( Helpers::get_meta_array( $this->post_id, 'post_to_special' ) as $article_id ) {
			$title = get_the_title( (int) $article_id );
			if ( '' !== $title ) {
				$articles[] = array(
					'@type'    => 'Article',
					'headline' => $title,
					'url'      => (string) get_permalink( (int) $article_id ),
				);
			}
		}

		if ( ! empty( $articles ) ) {
			$data['subjectOf'] = $articles;
		}
		return $data;
	}

	/**
	 * Adds the image from the Yoast context, falling back to the featured image.
	 *
	 * @param array $data Offer data.
	 * @return array
	 */
	protected function add_image( array $data ) {
		if ( null !== $this->context && ! empty( $this->context->has_image ) ) {
			$data['image'] = array( '@id' => $this->canonical . Helpers::primary_image_hash() );
		} else {
			$thumbnail_url = get_the_post_thumbnail_url( $this->post_id, 'large' );
			if ( $thumbnail_url ) {
				$data['image'] = esc_url( $thumbnail_url );
			}
		}
		return $data;
	}
}
