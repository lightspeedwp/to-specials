<?php
/**
 * LSX_TO_Specials_Schema
 *
 * @package   LSX_TO_Specials_Schema
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2018 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_Specials_Schema
 * @author  LightSpeed
 */

class LSX_TO_Specials_Schema extends LSX_TO_Specials {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();
		add_action( 'wp_head', array( $this, 'specials_single_schema' ), 1499 );
	}

	/**
	 * Creates the schema for the specials post type
	 *
	 * @since 1.0.0
	 * @return    object    A single instance of this class.
	 */
	public function specials_single_schema() {
		if ( is_singular( 'special' ) ) {

		$destination_list_special = get_post_meta( get_the_ID(), 'destination_to_special', false );
		$destination_list_schema = array();
		$url_option = get_the_permalink();
		$special_title = get_the_title();
		$primary_url = get_the_permalink();
		$special_content = wp_strip_all_tags( get_the_content() );
		$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		$price = get_post_meta( get_the_ID(), 'price', false );
		$start_validity = get_post_meta( get_the_ID(), 'booking_validity_start', false );
		$end_validity = get_post_meta( get_the_ID(), 'booking_validity_end', false );
	

		if ( ! empty( $destination_list_special ) ) {
			foreach( $destination_list_special as $single_destination ) {
				$url_option   = get_the_permalink() . '#destination-' . $i;
				$destination_name = get_the_title($single_destination);
				$schema_day       = array(
				"@type" => "PostalAddress",
				"addressLocality" => $destination_name,
			);
				$destination_list_schema[] = $schema_day;
				}
			}
			$meta = array(
				array(
					"@context" => "http://schema.org",
					"@type" => array("Trip", "ProfessionalService", "Offer"),
					"offers" => array(
					"@type" => "Offer",
					"price" => $price,
					"availabilityStarts" => $start_validity,
					"availabilityEnds" => $end_validity,
					),
					"address" => $destination_list_schema,
					"telephone" => "0216713090",
					"priceRange" => $price,
					"description" => $special_content,
					"image" => $thumb_url,
					"name" => $special_title,
					"provider" => "Southern Destinations",
					"url" => $primary_url,
				),
			);
			$output = wp_json_encode( $meta, JSON_UNESCAPED_SLASHES  );
			?>
			<script type="application/ld+json">
				<?php echo wp_kses_post( $output ); ?>
			</script>
			<?php
		}
	}
}

new LSX_TO_Specials_Schema();
