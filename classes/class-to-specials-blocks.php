<?php
/**
 * LSX_TO_Specials_Blocks
 *
 * @package   LSX_TO_Specials
 * @author    LightSpeed
 * @license   GPL-3.0+
 */
class LSX_TO_Specials_Blocks {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block_json_files' ) );

		// Register our block patterns.
		add_action( 'init', array( $this, 'register_block_patterns' ), 11 );

		// BLock Helpers
		add_filter( 'lsx_to_multi_field_wrappers', array( $this, 'register_multi_field_wrappers' ) );
	}

	/**
	 * Register the block JSON files from build/blocks/.
	 *
	 * @return void
	 */
	public function register_block_json_files() {
		$directory = LSX_TO_SPECIALS_PATH . 'build/blocks/';

		if ( ! is_dir( $directory ) ) {
			return;
		}

		foreach ( glob( $directory . '*', GLOB_ONLYDIR ) as $block_dir ) {
			register_block_type( $block_dir );
		}
	}

	/**
	 * Registers block patterns from the patterns directory.
	 *
	 * Loads patterns from the root /patterns/ directory.
	 *
	 * @since 1.0.0
	 * @since 2.1.0 Updated to load from root /patterns/ directory.
	 *
	 * @return void
	 */
	public function register_block_patterns() {
		$directory = LSX_TO_SPECIALS_PATH . 'patterns/';

		if ( ! is_dir( $directory ) ) {
			return;
		}

		foreach ( glob( $directory . '*.php' ) as $file ) {
			// Extract the filename without the directory path and extension.
			$filename = basename( $file, '.php' );

			// Use the filename to create the key.
			$key = 'lsx-tour-operator/' . $filename;

			// Check if pattern is already registered.
			if ( \WP_Block_Patterns_Registry::get_instance()->is_registered( $key ) ) {
				continue;
			}

			// Require the file and register the pattern.
			register_block_pattern( $key, require $file );
		}
	}

	/**
	 * Register review block wrappers that group multiple meta fields.
	 * The social-links wrapper should be hidden only when every social field is empty.
	 *
	 * @param array $wrappers
	 * @return array
	 */
	public function register_multi_field_wrappers( $wrappers ) {
		$wrappers['booking-validity'] = array(
			'booking_validity_start',
			'booking_validity_end',
		);
		return $wrappers;
	}
}
