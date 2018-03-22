<?php
/**
 * LSX_TO_Specials_Frontend
 *
 * @package   LSX_TO_Specials_Frontend
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2018 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_Specials_Frontend
 * @author  LightSpeed
 */

class LSX_TO_Specials_Frontend extends LSX_TO_Specials {

	/**
	 * Holds the $page_links array while its being built on the single special page.
	 *
	 * @var array
	 */
	public $page_links = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_filter( 'lsx_to_entry_class', array( $this, 'entry_class' ) );
		add_action( 'lsx_to_settings_current_tab', array( $this, 'set_settings_current_tab' ) );
		add_action( 'init', array( $this, 'init' ) );

		if ( ! class_exists( 'LSX_TO_Template_Redirects' ) ) {
			require_once( LSX_TO_SPECIALS_PATH . 'classes/class-template-redirects.php' );
		}

		$this->redirects = new LSX_TO_Template_Redirects( LSX_TO_SPECIALS_PATH, array_keys( $this->post_types ), array_keys( $this->taxonomies ) );

		add_action( 'lsx_special_content', array( $this->redirects, 'content_part' ), 10 , 2 );

		add_filter( 'lsx_to_page_navigation', array( $this, 'page_links' ) );

		add_action( 'lsx_entry_top',      array( $this, 'archive_entry_top' ), 15 );
		add_action( 'lsx_entry_bottom',   array( $this, 'archive_entry_bottom' ) );
		add_action( 'lsx_content_bottom', array( $this, 'single_content_bottom' ) );
		add_action( 'lsx_to_fast_facts', array( $this, 'single_fast_facts' ) );
	}

	/**
	 * Runs on init after all files have been parsed.
	 */
	public function init() {
		if ( ! class_exists( 'LSX_Currencies' ) ) {
			add_filter( 'lsx_to_custom_field_query', array( $this, 'price_filter' ), 5, 10 );
		}

		add_filter( 'lsx_to_custom_field_query', array( $this, 'terms_conditions_filter' ), 5, 10 );
	}

	/**
	 * A filter to set the content area to a small column on single
	 */
	public function entry_class( $classes ) {
		global $lsx_to_archive;

		if ( 1 !== $lsx_to_archive ) {
			$lsx_to_archive = false;
		}

		if ( is_main_query() && is_singular( 'special' ) && false === $lsx_to_archive ) {
			$classes[] = 'col-xs-12 col-sm-12 col-md-6';
		}

		return $classes;
	}

	/**
	 * Sets the current tab selected.
	 */
	public function set_settings_current_tab( $settings_tab ) {
		if ( is_tax( array_keys( $this->taxonomies ) ) ) {
			$taxonomy = get_query_var( 'taxonomy' );

			if ( 'special-type' === $taxonomy ) {
				$settings_tab = 'special';
			}
		}

		return $settings_tab;
	}

	/**
	 * Adds in additional info for the price custom field
	 */
	public function price_filter( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'special' && 'price' === $meta_key ) {
			$price_type = get_post_meta( get_the_ID(), 'price_type', true );
			$value = preg_replace( '/[^0-9,.]/', '', $value );
			$value = ltrim( $value, '.' );
			$value = str_replace( ', ', '', $value );
			$value = number_format( (int) $value, 2 );
			$tour_operator = tour_operator();
			$currency = '';

			if ( is_object( $tour_operator ) && isset( $tour_operator->options['general'] ) && is_array( $tour_operator->options['general'] ) ) {
				if ( isset( $tour_operator->options['general']['currency'] ) && ! empty( $tour_operator->options['general']['currency'] ) ) {
					$currency = $tour_operator->options['general']['currency'];
					$currency = '<span class="currency-icon ' . mb_strtolower( $currency ) . '">' . $currency . '</span>';
				}
			}

			switch ( $price_type ) {
				case 'per_person':
				case 'per_person_per_night':
				case 'per_person_sharing':
				case 'per_person_sharing_per_night':
					$value = $currency . $value . ' ' . ucwords( str_replace( '_', ' ', $price_type ) ) . '';
					$value = str_replace( 'Per Person', 'P/P', $value );
				break;

				case 'total_percentage':
					$value .= '% ' . __( 'Off', 'to-specials' ) . '';
					$before = str_replace( 'from price', '', $before );
				break;

				case 'none':
				default:
					$value = $currency . $value;
				break;
			}

			$html = $before . $value . $after;
		}

		return $html;
	}

	/**
	 * Filters text area type filters
	 */
	public function terms_conditions_filter( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'special' && 'terms_conditions' === $meta_key ) {
			$html = $before . '<div class="entry-content">' . apply_filters( 'the_content', wpautop( $value ) ) . '</div>' . $after;

		}

		return $html;
	}

	/**
	 * Adds our navigation links to the special single post
	 *
	 * @param $page_links array
	 * @return $page_links array
	 */
	public function page_links( $page_links ) {
		if ( is_singular( 'special' ) ) {
			$this->page_links = $page_links;

			$this->get_map_link();
			$this->get_gallery_link();
			$this->get_videos_link();
			$this->get_terms_and_conditions_link();

			$this->get_related_posts_link();

			$page_links = $this->page_links;
		}

		return $page_links;
	}

	/**
	 * Tests for the Google Map and returns a link for the section
	 */
	public function get_map_link() {
		if ( function_exists( 'lsx_to_has_map' ) && lsx_to_has_map() ) {
			$this->page_links['special-map'] = esc_html__( 'Map', 'to-specials' );
		}
	}

	/**
	 * Tests for the Gallery and returns a link for the section
	 */
	public function get_gallery_link() {
		$gallery_ids = get_post_meta( get_the_ID(), 'gallery', false );
		$envira_gallery = get_post_meta( get_the_ID(), 'envira_gallery', true );

		if ( ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) || ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) ) {
			if ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) {
				// Envira Gallery
				$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-specials' );
				return;
			} else {
				if ( function_exists( 'envira_dynamic' ) ) {
					// Envira Gallery - Dynamic
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-specials' );
					return;
				} else {
					// WordPress Gallery
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-specials' );
					return;
				}
			}
		}
	}

	/**
	 * Tests for the Videos and returns a link for the section
	 */
	public function get_videos_link() {
		$videos_id = false;

		if ( class_exists( 'Envira_Videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'envira_video', true );
		}

		if ( empty( $videos_id ) && function_exists( 'lsx_to_videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'videos', true );
		}

		if ( ! empty( $videos_id ) ) {
			$this->page_links['videos'] = esc_html__( 'Videos', 'to-specials' );
		}
	}

	/**
	 * Tests for the Related Posts and returns a link for the section
	 */
	public function get_related_posts_link() {
		$connected_posts = get_post_meta( get_the_ID(), 'post_to_special', false );

		if ( is_array( $connected_posts ) && ! empty( $connected_posts ) ) {
			$connected_posts = new \WP_Query( array(
				'post_type' => 'post',
				'post__in' => $connected_posts,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_posts = $connected_posts->posts;

			if ( is_array( $connected_posts ) && ! empty( $connected_posts ) ) {
				$this->page_links['posts'] = esc_html__( 'Posts', 'to-specials' );
			}
		}
	}

	/**
	 * Tests for the Term and Conditions and returns a link for the section
	 */
	public function get_terms_and_conditions_link() {
		$terms_conditions = get_post_meta( get_the_ID(), 'terms_conditions', true );

		if ( ! empty( $terms_conditions ) ) {
			$this->page_links['terms-and-conditions'] = esc_html__( 'Terms and Conditions', 'to-specials' );
		}
	}

	/**
	 * Adds the template tags to the top of the archive special
	 */
	public function archive_entry_top() {
		global $lsx_to_archive;

		if ( 'special' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) {
			if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
				<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-grid-mode">
					<?php
						$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

						lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'to-specials' ) . ':</span> ', '</span>' );
						lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'to-specials' ) . ':</span> ', '</span>' );
						lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'to-specials' ) . ':</span> ', '</span>' );
						the_terms( get_the_ID(), 'travel-style', '<span class="' . $meta_class . 'style"><span class="lsx-to-meta-data-key">' . __( 'Travel Style', 'to-specials' ) . ':</span> ', ', ', '</span>' );
						the_terms( get_the_ID(), 'special-type', '<span class="' . $meta_class . 'type"><span class="lsx-to-meta-data-key">' . __( 'Type', 'to-specials' ) . ':</span> ', ', ', '</span>' );
						lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Destinations', 'to-specials' ) . ':</span> ', '</span>' );
						lsx_to_specials_validity( '<span class="' . $meta_class . 'valid-from"><span class="lsx-to-meta-data-key">' . __( 'Booking Validity', 'to-specials' ) . ':</span> ', '</span>' );
						lsx_to_travel_dates( '<span class="' . $meta_class . 'travel-dates"><span class="lsx-to-meta-data-key">' . __( 'Travel Dates', 'to-specials' ) . ':</span> ', '</span>' );

						if ( function_exists( 'lsx_to_connected_activities' ) ) {
							lsx_to_connected_activities( '<span class="' . $meta_class . 'activities"><span class="lsx-to-meta-data-key">' . __( 'Activites', 'to-specials' ) . ':</span> ', '</span>' );
						}
					?>
				</div>
			<?php }
		}
	}

	/**
	 * Adds the template tags to the bottom of the archive special
	 */
	public function archive_entry_bottom() {
		global $lsx_to_archive;

		if ( 'special' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) { ?>
				</div>

				<?php if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
					<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-list-mode">
						<?php
							$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

							lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'to-specials' ) . ':</span> ', '</span>' );
							the_terms( get_the_ID(), 'travel-style', '<span class="' . $meta_class . 'style"><span class="lsx-to-meta-data-key">' . __( 'Travel Style', 'to-specials' ) . ':</span> ', ', ', '</span>' );
							the_terms( get_the_ID(), 'special-type', '<span class="' . $meta_class . 'type"><span class="lsx-to-meta-data-key">' . __( 'Type', 'to-specials' ) . ':</span> ', ', ', '</span>' );
							lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Destinations', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_specials_validity( '<span class="' . $meta_class . 'valid-from"><span class="lsx-to-meta-data-key">' . __( 'Booking Validity', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_travel_dates( '<span class="' . $meta_class . 'travel-dates"><span class="lsx-to-meta-data-key">' . __( 'Travel Dates', 'to-specials' ) . ':</span> ', '</span>' );

							if ( function_exists( 'lsx_to_connected_activities' ) ) {
								lsx_to_connected_activities( '<span class="' . $meta_class . 'activities"><span class="lsx-to-meta-data-key">' . __( 'Activites', 'to-specials' ) . ':</span> ', '</span>' );
							}
						?>
					</div>
				<?php } ?>
			</div>

			<?php $has_single = ! lsx_to_is_single_disabled(); ?>

			<?php if ( $has_single && 'grid' === tour_operator()->archive_layout ) : ?>
				<a href="<?php the_permalink(); ?>" class="moretag"><?php esc_html_e( 'View more', 'to-specials' ); ?></a>
			<?php endif; ?>
		<?php }
	}

	/**
	 * Adds the template tags fast facts
	 */
	public function single_fast_facts() {
		if ( is_singular( 'special' ) ) { ?>
			<section id="fast-facts">
				<div class="lsx-to-section-inner">
					<h3 class="lsx-to-section-title"><?php esc_html_e( 'Special Summary', 'to-specials' ); ?></h3>

					<div class="lsx-to-single-meta-data">
						<?php
							$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

							// lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_specials_validity( '<span class="' . $meta_class . 'valid-from"><span class="lsx-to-meta-data-key">' . esc_html__( 'Booking Validity', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_travel_dates( '<span class="' . $meta_class . 'travel-dates"><span class="lsx-to-meta-data-key">' . esc_html__( 'Travel Dates', 'to-specials' ) . ':</span> ', '</span>' );
							the_terms( get_the_ID(), 'travel-style', '<span class="' . $meta_class . 'style"><span class="lsx-to-meta-data-key">' . esc_html__( 'Travel Style', 'to-specials' ) . ':</span> ', ', ', '</span>' );
							the_terms( get_the_ID(), 'special-type', '<span class="' . $meta_class . 'type"><span class="lsx-to-meta-data-key">' . esc_html__( 'Type', 'to-specials' ) . ':</span> ', ', ', '</span>' );
							lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . esc_html__( 'Tours', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . esc_html__( 'Accommodation', 'to-specials' ) . ':</span> ', '</span>' );
							lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . esc_html__( 'Destinations', 'to-specials' ) . ':</span> ', '</span>' );

							if ( function_exists( 'lsx_to_connected_activities' ) ) {
								lsx_to_connected_activities( '<span class="' . $meta_class . 'activities"><span class="lsx-to-meta-data-key">' . esc_html__( 'Activites', 'to-specials' ) . ':</span> ', '</span>' );
							}
						?>
					</div>
				</div>
			</section>
		<?php }
	}

	/**
	 * Adds the template tags to the bottom of the single special
	 */
	public function single_content_bottom() {
		if ( is_singular( 'special' ) ) {
			if ( function_exists( 'lsx_to_has_map' ) && lsx_to_has_map() ) : ?>
				<section id="special-map" class="lsx-to-section lsx-to-collapse-section">
					<h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title hidden-lg" data-toggle="collapse" data-target="#collapse-special-map"><?php esc_html_e( 'Map', 'to-specials' ); ?></h2>

					<div id="collapse-special-map" class="collapse in">
						<div class="collapse-inner">
							<?php lsx_to_map(); ?>
						</div>
					</div>
				</section>
				<?php
			endif;

			lsx_to_gallery( '<section id="gallery" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-gallery">' . esc_html__( 'Gallery', 'to-specials' ) . '</h2><div id="collapse-gallery" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );

			if ( function_exists( 'lsx_to_videos' ) ) {
				lsx_to_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'to-specials' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			} elseif ( class_exists( 'Envira_Videos' ) ) {
				lsx_to_envira_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'to-specials' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			}

			$terms_conditions = get_post_meta( get_the_ID(), 'terms_conditions', true );

			if ( false !== $terms_conditions && '' !== $terms_conditions ) { ?>
				<section id="terms-and-conditions" class="lsx-to-section lsx-to-collapse-section">
					<h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-terms-and-conditions"><?php esc_html_e( 'Terms and Conditions', 'to-specials' ); ?></h2>

					<div id="collapse-terms-and-conditions" class="collapse in">
						<div class="collapse-inner">
							<div class="row">
								<div class="col-xs-12">
									<?php lsx_to_specials_terms_conditions(); ?>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?php }

			lsx_to_special_posts();
		}
	}

}

new LSX_TO_Specials_Frontend();
