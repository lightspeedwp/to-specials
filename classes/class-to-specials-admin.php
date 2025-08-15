<?php
/**
 * LSX_TO_Specials_Admin
 *
 * @package   LSX_TO_Specials_Admin
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2018 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Specials_Admin
 * @author  LightSpeed
 */

class LSX_TO_Specials_Admin extends LSX_TO_Specials {

	/**
	 * The post type slug
	 *
	 * @var string
	 */
	public $post_type = 'special';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'register_post_type' ), 100 );
		add_filter( 'lsx_get_taxonomies_configs', array( $this, 'taxonomy_config' ), 10, 1 );
		add_action( 'cmb2_admin_init', array( $this, 'register_cmb2_fields' ) );

		add_filter( 'lsx_to_post_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_tour_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_team_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_review_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields' ) );

		add_action( 'lsx_to_framework_special_tab_general_settings_bottom', array( $this, 'general_settings' ), 10, 1 );
	}

	/**
	 * Registers the custom post type for the content model.
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type(
			'special',
			require_once LSX_TO_SPECIALS_PATH . '/includes/post-types/config-' . $this->post_type . '.php'
		);
	}

	/**
	 * Register the Role taxonomy
	 *
	 *
	 * @return    null
	 */
	public function taxonomy_config( $taxonomies ) {
		if ( file_exists( LSX_TO_SPECIALS_PATH . 'includes/taxonomies/config-special-type.php' ) ) {
			$taxonomies['special-type'] = include LSX_TO_SPECIALS_PATH . 'includes/taxonomies/config-special-type.php';
		}

		return 	$taxonomies;
	}

	/**
	 * Adds in the fields to the Tour Operators Post Types.
	 */
	public function custom_fields( $fields ) {
		global $post, $typenow, $current_screen;

		$post_type = false;
		// @phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( $post && $post->post_type ) {
			$post_type = $post->post_type;
		} elseif ( $typenow ) {
			$post_type = $typenow;
		} elseif ( $current_screen && $current_screen->post_type ) {
			$post_type = $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		} elseif ( isset( $_REQUEST['post'] ) ) {
			$post_type = get_post_type( sanitize_key( $_REQUEST['post'] ) );
		}
		// @phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( false !== $post_type ) {
			$fields[] = array(
				'id' => 'special_to_' . $post_type,
				'name' => 'Specials related with this ' . $post_type,
				'type' => 'pw_multiselect',
				'use_ajax' => false,
				'repeatable' => false,
				'allow_none' => true,
				'options'  => array(
					'post_type_args' => 'special',
				),
			);
		}
		return $fields;
	}

	/**
	 * Registers the CMB2 custom fields
	 *
	 * @return void
	 */
	public function register_cmb2_fields() {
		/**
		 * Initiate the metabox
		 */
		$cmb = [];
		$fields = include( LSX_TO_SPECIALS_PATH . 'includes/metaboxes/config-' . $this->post_type . '.php' );

		$metabox_counter = 1;
		$cmb[ $metabox_counter ] = new_cmb2_box( array(
			'id'            => 'lsx_to_metabox_' . $this->post_type . '_' . $metabox_counter,
			'title'         => $fields['title'],
			'object_types'  => array( $this->post_type ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
		) );

		foreach ( $fields['fields'] as $field ) {

			if ( 'title' === $field['type'] ) {
				$metabox_counter++;
				$cmb[ $metabox_counter ] = new_cmb2_box( array(
					'id'            => 'lsx_to_metabox_' . $this->post_type . '_' . $metabox_counter,
					'title'         => $field['name'],
					'object_types'  => array( $this->post_type ), // Post type
					'context'       => 'normal',
					'priority'      => 'high',
					'show_names'    => true,
				) );
				continue;
			}

			/**
			 * Fixes for the extensions
			 */
			if ( 'post_select' === $field['type'] || 'post_ajax_search' === $field['type'] ) {
				$field['type'] = 'pw_multiselect';
			}

			$cmb[ $metabox_counter ]->add_field( $field );
		}
	}

	/**
	 * Adds the special specific options
	 */
	public function general_settings() {
		?>
		<tr class="form-field-wrap">
			<th scope="row">
				<label for="enable_widget_excerpt"> <?php esc_html_e( 'Disable Widget Excerpt', 'to-specials' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if enable_widget_excerpt}} checked="checked" {{/if}} name="enable_widget_excerpt" />
				<small><?php esc_html_e( 'This enables the excerpt text on the widget.', 'to-specials' ); ?></small>
			</td>
		</tr>
		<tr class="form-field-wrap">
			<th scope="row">
				<label for="expiration_status"> <?php esc_html_e( 'Expiration Status', 'to-specials' ); ?></label>
			</th>
			<td>
				<select value="{{expiration_status}}" name="expiration_status">
					<option value="draft" {{#is expiration_status value=""}}selected="selected"{{/is}} {{#is expiration_status value="draft"}} selected="selected"{{/is}}><?php esc_html_e( 'Draft', 'to-specials' ); ?></option>
					<option value="delete" {{#is expiration_status value="delete"}} selected="selected"{{/is}}><?php esc_html_e( 'Delete', 'to-specials' ); ?></option>
					<option value="private" {{#is expiration_status value="private"}} selected="selected"{{/is}}><?php esc_html_e( 'Private', 'to-specials' ); ?></option>
				</select>
			</td>
		</tr>

		<?php
	}
}
new LSX_TO_Specials_Admin();
