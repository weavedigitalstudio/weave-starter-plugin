<?php
/**
 * Custom post type, taxonomy, and meta field registration.
 *
 * Registers the demo "Starter Item" CPT with two taxonomies (hierarchical
 * and flat) and five meta fields exposed via the REST API for use by the
 * Gutenberg meta-entry block.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\PostTypes;

defined( 'ABSPATH' ) || exit;

/**
 * Register the Starter Item custom post type.
 *
 * Respects the enable_cpt and cpt_public settings from the settings page.
 */
function register_post_types(): void {
	$settings  = \WeaveStarterPlugin\SettingsPage\get_settings();

	// Bail if the CPT is disabled.
	if ( empty( $settings['enable_cpt'] ) ) {
		return;
	}

	$is_public = ! empty( $settings['cpt_public'] );

	register_post_type( 'starter_item', [
		'labels'              => [
			'name'                  => __( 'Starter Items', 'weave-starter-plugin' ),
			'singular_name'         => __( 'Starter Item', 'weave-starter-plugin' ),
			'add_new'               => __( 'Add New', 'weave-starter-plugin' ),
			'add_new_item'          => __( 'Add New Starter Item', 'weave-starter-plugin' ),
			'edit_item'             => __( 'Edit Starter Item', 'weave-starter-plugin' ),
			'new_item'              => __( 'New Starter Item', 'weave-starter-plugin' ),
			'view_item'             => __( 'View Starter Item', 'weave-starter-plugin' ),
			'search_items'          => __( 'Search Starter Items', 'weave-starter-plugin' ),
			'not_found'             => __( 'No starter items found.', 'weave-starter-plugin' ),
			'not_found_in_trash'    => __( 'No starter items found in Trash.', 'weave-starter-plugin' ),
			'all_items'             => __( 'All Starter Items', 'weave-starter-plugin' ),
			'menu_name'             => __( 'Starter Items', 'weave-starter-plugin' ),
			'item_published'        => __( 'Starter item published.', 'weave-starter-plugin' ),
			'item_updated'          => __( 'Starter item updated.', 'weave-starter-plugin' ),
			'item_reverted_to_draft' => __( 'Starter item reverted to draft.', 'weave-starter-plugin' ),
		],
		'public'              => $is_public,
		'publicly_queryable'  => $is_public,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'rest_base'           => 'starter-items',
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-portfolio',
		'supports'            => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
		'has_archive'         => $is_public,
		'rewrite'             => $is_public ? [ 'slug' => 'starter-items' ] : false,
		'capability_type'     => 'post',
		'template'            => [
			[ 'weave-starter/starter-item-meta' ],
		],
		'template_lock'       => 'insert',
	] );
}
add_action( 'init', __NAMESPACE__ . '\register_post_types' );

/**
 * Register taxonomies for the Starter Item CPT.
 */
function register_taxonomies(): void {
	$settings = \WeaveStarterPlugin\SettingsPage\get_settings();

	if ( empty( $settings['enable_cpt'] ) ) {
		return;
	}

	// Hierarchical taxonomy (like categories).
	register_taxonomy( 'starter-item-category', 'starter_item', [
		'labels'       => [
			'name'          => __( 'Categories', 'weave-starter-plugin' ),
			'singular_name' => __( 'Category', 'weave-starter-plugin' ),
			'search_items'  => __( 'Search Categories', 'weave-starter-plugin' ),
			'all_items'     => __( 'All Categories', 'weave-starter-plugin' ),
			'parent_item'   => __( 'Parent Category', 'weave-starter-plugin' ),
			'edit_item'     => __( 'Edit Category', 'weave-starter-plugin' ),
			'add_new_item'  => __( 'Add New Category', 'weave-starter-plugin' ),
		],
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => [ 'slug' => 'starter-category' ],
	] );

	// Flat taxonomy (like tags).
	register_taxonomy( 'starter-item-tag', 'starter_item', [
		'labels'       => [
			'name'          => __( 'Tags', 'weave-starter-plugin' ),
			'singular_name' => __( 'Tag', 'weave-starter-plugin' ),
			'search_items'  => __( 'Search Tags', 'weave-starter-plugin' ),
			'all_items'     => __( 'All Tags', 'weave-starter-plugin' ),
			'edit_item'     => __( 'Edit Tag', 'weave-starter-plugin' ),
			'add_new_item'  => __( 'Add New Tag', 'weave-starter-plugin' ),
		],
		'hierarchical' => false,
		'show_in_rest' => true,
		'rewrite'      => [ 'slug' => 'starter-tag' ],
	] );
}
add_action( 'init', __NAMESPACE__ . '\register_taxonomies' );

/**
 * Register post meta fields for the Starter Item CPT.
 *
 * All fields are exposed in the REST API so the Gutenberg meta-entry
 * block can read and write them via useEntityProp.
 */
function register_meta_fields(): void {
	$meta_fields = [
		'_weave_starter_subtitle'      => [
			'type'              => 'string',
			'description'       => __( 'Subtitle for the item.', 'weave-starter-plugin' ),
			'single'            => true,
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		],
		'_weave_starter_description'   => [
			'type'              => 'string',
			'description'       => __( 'Description of the item.', 'weave-starter-plugin' ),
			'single'            => true,
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		],
		'_weave_starter_price'         => [
			'type'              => 'number',
			'description'       => __( 'Price of the item.', 'weave-starter-plugin' ),
			'single'            => true,
			'default'           => 0,
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_price',
		],
		'_weave_starter_url'           => [
			'type'              => 'string',
			'description'       => __( 'URL for the item.', 'weave-starter-plugin' ),
			'single'            => true,
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		],
		'_weave_starter_display_order' => [
			'type'              => 'integer',
			'description'       => __( 'Display order for sorting.', 'weave-starter-plugin' ),
			'single'            => true,
			'default'           => 0,
			'sanitize_callback' => 'absint',
		],
	];

	foreach ( $meta_fields as $key => $args ) {
		register_post_meta(
			'starter_item',
			$key,
			array_merge( $args, [
				'show_in_rest'  => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			] )
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\register_meta_fields' );

/**
 * Sanitise a price value to a float.
 *
 * @param mixed $value The raw price value.
 * @return float Sanitised price.
 */
function sanitize_price( $value ): float {
	return round( (float) $value, 2 );
}
