<?php
/**
 * OPTIONAL MODULE: Shortcodes.
 * Remove this file if not needed for your plugin.
 *
 * Provides two shortcodes for use in Beaver Builder layouts and the classic
 * editor where blocks are not practical. New builds should prefer blocks.
 *
 * [weave_starter_list]  — Grid/list of CPT items.
 * [weave_starter_field] — Single meta field value from a CPT item.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * Register shortcodes.
 */
function register_shortcodes(): void {
	add_shortcode( 'weave_starter_list', __NAMESPACE__ . '\render_list_shortcode' );
	add_shortcode( 'weave_starter_field', __NAMESPACE__ . '\render_field_shortcode' );
}
add_action( 'init', __NAMESPACE__ . '\register_shortcodes' );

/**
 * Render a grid of Starter Items.
 *
 * Usage: [weave_starter_list columns="3" limit="12" orderby="display_order" order="ASC"]
 * Usage: [weave_starter_list type="category-slug" columns="2" limit="6"]
 *
 * @param array|string $atts Shortcode attributes.
 * @return string Rendered HTML.
 */
function render_list_shortcode( $atts ): string {
	$atts = shortcode_atts( [
		'type'    => '',
		'columns' => 3,
		'limit'   => 12,
		'orderby' => 'display_order',
		'order'   => 'ASC',
		'exclude' => '',
	], $atts, 'weave_starter_list' );

	$query_args = [
		'post_type'      => 'starter_item',
		'posts_per_page' => absint( $atts['limit'] ),
		'post_status'    => 'publish',
	];

	// Map friendly orderby values to WP_Query parameters.
	switch ( $atts['orderby'] ) {
		case 'display_order':
			$query_args['meta_key'] = '_weave_starter_display_order';
			$query_args['orderby']  = 'meta_value_num';
			break;
		case 'price':
			$query_args['meta_key'] = '_weave_starter_price';
			$query_args['orderby']  = 'meta_value_num';
			break;
		default:
			$query_args['orderby'] = sanitize_text_field( $atts['orderby'] );
			break;
	}

	$query_args['order'] = in_array( strtoupper( $atts['order'] ), [ 'ASC', 'DESC' ], true )
		? strtoupper( $atts['order'] )
		: 'ASC';

	// Filter by category taxonomy term.
	if ( ! empty( $atts['type'] ) ) {
		$query_args['tax_query'] = [
			[
				'taxonomy' => 'starter-item-category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $atts['type'] ),
			],
		];
	}

	// Exclude specific post IDs.
	if ( ! empty( $atts['exclude'] ) ) {
		$query_args['post__not_in'] = array_map( 'absint', explode( ',', $atts['exclude'] ) );
	}

	$query   = new \WP_Query( $query_args );
	$columns = absint( $atts['columns'] );

	ob_start();

	if ( $query->have_posts() ) {
		printf(
			'<div class="weave-starter-grid" style="display:grid;grid-template-columns:repeat(%d,1fr);gap:1.5rem;">',
			$columns
		);

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id  = get_the_ID();
			$subtitle = get_post_meta( $post_id, '_weave_starter_subtitle', true );
			$price    = (float) get_post_meta( $post_id, '_weave_starter_price', true );
			$url      = get_post_meta( $post_id, '_weave_starter_url', true );

			echo '<div class="weave-starter-grid__item">';

			if ( has_post_thumbnail() ) {
				echo '<div class="weave-starter-grid__image">';
				the_post_thumbnail( 'medium' );
				echo '</div>';
			}

			echo '<h3 class="weave-starter-grid__title">';
			if ( ! empty( $url ) ) {
				printf( '<a href="%s">', esc_url( $url ) );
			}
			the_title();
			if ( ! empty( $url ) ) {
				echo '</a>';
			}
			echo '</h3>';

			if ( ! empty( $subtitle ) ) {
				printf( '<p class="weave-starter-grid__subtitle">%s</p>', esc_html( $subtitle ) );
			}

			if ( $price > 0 ) {
				printf( '<p class="weave-starter-grid__price">$%s</p>', esc_html( number_format( $price, 2 ) ) );
			}

			echo '</div>';
		}

		echo '</div>';

		wp_reset_postdata();
	}

	return ob_get_clean();
}

/**
 * Render a single meta field value from a Starter Item.
 *
 * Usage: [weave_starter_field field="price" before="$" after=" NZD"]
 * Usage: [weave_starter_field id="123" field="subtitle"]
 *
 * @param array|string $atts Shortcode attributes.
 * @return string Rendered HTML.
 */
function render_field_shortcode( $atts ): string {
	$atts = shortcode_atts( [
		'id'     => 0,
		'field'  => '',
		'before' => '',
		'after'  => '',
	], $atts, 'weave_starter_field' );

	if ( empty( $atts['field'] ) ) {
		return '';
	}

	$post_id = absint( $atts['id'] ) ?: get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	// Prepend the meta prefix if not already present.
	$meta_key = $atts['field'];
	if ( 0 !== strpos( $meta_key, '_weave_starter_' ) ) {
		$meta_key = '_weave_starter_' . sanitize_key( $meta_key );
	}

	$value = get_post_meta( $post_id, $meta_key, true );

	if ( '' === $value || false === $value ) {
		return '';
	}

	return wp_kses_post( $atts['before'] ) . esc_html( (string) $value ) . wp_kses_post( $atts['after'] );
}
