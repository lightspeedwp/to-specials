<?php
/**
 * PHPUnit bootstrap for the TO Specials schema tests.
 *
 * Stubs the WordPress functions used by the schema piece and backs them with
 * in-memory fixtures, so the tests run without a WordPress install. Fixtures
 * are reset between tests with lsx_to_test_reset().
 *
 * @package to-specials
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals, Universal.Files.SeparateFunctionsFromOO

define( 'ABSPATH', __DIR__ . '/' );

/**
 * Locate the Tour Operator core schema helpers.
 *
 * @return string Path to the helpers file, or an empty string.
 */
function lsx_to_test_core_helpers_path() {
	$candidates = array();
	if ( getenv( 'TO_CORE_PATH' ) ) {
		$candidates[] = rtrim( getenv( 'TO_CORE_PATH' ), '/' );
	}
	$candidates[] = dirname( __DIR__, 3 ) . '/tour-operator';
	$candidates[] = dirname( __DIR__, 2 ) . '/vendor/lightspeedwp/tour-operator';

	foreach ( $candidates as $dir ) {
		$file = $dir . '/includes/classes/schema/class-lsx-to-schema-helpers.php';
		if ( file_exists( $file ) ) {
			return $file;
		}
	}
	return '';
}

$lsx_to_helpers = lsx_to_test_core_helpers_path();
if ( '' === $lsx_to_helpers ) {
	fwrite( STDERR, "Tour Operator core 2.2+ not found. Set TO_CORE_PATH to a tour-operator checkout.\n" );
	exit( 1 );
}
require_once $lsx_to_helpers;

// -----------------------------------------------------------------------------
// Fixtures.
// -----------------------------------------------------------------------------

/**
 * Resets all fixtures.
 */
function lsx_to_test_reset() {
	$GLOBALS['lsx_to_test'] = array(
		'posts'    => array(),
		'meta'     => array(),
		'terms'    => array(),
		'singular' => '',
		'queried'  => 0,
		'loop_id'  => 0,
		'currency' => 'USD',
		'now'      => null,
	);
}
lsx_to_test_reset();

/**
 * Adds a post fixture.
 *
 * @param int   $id   Post ID.
 * @param array $args Post fields (post_title, post_type, post_status, post_content, post_excerpt).
 */
function lsx_to_test_add_post( $id, array $args = array() ) {
	$GLOBALS['lsx_to_test']['posts'][ $id ] = (object) array_merge(
		array(
			'ID'                => $id,
			'post_title'        => 'Post ' . $id,
			'post_type'         => 'post',
			'post_status'       => 'publish',
			'post_content'      => '',
			'post_excerpt'      => '',
			'post_date_gmt'     => '2026-01-02 03:04:05',
			'post_modified_gmt' => '2026-02-03 04:05:06',
		),
		$args
	);
}

/**
 * Adds meta fixtures. Array values become multiple meta rows.
 *
 * @param int   $id   Post ID.
 * @param array $meta Meta key => value(s).
 */
function lsx_to_test_add_meta( $id, array $meta ) {
	foreach ( $meta as $key => $value ) {
		$GLOBALS['lsx_to_test']['meta'][ $id ][ $key ] = is_array( $value ) ? array_values( $value ) : array( $value );
	}
}

/**
 * Adds term fixtures.
 *
 * @param int    $id       Post ID.
 * @param string $taxonomy Taxonomy.
 * @param array  $names    Term names.
 */
function lsx_to_test_add_terms( $id, $taxonomy, array $names ) {
	$terms = array();
	foreach ( $names as $i => $name ) {
		$terms[] = (object) array(
			'term_id' => $i + 1,
			'name'    => $name,
			'slug'    => strtolower( str_replace( ' ', '-', $name ) ),
		);
	}
	$GLOBALS['lsx_to_test']['terms'][ $id ][ $taxonomy ] = $terms;
}

// -----------------------------------------------------------------------------
// WordPress stubs.
// -----------------------------------------------------------------------------

/**
 * Gets a post fixture.
 *
 * @param int|null $id Post ID.
 * @return object|null
 */
function get_post( $id = null ) {
	return $GLOBALS['lsx_to_test']['posts'][ (int) $id ] ?? null;
}

/**
 * Gets meta fixture rows for a post.
 *
 * @param int    $id     Post ID.
 * @param string $key    Meta key.
 * @param bool   $single Whether to return only the first row.
 * @return mixed
 */
function get_post_meta( $id, $key = '', $single = false ) {
	$rows = $GLOBALS['lsx_to_test']['meta'][ (int) $id ][ $key ] ?? array();
	if ( $single ) {
		return $rows[0] ?? '';
	}
	return $rows;
}

/**
 * Gets a post fixture's raw title.
 *
 * @param int $id Post ID.
 * @return string
 */
function get_the_title( $id = 0 ) {
	$post = get_post( $id );
	return $post ? $post->post_title : '';
}

/**
 * Builds a predictable permalink from the post type and ID.
 *
 * @param int $id Post ID.
 * @return string|false
 */
function get_permalink( $id = 0 ) {
	$post = get_post( $id );
	return $post ? 'https://example.test/' . $post->post_type . '/' . $id . '/' : false;
}

/**
 * Gets a post fixture's status.
 *
 * @param int|null $id Post ID.
 * @return string|false
 */
function get_post_status( $id = null ) {
	$post = get_post( $id );
	return $post ? $post->post_status : false;
}

