<?php
/**
 * Regression tests for the Specials Offer schema piece.
 *
 * @package to-specials
 */

use PHPUnit\Framework\TestCase;

require_once dirname( __DIR__, 2 ) . '/classes/class-to-specials-schema.php';

/**
 * Tests for LSX_TO_Specials_Schema.
 */
class SpecialSchemaTest extends TestCase {

	const SPECIAL_ID = 10;

	/**
	 * Sets up a published, priced special with a booking window.
	 */
	protected function setUp(): void {
		lsx_to_test_reset();
		$GLOBALS['lsx_to_test']['currency'] = 'zar';

		lsx_to_test_add_post(
			self::SPECIAL_ID,
			array(
				'post_title'   => 'Winter &#8211; Safari Deal',
				'post_type'    => 'special',
				'post_content' => '<p>Save on a <strong>winter</strong> safari &amp; more.</p>',
			)
		);
		lsx_to_test_add_meta(
			self::SPECIAL_ID,
			array(
				'price'                  => 'R 12,500',
				'price_type'             => 'per_person_sharing',
				'booking_validity_start' => (string) gmmktime( 0, 0, 0, 11, 1, 2026 ),
				'booking_validity_end'   => (string) gmmktime( 0, 0, 0, 2, 28, 2027 ),
			)
		);
	}

	/**
	 * Builds the piece with a Yoast-like context.
	 *
	 * @param array $overrides Context properties to override.
	 * @return array Generated schema.
	 */
	private function generate( array $overrides = array() ) {
		$context = (object) array_merge(
			array(
				'id'                        => self::SPECIAL_ID,
				'has_image'                 => false,
				'site_represents_reference' => array( '@id' => 'https://example.test/#organization' ),
			),
			$overrides
		);
		return ( new LSX_TO_Specials_Schema( $context ) )->generate();
	}

	public function test_offer_core_properties() {
		$data = $this->generate();

		$this->assertSame( 'Offer', $data['@type'] );
		$this->assertSame( 'https://example.test/special/10/#/schema/offer/10', $data['@id'] );
		$this->assertSame( "Winter \u{2013} Safari Deal", $data['name'] );
		$this->assertSame( 'Save on a winter safari & more.', $data['description'] );
		$this->assertSame( 'https://example.test/special/10/', $data['url'] );
		$this->assertSame( array( '@id' => 'https://example.test/special/10/' ), $data['mainEntityOfPage'] );
		$this->assertSame( array( '@id' => 'https://example.test/#organization' ), $data['offeredBy'] );
	}

	public function test_booking_window_maps_to_iso_dates_with_a_single_price_valid_until() {
		$data = $this->generate();

		$this->assertSame( '2026-11-01', $data['availabilityStarts'] );
		$this->assertSame( '2027-02-28', $data['availabilityEnds'] );
		$this->assertSame( '2027-02-28', $data['priceValidUntil'] );
		$this->assertSame( 1, substr_count( (string) json_encode( $data ), 'priceValidUntil' ) );
	}

