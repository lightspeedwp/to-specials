<?php
/**
 * Specials Widget Content Part
 *
 * @package 	tour-operators
 * @category	special
 * @subpackage	widget
 */

global $disable_placeholder, $disable_text, $post;

$has_single = ! lsx_to_is_single_disabled();
$permalink = '';
$tour_operator = tour_operator();

if ( $has_single ) {
	$permalink = get_the_permalink();
} elseif ( ! is_post_type_archive( 'special' ) ) {
	$has_single = true;
	$permalink = get_post_type_archive_link( 'special' ) . '#special-' . $post->post_name;
}
?>
<article <?php post_class(); ?>>
	<?php if ( empty( $disable_placeholder ) ) { ?>
		<div class="lsx-to-widget-thumb">
			<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
				<?php lsx_thumbnail( 'lsx-thumbnail-single' ); ?>
			<?php if ( $has_single ) { ?></a><?php } ?>
		</div>
	<?php } ?>

	<div class="lsx-to-widget-content">
		<h4 class="lsx-to-widget-title text-center">
			<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
				<?php the_title(); ?>
			<?php if ( $has_single ) { ?></a><?php } ?>
		</h4>

		<?php
			// if ( empty( $disable_text ) ) {
			// 	lsx_to_tagline( '<p class="lsx-to-widget-tagline text-center">', '</p>' );
			// }
		?>

		<div class="lsx-to-widget-meta-data">
			<?php
				$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

				lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'to-specials' ) . ':</span> ', '</span>' );
				lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'to-specials' ) . ':</span> ', '</span>' );
				lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'to-specials' ) . ':</span> ', '</span>' );
				the_terms( get_the_ID(), 'travel-style', '<span class="' . $meta_class . 'style"><span class="lsx-to-meta-data-key">' . __( 'Travel Style', 'to-specials' ) . ':</span> ', ', ', '</span>' );
				the_terms( get_the_ID(), 'special-type', '<span class="' . $meta_class . 'special"><span class="lsx-to-meta-data-key">' . __( 'Type', 'to-specials' ) . ':</span> ', ', ', '</span>' );
				lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Destinations', 'to-specials' ) . ':</span> ', '</span>' );
				lsx_to_specials_validity( '<span class="' . $meta_class . 'valid-from"><span class="lsx-to-meta-data-key">' . __( 'Booking Validity', 'to-specials' ) . ':</span> ', '</span>' );

				if ( function_exists( 'lsx_to_connected_activities' ) ) {
					lsx_to_connected_activities( '<span class="' . $meta_class . 'activities"><span class="lsx-to-meta-data-key">' . __( 'Activites', 'to-specials' ) . ':</span> ', '</span>' );
				}
			?>
		</div>

		<?php
		ob_start();
		lsx_to_widget_entry_content_top();
		the_excerpt();
		lsx_to_widget_entry_content_bottom();
		$excerpt = ob_get_clean();

		if ( empty( $disable_text ) && ! empty( $excerpt ) ) {
			echo wp_kses_post( $excerpt );
		} elseif ( $has_single && true !== $disable_view_more && '1' !== $disable_view_more ) {
			?>
			<p class="moretag-wrapper"><a href="<?php echo esc_url( $permalink ); ?>" class="moretag"><?php esc_html_e( 'View more', 'tour-operator' ); ?></a></p>
			<?php
		}
		?>
	</div>
</article>
