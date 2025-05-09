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
			'name'              => esc_html__( 'Special Type', 'to-specials' ),
			'singular_name'     => esc_html__( 'Special Type', 'to-specials' ),
			'search_items'      => esc_html__( 'Search Types', 'to-specials' ),
			'all_items'         => esc_html__( 'Special Types', 'to-specials' ),
			'parent_item'       => esc_html__( 'Parent', 'to-specials' ),
			'parent_item_colon' => esc_html__( 'Parent:', 'to-specials' ),
			'edit_item'         => esc_html__( 'Edit Special Type', 'to-specials' ),
			'update_item'       => esc_html__( 'Update Special Type', 'to-specials' ),
			'add_new_item'      => esc_html__( 'Add New Special Type', 'to-specials' ),
			'new_item_name'     => esc_html__( 'New Role', 'to-specials' ),
			'menu_name'         => esc_html__( 'Roles', 'to-specials' ),
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
