<?php
/**
 * Registers our Block Templates
 * 
 * @link https://github.com/lightspeedwp/lsx-starter-plugin/blob/master/classes/class-templates.php
 * @version 1.0.0
 */

class LSX_TO_Specials_Templates {

	/**
	 * Holds array of out templates to be registered.
	 *
	 * @var array
	 */
	public $templates = [];

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type_templates' ] );
	}

	/**
	 * Registers our plugins templates.
	 *
	 * @return void
	 */
	public function register_post_type_templates() {

		/**
		 * The slugs of the built in post types we are using.
		 */
		$post_types = [
			'single-tour'  => [
				'title'       => __( 'Single Special Offer', 'to-specials' ),
				'description' => __( 'Displays a single tour', 'to-specials' ),
				'post_types'  => ['special'],
			],
			'archive-special' => [
				'title'       => __( 'Specials Offers Archive', 'to-specials' ),
				'description' => __( 'Displays all the specials available.', 'to-specials' ),
				'post_types'  => ['special'],
			],
		];

		foreach ( $post_types as $key => $labels ) {
			$args = [
				'title'       => $labels['title'],
				'description' => $labels['description'],
				'content'     => $this->get_template_content( $key . '.html' ),
			];
			if ( isset( $labels['post_types'] ) ) {
				$args['post_types'] = $labels['post_types'];
			}

			if ( function_exists( 'register_block_template' ) ) {
				register_block_template( 'lsx-tour-operator//' . $key, $args );
			}
		}
	}

	/**
	 * Gets the PHP template file and returns the content.
	 *
	 * @param [type] $template
	 * @return void
	 */
	protected function get_template_content( $template ) {
		ob_start();
		include LSX_TO_SPECIALS_PATH . "/templates/{$template}";
		return ob_get_clean();
	}
}

new LSX_TO_Specials_Templates();