	public function test_invalid_dates_are_omitted() {
		lsx_to_test_add_meta(
			self::SPECIAL_ID,
			array(
				'booking_validity_start' => 'not a date',
				'booking_validity_end'   => '',
			)
		);

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'availabilityStarts', $data );
		$this->assertArrayNotHasKey( 'availabilityEnds', $data );
		$this->assertArrayNotHasKey( 'priceValidUntil', $data );
	}

	public function test_price_is_numeric_with_a_structured_price_specification() {
		$data = $this->generate();

		$this->assertSame( '12500', $data['price'] );
		$this->assertSame( 'ZAR', $data['priceCurrency'] );
		$this->assertSame( 'https://schema.org/LimitedAvailability', $data['availability'] );
		$this->assertArrayNotHasKey( 'PriceSpecification', $data );
		$this->assertSame(
			array(
				'@type'         => 'UnitPriceSpecification',
				'price'         => '12500',
				'priceCurrency' => 'ZAR',
				'unitText'      => 'Per Person Sharing',
			),
			$data['priceSpecification']
		);
	}

	public function test_ended_special_is_marked_discontinued() {
		lsx_to_test_add_meta(
			self::SPECIAL_ID,
			array(
				'booking_validity_start' => (string) gmmktime( 0, 0, 0, 6, 1, 2020 ),
				'booking_validity_end'   => (string) gmmktime( 0, 0, 0, 9, 1, 2020 ),
			)
		);

		$data = $this->generate();

		$this->assertSame( 'https://schema.org/Discontinued', $data['availability'] );
		$this->assertSame( '2020-09-01', $data['availabilityEnds'] );
	}

	public function test_per_person_label_falls_back_when_core_has_none() {
		lsx_to_test_add_meta( self::SPECIAL_ID, array( 'price_type' => 'per_person' ) );

		$this->assertSame( 'Per Person', $this->generate()['priceSpecification']['unitText'] );
	}

	public function test_no_price_type_keeps_the_price_without_a_specification() {
		lsx_to_test_add_meta( self::SPECIAL_ID, array( 'price_type' => 'none' ) );

		$data = $this->generate();

		$this->assertSame( '12500', $data['price'] );
		$this->assertArrayNotHasKey( 'priceSpecification', $data );
	}

	public function test_percentage_specials_emit_no_price_fields() {
		lsx_to_test_add_meta(
			self::SPECIAL_ID,
			array(
				'price'      => '20',
				'price_type' => 'total_percentage',
			)
		);

		$data = $this->generate();

		foreach ( array( 'price', 'priceCurrency', 'priceSpecification', 'category', 'availability' ) as $key ) {
			$this->assertArrayNotHasKey( $key, $data, $key );
		}
		$this->assertSame( '2027-02-28', $data['availabilityEnds'] );
	}

	public function test_unparseable_price_emits_no_price_fields() {
		lsx_to_test_add_meta( self::SPECIAL_ID, array( 'price' => 'Price on request' ) );

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'price', $data );
		$this->assertArrayNotHasKey( 'priceSpecification', $data );
		$this->assertArrayNotHasKey( 'availability', $data );
	}

	public function test_tours_and_accommodation_are_merged_without_overwriting() {
		lsx_to_test_add_post( 20, array( 'post_title' => 'Cape &#8211; Safari', 'post_type' => 'tour' ) );
		lsx_to_test_add_post( 21, array( 'post_title' => 'Draft Tour', 'post_type' => 'tour', 'post_status' => 'draft' ) );
		lsx_to_test_add_post( 30, array( 'post_title' => 'River Lodge', 'post_type' => 'accommodation' ) );
		lsx_to_test_add_meta(
			self::SPECIAL_ID,
			array(
				'tour_to_special'          => array( array( '20', '21', '20' ) ),
				'accommodation_to_special' => array( 30, 30 ),
			)
		);

		$this->assertSame(
			array(
				array(
					'@type' => 'TouristTrip',
					'name'  => "Cape \u{2013} Safari",
					'url'   => 'https://example.test/tour/20/',
				),
				array(
					'@type' => 'LodgingBusiness',
					'name'  => 'River Lodge',
					'url'   => 'https://example.test/accommodation/30/',
				),
			),
			$this->generate()['itemOffered']
		);
	}

	public function test_single_offered_item_is_an_object_and_none_is_omitted() {
		$this->assertArrayNotHasKey( 'itemOffered', $this->generate() );

		lsx_to_test_add_post( 30, array( 'post_title' => 'River Lodge', 'post_type' => 'accommodation' ) );
		lsx_to_test_add_meta( self::SPECIAL_ID, array( 'accommodation_to_special' => array( 30 ) ) );

		$this->assertSame( 'LodgingBusiness', $this->generate()['itemOffered']['@type'] );
	}

	public function test_related_posts_are_published_subject_of_articles() {
		lsx_to_test_add_post( 40, array( 'post_title' => 'Packing tips' ) );
		lsx_to_test_add_post( 41, array( 'post_title' => 'Unpublished', 'post_status' => 'draft' ) );
		lsx_to_test_add_meta( self::SPECIAL_ID, array( 'post_to_special' => array( 40, 41 ) ) );

		$this->assertSame(
			array(
				array(
					'@type'    => 'Article',
					'headline' => 'Packing tips',
					'url'      => 'https://example.test/post/40/',
				),
			),
			$this->generate()['subjectOf']
		);
	}

	/**
	 * Non-public post statuses.
	 *
	 * @return array
	 */
	public static function unpublished_statuses() {
		return array(
			'draft'   => array( 'draft' ),
			'pending' => array( 'pending' ),
			'private' => array( 'private' ),
			'future'  => array( 'future' ),
		);
	}

	/**
	 * @dataProvider unpublished_statuses
	 *
	 * @param string $status Post status.
	 */
	public function test_unpublished_specials_emit_nothing( $status ) {
		$GLOBALS['lsx_to_test']['posts'][ self::SPECIAL_ID ]->post_status = $status;

		$this->assertSame( array(), $this->generate() );
	}

	public function test_missing_post_emits_nothing() {
		$this->assertSame( array(), $this->generate( array( 'id' => 999 ) ) );
	}

	public function test_without_context_the_queried_special_is_used_not_the_loop_post() {
		lsx_to_test_add_post( 50, array( 'post_title' => 'Some other post' ) );
		$GLOBALS['lsx_to_test']['queried'] = self::SPECIAL_ID;
		$GLOBALS['lsx_to_test']['loop_id'] = 50;

		$data = ( new LSX_TO_Specials_Schema() )->generate();

		$this->assertSame( 'https://example.test/special/10/#/schema/offer/10', $data['@id'] );
	}

	public function test_without_context_falls_back_to_the_loop_post() {
		$GLOBALS['lsx_to_test']['loop_id'] = self::SPECIAL_ID;

		$data = ( new LSX_TO_Specials_Schema() )->generate();

		$this->assertSame( 'https://example.test/special/10/#/schema/offer/10', $data['@id'] );
	}

	public function test_offered_by_is_omitted_without_a_site_organisation() {
		$this->assertArrayNotHasKey( 'offeredBy', $this->generate( array( 'site_represents_reference' => false ) ) );
	}
}
