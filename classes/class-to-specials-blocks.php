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
}
