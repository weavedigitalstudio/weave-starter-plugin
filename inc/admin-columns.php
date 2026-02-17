<?php
/**
 * OPTIONAL MODULE: Admin Columns.
 * Remove this file if not needed for your plugin.
 *
 * Adds custom columns (Price, Category, Display Order) to the Starter Items
 * admin list table and makes numeric columns sortable.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\AdminColumns;

defined( 'ABSPATH' ) || exit;

/**
 * Register custom columns for the Starter Item list table.
 *
 * Inserts Price, Category, and Order columns after Title,
 * and removes the default Date column.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function add_custom_columns( array $columns ): array {
	$new_columns = [];

	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;

		// Insert custom columns right after the title.
		if ( 'title' === $key ) {
			$new_columns['weave_starter_price']    = __( 'Price', 'weave-starter-plugin' );
			$new_columns['weave_starter_category'] = __( 'Category', 'weave-starter-plugin' );
			$new_columns['weave_starter_order']    = __( 'Order', 'weave-starter-plugin' );
		}
	}

	// Remove the default date column — Order is more useful for CPTs.
	unset( $new_columns['date'] );

	return $new_columns;
}
add_filter( 'manage_starter_item_posts_columns', __NAMESPACE__ . '\add_custom_columns' );

/**
 * Render custom column content.
 *
 * @param string $column  Column identifier.
 * @param int    $post_id Current post ID.
 */
function render_custom_columns( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'weave_starter_price':
			$price = (float) get_post_meta( $post_id, '_weave_starter_price', true );
			echo $price > 0
				? '$' . esc_html( number_format( $price, 2 ) )
				: '<span aria-label="' . esc_attr__( 'No price set', 'weave-starter-plugin' ) . '">—</span>';
			break;

		case 'weave_starter_category':
			$terms = get_the_term_list( $post_id, 'starter-item-category', '', ', ' );
			echo $terms && ! is_wp_error( $terms )
				? wp_kses_post( $terms )
				: '<span aria-label="' . esc_attr__( 'No category', 'weave-starter-plugin' ) . '">—</span>';
			break;

		case 'weave_starter_order':
			$order = get_post_meta( $post_id, '_weave_starter_display_order', true );
			echo esc_html( $order ?: '0' );
			break;
	}
}
add_action( 'manage_starter_item_posts_custom_column', __NAMESPACE__ . '\render_custom_columns', 10, 2 );

/**
 * Mark numeric columns as sortable.
 *
 * @param array $columns Sortable columns.
 * @return array Modified sortable columns.
 */
function sortable_columns( array $columns ): array {
	$columns['weave_starter_price'] = 'weave_starter_price';
	$columns['weave_starter_order'] = 'weave_starter_order';
	return $columns;
}
add_filter( 'manage_edit-starter_item_sortable_columns', __NAMESPACE__ . '\sortable_columns' );

/**
 * Handle sorting by custom meta fields.
 *
 * @param \WP_Query $query The main query.
 */
function handle_column_sorting( \WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'weave_starter_price' === $orderby ) {
		$query->set( 'meta_key', '_weave_starter_price' );
		$query->set( 'orderby', 'meta_value_num' );
	} elseif ( 'weave_starter_order' === $orderby ) {
		$query->set( 'meta_key', '_weave_starter_display_order' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\handle_column_sorting' );
