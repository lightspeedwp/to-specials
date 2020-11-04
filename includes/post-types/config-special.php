<?php
/**
 * Tour Operator - Special Post Type config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$post_type = array(
	'class'               => 'LSX_TO_Specials',
	'menu_icon'           => 'dashicons-star-filled',
	'labels'              => array(
		'name'               => esc_html__( 'Specials', 'to-specials' ),
		'singular_name'      => esc_html__( 'Activity', 'to-specials' ),
		'add_new'            => esc_html__( 'Add New', 'to-specials' ),
		'add_new_item'       => esc_html__( 'Add New Special', 'to-specials' ),
		'edit_item'          => esc_html__( 'Edit Special', 'to-specials' ),
		'new_item'           => esc_html__( 'New Specials', 'to-specials' ),
		'all_items'          => esc_html__( 'Specials', 'to-specials' ),
		'view_item'          => esc_html__( 'View Special', 'to-specials' ),
		'search_items'       => esc_html__( 'Search Specials', 'to-specials' ),
		'not_found'          => esc_html__( 'No specials found', 'to-specials' ),
		'not_found_in_trash' => esc_html__( 'No specials found in Trash', 'to-specials' ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__( 'Specials', 'to-specials' ),
	),
	'public'              => true,
	'publicly_queryable'  => true,
	'show_ui'             => true,
	'show_in_menu'        => 'tour-operator',
	'menu_position'       => 70,
	'query_var'           => true,
	'rewrite'             => array(
		'slug'       => 'special',
		'with_front' => false,
	),
	'exclude_from_search' => false,
	'capability_type'     => 'post',
	'has_archive'         => 'specials',
	'hierarchical'        => false,
	'show_in_rest'        => true,
	'supports'            => array(
		'title',
		'slug',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	),
);

return $post_type;
