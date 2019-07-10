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
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_filter( 'lsx_get_post-types_configs', array( $this, 'post_type_config' ), 10, 1 );
		add_filter( 'lsx_get_metaboxes_configs', array( $this, 'meta_box_config' ), 10, 1 );
		add_filter( 'lsx_get_taxonomies_configs', array( $this, 'taxonomy_config' ), 10, 1 );

		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_tour_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_team_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_review_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields' ) );

		add_action( 'lsx_to_framework_special_tab_general_settings_bottom', array( $this, 'general_settings' ), 10, 1 );
	}
	/**
	 * Register the specials post type config
	 *
	 * @param  $objects
	 * @return   array
	 */
	public function post_type_config( $objects ) {

		foreach ( $this->post_types as $key => $label ) {
			if ( file_exists( LSX_TO_SPECIALS_PATH . 'includes/post-types/config-' . $key . '.php' ) ) {
				$objects[ $key ] = include LSX_TO_SPECIALS_PATH . 'includes/post-types/config-' . $key . '.php';
			}
		}

		return 	$objects;
	}

	/**
	 * Register the activity metabox config
	 *
	 * @param  $meta_boxes
	 * @return   array
	 */
	public function meta_box_config( $meta_boxes ) {
		foreach ( $this->post_types as $key => $label ) {
			if ( file_exists( LSX_TO_SPECIALS_PATH . 'includes/metaboxes/config-' . $key . '.php' ) ) {
				$meta_boxes[ $key ] = include LSX_TO_SPECIALS_PATH . 'includes/metaboxes/config-' . $key . '.php';
			}
		}
		return 	$meta_boxes;
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
	 * Register the global post types.
	 *
	 *
	 * @return    null
	 */
	public function register_taxonomies() {

		$labels = array(
				'name' => _x( 'Special Type', 'to-specials' ),
				'singular_name' => _x( 'Special Type', 'to-specials' ),
				'search_items' => __( 'Search Special Types' , 'to-specials' ),
				'all_items' => __( 'Special Types' , 'to-specials' ),
				'parent_item' => __( 'Parent Special Type' , 'to-specials' ),
				'parent_item_colon' => __( 'Parent Special Type:' , 'to-specials' ),
				'edit_item' => __( 'Edit Special Type' , 'to-specials' ),
				'update_item' => __( 'Update Special Type' , 'to-specials' ),
				'add_new_item' => __( 'Add New Special Type' , 'to-specials' ),
				'new_item_name' => __( 'New Special Type' , 'to-specials' ),
				'menu_name' => __( 'Special Types' , 'to-specials' ),
		);

		// Now register the taxonomy
		register_taxonomy('special-type',$this->plugin_slug, array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'public' => true,
				'show_tagcloud' => false,
				'exclude_from_search' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'special-type' ),
		));

	}

	/**
	 * Adds in the fields to the Tour Operators Post Types.
	 */
	public function custom_fields( $fields ) {
		global $post, $typenow, $current_screen;

		$post_type = false;
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

		if ( false !== $post_type ) {
			$fields[] = array(
				'id' => 'specials_title',
				'name' => 'Specials',
				'type' => 'title',
				'cols' => 12,
			);
			$fields[] = array(
				'id' => 'special_to_' . $post_type,
				'name' => 'Specials related with this ' . $post_type,
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'special',
					'nopagin' => true,
					'posts_per_page' => '-1',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}
		return $fields;
	}

	/**
	 * Adds the special specific options
	 */
	public function general_settings() {
		?>
		<tr class="form-field-wrap">
			<th scope="row">
				<label for="enable_widget_excerpt"> <?php esc_html_e( 'Disable Widget Excerpt', 'tour-operator' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if enable_widget_excerpt}} checked="checked" {{/if}} name="enable_widget_excerpt" />
				<small><?php esc_html_e( 'This enables the excerpt text on the widget.', 'tour-operator' ); ?></small>
			</td>
		</tr>
		<tr class="form-field-wrap">
			<th scope="row">
				<label for="expiration_status"> <?php esc_html_e( 'Expiration Status', 'tour-operator' ); ?></label>
			</th>
			<td>
				<select value="{{expiration_status}}" name="expiration_status">
					<option value="draft" {{#is expiration_status value=""}}selected="selected"{{/is}} {{#is expiration_status value="draft"}} selected="selected"{{/is}}><?php esc_html_e( 'Draft', 'tour-operator' ); ?></option>
					<option value="delete" {{#is expiration_status value="delete"}} selected="selected"{{/is}}><?php esc_html_e( 'Delete', 'tour-operator' ); ?></option>
					<option value="private" {{#is expiration_status value="private"}} selected="selected"{{/is}}><?php esc_html_e( 'Private', 'tour-operator' ); ?></option>
				</select>
			</td>
		</tr>

		<?php
	}
}
new LSX_TO_Specials_Admin();
