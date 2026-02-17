/**
 * OPTIONAL MODULE: Interactivity API store for the frontend display block.
 *
 * Handles category filtering. Each filter button and item card has its own
 * context (via data-wp-context). The parent wrapper holds the activeFilter.
 * Child contexts inherit from parents, so getContext() returns merged values.
 */
import { store, getContext } from '@wordpress/interactivity';

store( 'weave-starter/starter-item-frontend', {
	state: {
		/**
		 * Whether any filter is currently active.
		 *
		 * @return {boolean} True if a filter is set.
		 */
		get hasActiveFilter() {
			const { activeFilter } = getContext();
			return activeFilter !== '';
		},

		/**
		 * Whether this filter button is the currently active one.
		 * Called in the context of a filter button (which has a slug).
		 *
		 * @return {boolean} True if this button's slug matches the active filter.
		 */
		get isFilterActive() {
			const { activeFilter, slug } = getContext();
			return activeFilter === slug;
		},

		/**
		 * Whether this item card should be hidden.
		 * Called in the context of an item card (which has categories).
		 *
		 * @return {boolean} True if the item should be hidden.
		 */
		get isItemHidden() {
			const { activeFilter, categories } = getContext();

			// Show all items when no filter is active.
			if ( ! activeFilter ) {
				return false;
			}

			// Hide items that don't match the active category filter.
			return ! categories?.includes( activeFilter );
		},
	},

	actions: {
		/**
		 * Set the active filter to this button's category slug.
		 */
		setFilter() {
			const context = getContext();
			// Read the slug from this button's context, set it on the parent.
			context.activeFilter = context.slug;
		},

		/**
		 * Clear the active filter to show all items.
		 */
		clearFilter() {
			const context = getContext();
			context.activeFilter = '';
		},
	},
} );
