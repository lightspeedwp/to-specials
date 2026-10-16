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
	 * Price type whose price field holds a percentage discount, not an amount.
	 *
	 * @var string
	 */
	const PERCENTAGE_PRICE_TYPE = 'total_percentage';

	/**
	 * Constructor.
	 *
	 * Without a Yoast context the post is taken from the queried object, which
	 * stays reliable in wp_head even if a custom loop has changed the global
	 * $post, falling back to get_the_ID().
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null $context Yoast context, or null when Yoast is inactive.
	 */
	public function __construct( $context = null ) {
		$this->context = $context;
		if ( null !== $context ) {
			$this->post_id = (int) $context->id;
		} else {
			$queried_id    = (int) get_queried_object_id();
			$this->post_id = $queried_id > 0 ? $queried_id : (int) get_the_ID();
		}
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
	 * Returns an empty array for a missing post or a Special that is not
	 * published, so drafts, private posts and previews never emit an Offer.
	 *
	 * @return array Schema.org Offer data, or an empty array.
	 */
	public function generate() {
		if ( ! is_object( $this->post ) || 'publish' !== get_post_status( $this->post_id ) ) {
			return array();
		}

		$data = array(
			'@type'            => 'Offer',
			'@id'              => $this->canonical . '#/schema/offer/' . $this->post_id,
			'name'             => $this->get_plain_title( $this->post_id ),
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
	 * Gets a post title as plain text, with texturised entities decoded.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	protected function get_plain_title( $post_id ) {
		return Helpers::strip_to_text( get_the_title( $post_id ) );
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
	 * Percentage-off specials store a percentage in the price field. An Offer
	 * has no valid property for a percentage discount, and publishing it as
	 * an amount in the site currency would be wrong, so no price fields are
	 * output for them.
	 *
	 * @param array $data Offer data.
	 * @return array
	 */
	protected function add_price( array $data ) {
		$price_type = Helpers::get_meta( $this->post_id, 'price_type' );
		if ( self::PERCENTAGE_PRICE_TYPE === $price_type ) {
			return $data;
		}

		$price = Helpers::normalise_price( Helpers::get_meta( $this->post_id, 'price' ) );
		if ( '' === $price ) {
			return $data;
		}

		$currency = Helpers::get_currency();

		$data['price']         = $price;
		$data['priceCurrency'] = $currency;
		$data['category']      = __( 'Special', 'to-specials' );
		$data['availability']  = $this->get_availability();

		$label = $this->get_price_type_label( $price_type );
		if ( '' !== $label ) {
			$data['priceSpecification'] = array(
				'@type'         => 'UnitPriceSpecification',
				'price'         => $price,
				'priceCurrency' => $currency,
				'unitText'      => $label,
			);
		}

		return $data;
	}

	/**
	 * Gets the offer availability: Discontinued once the booking window has
	 * ended, so an expired special never claims it can still be booked.
	 *
	 * @return string Schema.org ItemAvailability URL.
	 */
	protected function get_availability() {
		$end = Helpers::format_iso_date( Helpers::get_meta( $this->post_id, 'booking_validity_end' ) );
		if ( '' !== $end && $end < current_time( 'Y-m-d' ) ) {
			return 'https://schema.org/Discontinued';
		}
		return 'https://schema.org/LimitedAvailability';
	}

	/**
	 * Gets the human-readable label for a price type.
	 *
	 * Uses the core label first, falling back to this plugin's field labels,
	 * since core has no label for every type (for example "per_person").
	 *
	 * @param string $price_type Stored price type.
	 * @return string Label, or an empty string for no or unknown type.
	 */
	protected function get_price_type_label( $price_type ) {
		if ( '' === $price_type || 'none' === $price_type ) {
			return '';
		}

		$label = function_exists( 'lsx_to_get_price_type_label' ) ? (string) lsx_to_get_price_type_label( $price_type ) : '';
		if ( '' !== $label ) {
			return $label;
		}

		$labels = array(
			'per_person'                   => __( 'Per Person', 'to-specials' ),
			'per_person_per_night'         => __( 'Per Person Per Night', 'to-specials' ),
			'per_person_sharing'           => __( 'Per Person Sharing', 'to-specials' ),
			'per_person_sharing_per_night' => __( 'Per Person Sharing Per Night', 'to-specials' ),
		);

		return isset( $labels[ $price_type ] ) ? $labels[ $price_type ] : '';
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
	 * Gets the published, unique post IDs linked through a relationship field.
	 *
	 * @param string $meta_key Relationship meta key.
	 * @return int[]
	 */
	protected function get_related_ids( $meta_key ) {
		$ids = array();
		foreach ( Helpers::get_meta_array( $this->post_id, $meta_key ) as $related_id ) {
			$related_id = (int) $related_id;
			if ( $related_id > 0 && ! in_array( $related_id, $ids, true ) && 'publish' === get_post_status( $related_id ) ) {
				$ids[] = $related_id;
			}
		}
		return $ids;
	}

	/**
	 * Builds typed nodes, with name and URL, for the published posts linked
	 * through a relationship field.
	 *
	 * @param string $meta_key Relationship meta key.
	 * @param string $type     Schema.org type for the nodes.
	 * @return array[]
	 */
	protected function get_related_items( $meta_key, $type ) {
		$items = array();
		foreach ( $this->get_related_ids( $meta_key ) as $related_id ) {
			$title = $this->get_plain_title( $related_id );
			if ( '' !== $title ) {
				$items[] = array(
					'@type' => $type,
					'name'  => $title,
					'url'   => (string) get_permalink( $related_id ),
				);
			}
		}
		return $items;
	}

	/**
	 * Adds the published related blog posts as subjectOf articles.
	 *
	 * @param array $data Offer data.
	 * @return array
	 */
	protected function add_articles( array $data ) {
		$articles = array();
		foreach ( $this->get_related_ids( 'post_to_special' ) as $article_id ) {
			$title = $this->get_plain_title( $article_id );
			if ( '' !== $title ) {
				$articles[] = array(
					'@type'    => 'Article',
					'headline' => $title,
					'url'      => (string) get_permalink( $article_id ),
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
