<?php
/**
 * Tour Operator - Special Type taxonomy config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$taxonomy = array(
	'object_types'  => 'special',
	'menu_position' => 73,
	'args'          => array(
		'hierarchical'        => true,
		'labels'              => array(
			'name'              => esc_html__( 'Special Type', 'to-special' ),
			'singular_name'     => esc_html__( 'Special Type', 'to-special' ),
			'search_items'      => esc_html__( 'Search Types', 'to-special' ),
			'all_items'         => esc_html__( 'Special Types', 'to-special' ),
			'parent_item'       => esc_html__( 'Parent', 'to-special' ),
			'parent_item_colon' => esc_html__( 'Parent:', 'to-special' ),
			'edit_item'         => esc_html__( 'Edit Special Type', 'to-special' ),
			'update_item'       => esc_html__( 'Update Special Type', 'to-special' ),
			'add_new_item'      => esc_html__( 'Add New Special Type', 'to-special' ),
			'new_item_name'     => esc_html__( 'New Role', 'to-special' ),
			'menu_name'         => esc_html__( 'Roles', 'to-special' ),
		),
		'show_ui'             => true,
		'public'              => true,
		'show_tagcloud'       => false,
		'exclude_from_search' => true,
		'show_admin_column'   => true,
		'query_var'           => true,
		'rewrite'             => array( 'special-type' ),
	),
);

return $taxonomy;
