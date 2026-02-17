/**
 * OPTIONAL MODULE: DataViews Admin App.
 *
 * Renders a DataViews-powered list/grid of Starter Item CPT entries.
 * Uses @wordpress/core-data for data fetching — no custom REST endpoints.
 */
import { useState } from '@wordpress/element';
import { DataViews } from '@wordpress/dataviews';
import { __ } from '@wordpress/i18n';
import { useItems } from '../hooks/useItems';
import { fields, getActions } from '../config/itemConfig';

const DEFAULT_VIEW = {
	type: 'table',
	perPage: 10,
	page: 1,
	sort: {
		field: 'date',
		direction: 'desc',
	},
	search: '',
	filters: [],
	hiddenFields: [ 'modified' ],
	layout: {},
};

export default function App() {
	const [ view, setView ] = useState( DEFAULT_VIEW );
	const { records, total, totalPages, isLoading } = useItems( view );
	const actions = getActions();

	return (
		<DataViews
			data={ records }
			fields={ fields }
			view={ view }
			onChangeView={ setView }
			actions={ actions }
			paginationInfo={ {
				totalItems: total,
				totalPages,
			} }
			isLoading={ isLoading }
			defaultLayouts={ { table: {}, grid: {} } }
			header={
				<span>
					{ __( 'Starter Items', 'weave-starter-plugin' ) }
				</span>
			}
		/>
	);
}
