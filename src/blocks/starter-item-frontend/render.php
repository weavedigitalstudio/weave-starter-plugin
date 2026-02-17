<?php
/**
 * OPTIONAL MODULE: Starter Item Frontend Block — Server-side render.
 * Remove this file and its directory if not needed for your plugin.
 *
 * Renders a filterable grid of Starter Items using the Interactivity API.
 * The view.js store handles category filtering on the client side.
 *
 * @package WeaveStarterPlugin
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block inner content.
 * @var WP_Block $block      Block instance.
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$limit   = absint( $attributes['limit'] ?? 12 );
$columns = absint( $attributes['columns'] ?? 3 );

// Query starter items.
$query = new WP_Query( [
	'post_type'      => 'starter_item',
	'posts_per_page' => $limit,
	'meta_key'       => '_weave_starter_display_order',
	'orderby'        => 'meta_value_num',
	'order'          => 'ASC',
	'post_status'    => 'publish',
] );

// Get categories for filter buttons.
$categories = get_terms( [
	'taxonomy'   => 'starter-item-category',
	'hide_empty' => true,
] );

if ( is_wp_error( $categories ) ) {
	$categories = [];
}

// Build the initial context for the Interactivity API store.
$context = [
	'activeFilter' => '',
];

?>
<div
	<?php echo get_block_wrapper_attributes( [ 'class' => 'weave-starter-items-display' ] ); ?>
	data-wp-interactive="weave-starter/starter-item-frontend"
	<?php echo wp_interactivity_data_wp_context( $context ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
>
	<?php if ( ! empty( $categories ) ) : ?>
		<div class="weave-starter-filters">
			<button
				class="weave-starter-filter-btn"
				data-wp-on--click="actions.clearFilter"
				data-wp-class--is-active="!state.hasActiveFilter"
			>
				<?php esc_html_e( 'All', 'weave-starter-plugin' ); ?>
			</button>
			<?php foreach ( $categories as $category ) : ?>
				<button
					class="weave-starter-filter-btn"
					data-wp-context="<?php echo esc_attr( wp_json_encode( [ 'slug' => $category->slug ] ) ); ?>"
					data-wp-on--click="actions.setFilter"
					data-wp-class--is-active="state.isFilterActive"
				>
					<?php echo esc_html( $category->name ); ?>
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $query->have_posts() ) : ?>
		<div
			class="weave-starter-items-grid"
			style="grid-template-columns: repeat(<?php echo esc_attr( (string) $columns ); ?>, 1fr);"
		>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$post_id       = get_the_ID();
				$subtitle      = get_post_meta( $post_id, '_weave_starter_subtitle', true );
				$price         = (float) get_post_meta( $post_id, '_weave_starter_price', true );
				$url           = get_post_meta( $post_id, '_weave_starter_url', true );
				$item_cats     = wp_get_post_terms( $post_id, 'starter-item-category', [ 'fields' => 'slugs' ] );
				$item_cat_json = wp_json_encode( is_array( $item_cats ) ? $item_cats : [] );
				?>
				<div
					class="weave-starter-item-card"
					data-wp-context="<?php echo esc_attr( wp_json_encode( [ 'categories' => $item_cats ] ) ); ?>"
					data-wp-bind--hidden="state.isItemHidden"
				>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="weave-starter-item-card__image">
							<?php the_post_thumbnail( 'medium' ); ?>
						</div>
					<?php endif; ?>

					<div class="weave-starter-item-card__content">
						<h3 class="weave-starter-item-card__title">
							<?php if ( ! empty( $url ) ) : ?>
								<a href="<?php echo esc_url( $url ); ?>">
									<?php the_title(); ?>
								</a>
							<?php else : ?>
								<?php the_title(); ?>
							<?php endif; ?>
						</h3>

						<?php if ( ! empty( $subtitle ) ) : ?>
							<p class="weave-starter-item-card__subtitle">
								<?php echo esc_html( $subtitle ); ?>
							</p>
						<?php endif; ?>

						<?php if ( $price > 0 ) : ?>
							<p class="weave-starter-item-card__price">
								$<?php echo esc_html( number_format( $price, 2 ) ); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'No starter items found.', 'weave-starter-plugin' ); ?></p>
	<?php endif; ?>
</div>
