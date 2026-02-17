/**
 * OPTIONAL MODULE: DataViews field and action configuration.
 *
 * Defines columns (fields) and row-level actions for the DataViews
 * admin screen. Adapt the fields to match your CPT's meta fields.
 */
import { trash, pencil, external } from '@wordpress/icons';
import { __, sprintf } from '@wordpress/i18n';

export const fields = [
	{
		id: 'title',
		label: __( 'Title', 'weave-starter-plugin' ),
		type: 'text',
		enableHiding: false,
		enableSorting: true,
		enableGlobalSearch: true,
		getValue: ( { item } ) => item.title?.rendered || '',
		render: ( { item } ) => {
			const title =
				item.title?.rendered ||
				__( '(no title)', 'weave-starter-plugin' );
			return (
				<a
					href={ `/wp-admin/post.php?post=${ item.id }&action=edit` }
				>
					{ title }
				</a>
			);
		},
	},
	{
		id: 'status',
		label: __( 'Status', 'weave-starter-plugin' ),
		type: 'text',
		enableHiding: true,
		enableSorting: false,
		getValue: ( { item } ) => item.status,
		render: ( { item } ) => {
			const statusLabels = {
				publish: __( 'Published', 'weave-starter-plugin' ),
				draft: __( 'Draft', 'weave-starter-plugin' ),
				pending: __( 'Pending', 'weave-starter-plugin' ),
				private: __( 'Private', 'weave-starter-plugin' ),
			};
			return statusLabels[ item.status ] || item.status;
		},
	},
	{
		id: 'date',
		label: __( 'Date', 'weave-starter-plugin' ),
		type: 'text',
		enableHiding: true,
		enableSorting: true,
		getValue: ( { item } ) => item.date,
		render: ( { item } ) => {
			if ( ! item.date ) {
				return '—';
			}
			const date = new Date( item.date );
			return date.toLocaleDateString( undefined, {
				year: 'numeric',
				month: 'short',
				day: 'numeric',
			} );
		},
	},
	{
		id: 'modified',
		label: __( 'Modified', 'weave-starter-plugin' ),
		type: 'text',
		enableHiding: true,
		enableSorting: false,
		getValue: ( { item } ) => item.modified,
		render: ( { item } ) => {
			if ( ! item.modified ) {
				return '—';
			}
			const date = new Date( item.modified );
			return date.toLocaleDateString( undefined, {
				year: 'numeric',
				month: 'short',
				day: 'numeric',
			} );
		},
	},
];

/**
 * Build row-level actions for the DataViews table.
 *
 * @return {Array} Actions array.
 */
export function getActions() {
	return [
		{
			id: 'edit',
			label: __( 'Edit', 'weave-starter-plugin' ),
			icon: pencil,
			callback: ( items ) => {
				window.location.href = `/wp-admin/post.php?post=${ items[ 0 ].id }&action=edit`;
			},
		},
		{
			id: 'view',
			label: __( 'View', 'weave-starter-plugin' ),
			icon: external,
			callback: ( items ) => {
				if ( items[ 0 ].link ) {
					window.open( items[ 0 ].link, '_blank' );
				}
			},
		},
	];
}