/**
 * Gets the current loop post ID.
 *
 * @return int
 */
function get_the_ID() {
	return $GLOBALS['lsx_to_test']['loop_id'];
}

/**
 * Gets the queried object ID.
 *
 * @return int
 */
function get_queried_object_id() {
	return $GLOBALS['lsx_to_test']['queried'];
}

/**
 * Checks the fixture's singular post type against the given types.
 *
 * @param string|string[] $post_types Post types to check.
 * @return bool
 */
function is_singular( $post_types = '' ) {
	return '' !== $GLOBALS['lsx_to_test']['singular'] && in_array( $GLOBALS['lsx_to_test']['singular'], (array) $post_types, true );
}

/**
 * Gets term fixtures for a post and taxonomy.
 *
 * @param int    $id       Post ID.
 * @param string $taxonomy Taxonomy.
 * @return object[]|false
 */
function get_the_terms( $id, $taxonomy ) {
	$terms = $GLOBALS['lsx_to_test']['terms'][ (int) $id ][ $taxonomy ] ?? array();
	return empty( $terms ) ? false : $terms;
}

/**
 * Fixtures never produce errors.
 *
 * @param mixed $thing Value to check.
 * @return bool
 */
function is_wp_error( $thing ) {
	return false;
}

/**
 * Gets the thumbnail URL from the _test_thumbnail_url meta fixture.
 *
 * @param int|null $id   Post ID.
 * @param string   $size Image size (ignored).
 * @return string|false
 */
function get_the_post_thumbnail_url( $id = null, $size = 'post-thumbnail' ) {
	$thumb = get_post_meta( $id, '_test_thumbnail_url', true );
	return '' === $thumb ? false : $thumb;
}

/**
 * Returns the value unfiltered.
 *
 * @param string $hook  Hook name.
 * @param mixed  $value Value to filter.
 * @param mixed  ...$args Extra arguments (ignored).
 * @return mixed
 */
function apply_filters( $hook, $value, ...$args ) {
	return $value;
}

/**
 * Returns the text untranslated.
 *
 * @param string $text   Text to translate.
 * @param string $domain Text domain (ignored).
 * @return string
 */
function __( $text, $domain = 'default' ) {
	return $text;
}

/**
 * Strips tags and trims the result.
 *
 * @param string $text          Text to strip.
 * @param bool   $remove_breaks Ignored.
 * @return string
 */
function wp_strip_all_tags( $text, $remove_breaks = false ) {
	return trim( strip_tags( (string) $text ) );
}

/**
 * Strips tags and collapses whitespace.
 *
 * @param string $str Text to sanitize.
 * @return string
 */
function sanitize_text_field( $str ) {
	return trim( preg_replace( '/[\r\n\t ]+/', ' ', strip_tags( (string) $str ) ) );
}

/**
 * Validates an email address.
 *
 * @param string $email Email address.
 * @return string|false The email, or false when invalid.
 */
function is_email( $email ) {
	return false !== filter_var( $email, FILTER_VALIDATE_EMAIL ) ? $email : false;
}

/**
 * Sanitizes an email address.
 *
 * @param string $email Email address.
 * @return string
 */
function sanitize_email( $email ) {
	return filter_var( $email, FILTER_SANITIZE_EMAIL );
}

/**
 * Parses a URL.
 *
 * @param string $url       URL to parse.
 * @param int    $component URL component, or -1 for all.
 * @return mixed
 */
function wp_parse_url( $url, $component = -1 ) {
	return parse_url( $url, $component );
}

/**
 * Sanitizes a URL.
 *
 * @param string $url URL.
 * @return string
 */
function esc_url_raw( $url ) {
	return filter_var( $url, FILTER_SANITIZE_URL );
}

/**
 * Sanitizes a URL for output.
 *
 * @param string $url URL.
 * @return string
 */
function esc_url( $url ) {
	return esc_url_raw( $url );
}

/**
 * Gets a Tour Operator instance carrying the currency fixture.
 *
 * @return object
 */
function tour_operator() {
	return (object) array( 'options' => array( 'currency' => $GLOBALS['lsx_to_test']['currency'] ) );
}

/**
 * Gets a core price type label.
 *
 * @param string $label_index Price type.
 * @return string
 */
function lsx_to_get_price_type_label( $label_index = '' ) {
	// Mirrors core, which has no label for 'per_person'.
	$labels = array(
		'per_person_per_night'         => 'Per Person Per Night',
		'per_person_sharing'           => 'Per Person Sharing',
		'per_person_sharing_per_night' => 'Per Person Sharing Per Night',
		'total_percentage'             => 'Percentage Off Your Price.',
	);
	return $labels[ $label_index ] ?? '';
}

/**
 * Gets a post fixture's excerpt.
 *
 * @param int|null $post Post ID.
 * @return string
 */
function get_the_excerpt( $post = null ) {
	$post = get_post( $post );
	return $post ? $post->post_excerpt : '';
}

/**
 * Formats the test clock, pinned by the "now" fixture, or the real time when it is unset.
 *
 * @param string   $type Format, or "mysql".
 * @param int|bool $gmt  Ignored; the test clock is UTC.
 * @return string
 */
function current_time( $type, $gmt = 0 ) {
	return gmdate( 'mysql' === $type ? 'Y-m-d H:i:s' : $type, $GLOBALS['lsx_to_test']['now'] ?? time() );
}
