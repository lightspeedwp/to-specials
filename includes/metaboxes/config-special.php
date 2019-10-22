<?php
/**
 * Tour Operator - Special Metabox config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$metabox = array(
	'title'  => esc_html__( 'Tour Operator Plugin', 'to-specials' ),
	'pages'  => 'special',
	'fields' => array(),
);

$metabox['fields'][] = array(
	'id'   => 'featured',
	'name' => esc_html__( 'Featured', 'to-specials' ),
	'type' => 'checkbox',
);

$metabox['fields'][] = array(
	'id'   => 'disable_single',
	'name' => esc_html__( 'Disable Single', 'to-specials' ),
	'type' => 'checkbox',
);

if ( ! class_exists( 'LSX_Banners' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'tagline',
		'name' => esc_html__( 'Tagline', 'to-specials' ),
		'type' => 'text',
	);
}

$metabox['fields'][] = array(
	'id'      => 'terms_conditions',
	'name'    => esc_html__( 'Terms & Conditions','to-specials' ),
	'type'    => 'wysiwyg',
	'options' => array(
		'editor_height' => '100',
	),
);

if ( class_exists( 'LSX_TO_Team' ) ) {
	$metabox['fields'][] = array(
		'id'         => 'team_to_special',
		'name'       => esc_html__( 'Team Member', 'to-specials' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'team',
			'nopagin'        => true,
			'posts_per_page' => 1000,
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'][] = array(
	'id'   => 'booking_title',
	'name' => esc_html__( 'Booking','to-specials' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
	'id'   => 'price',
	'name' => esc_html__( 'Price','to-specials' ),
	'type' => 'text',
);

$metabox['fields'][] = array(
	'id'   => 'price_type',
	'name' => esc_html__( 'Price Type','to-specials' ),
	'type' => 'select',
	'options' => array(
		'none'                         => esc_html__( 'Select a type','to-specials' ),
		'per_person'                   => esc_html__( 'Per Person','to-specials' ),
		'per_person_per_night'         => esc_html__( 'Per Person Per Night','to-specials' ),
		'per_person_sharing'           => esc_html__( 'Per Person Sharing','to-specials' ),
		'per_person_sharing_per_night' => esc_html__( 'Per Person Sharing Per Night','to-specials' ),
		'total_percentage'             => esc_html__( 'Percentage Off Your Price.','to-specials' ),
	),
);

$metabox['fields'][] = array(
	'id'   => 'duration',
	'name' => esc_html__( 'Duration','to-specials' ),
	'type' => 'text',
);

$metabox['fields'][] = array(
	'id'   => 'booking_validity_start',
	'name' => esc_html__( 'Booking Validity (start)','to-specials' ),
	'type' => 'date',
);

$metabox['fields'][] = array(
	'id'   => 'booking_validity_end',
	'name' => esc_html__( 'Booking Validity (end)','to-specials' ),
	'type' => 'date',
);

$metabox['fields'][] = array(
	'id'   => 'expire_post',
	'name' => esc_html__( 'Expire this special automatically', 'to-specials' ),
	'type' => 'checkbox',
);

$metabox['fields'][] = array(
	'id'   => 'travel_dates',
	'name' => '',
	'single_name' => esc_html__( 'Travel Dates','to-specials' ),
	'type' => 'group',
	'repeatable'  => true,
	'sortable'    => true,
	'fields'      => array(
		array(
			'id'   => 'travel_dates_start',
			'name' => esc_html__( 'Start','to-specials' ),
			'type' => 'date',
		),
		array(
			'id'   => 'travel_dates_end',
			'name' => esc_html__( 'End','to-specials' ),
			'type' => 'date',
		),
	),
);

if ( class_exists( 'LSX_TO_Maps' ) ) {
	$tour_operator = tour_operator();
	$api_key = false;

	if ( isset( $tour_operator->options['api']['googlemaps_key'] ) ) {
		$api_key = $tour_operator->options['api']['googlemaps_key'];
	}

	$metabox['fields'][] = array(
		'id'   => 'location_title',
		'name' => esc_html__( 'Location', 'to-specials' ),
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'             => 'location',
		'name'           => esc_html__( 'Location', 'to-specials' ),
		'type'           => 'gmap',
		'google_api_key' => $api_key,
	);
}

$metabox['fields'][] = array(
	'id'   => 'gallery_title',
	'name' => esc_html__( 'Gallery', 'to-specials' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
	'id'                  => 'gallery',
	'name'                => '',
	'type'                => 'image',
	'repeatable'          => true,
	'show_size'           => false,
	'sortable'            => true,
	'string-repeat-field' => esc_html__( 'Add new image', 'tour-operator' ),
);

if ( class_exists( 'Envira_Gallery' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'envira_title',
		'name' => esc_html__( 'Envira Gallery', 'to-specials' ),
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => 'envira_gallery',
		'name'       => esc_html__( 'Envira Gallery', 'to-specials' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'envira',
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);

	if ( class_exists( 'Envira_Videos' ) ) {
		$metabox['fields'][] = array(
			'id'         => 'envira_video',
			'name'       => esc_html__( 'Envira Video Gallery', 'to-specials' ),
			'type'       => 'post_select',
			'use_ajax'   => false,
			'allow_none' => true,
			'query'      => array(
				'post_type'      => 'envira',
				'nopagin'        => true,
				'posts_per_page' => '-1',
				'orderby'        => 'title',
				'order'          => 'ASC',
			),
		);
	}
}

$post_types = array(
	'post'          => esc_html__( 'Posts', 'to-specials' ),
	'accommodation' => esc_html__( 'Accommodation', 'to-specials' ),
	'destination'   => esc_html__( 'Destinations', 'to-specials' ),
	'tour'          => esc_html__( 'Tours', 'to-specials' ),
);

foreach ( $post_types as $slug => $label ) {
	$metabox['fields'][] = array(
		'id'   => $slug . '_title',
		'name' => $label,
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => $slug . '_to_special',
		'name'       => $label . esc_html__( ' related with this special', 'to-specials' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'repeatable' => true,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => $slug,
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'] = apply_filters( 'lsx_to_special_custom_fields', $metabox['fields'] );

return $metabox;
