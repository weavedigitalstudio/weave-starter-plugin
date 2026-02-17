/**
 * OPTIONAL MODULE: Custom hook for fetching CPT items.
 *
 * Uses @wordpress/core-data useEntityRecords to fetch Starter Items
 * from the WordPress REST API. Change 'starter_item' to your CPT slug
 * when adapting this scaffold.
 */
import { useEntityRecords } from '@wordpress/core-data';

export function useItems( view ) {
	const { records, totalItems, totalPages, isResolving, hasResolved } =
		useEntityRecords( 'postType', 'starter_item', {
			per_page: view.perPage,
			page: view.page,
			orderby: view.sort?.field === 'title' ? 'title' : 'date',
			order: view.sort?.direction || 'desc',
			search: view.search || undefined,
			status: 'any',
		} );

	return {
		records: records || [],
		total: totalItems || 0,
		totalPages: totalPages || 0,
		isLoading: isResolving,
		hasResolved,
	};
}
