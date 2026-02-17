/**
 * OPTIONAL MODULE: Frontend block editor component.
 *
 * Shows a placeholder in the editor since the block is server-rendered
 * on the frontend via render.php.
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	Placeholder,
} from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { limit, columns } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Display Settings', 'weave-starter-plugin' ) }
				>
					<RangeControl
						label={ __(
							'Number of Items',
							'weave-starter-plugin'
						) }
						value={ limit }
						onChange={ ( value ) =>
							setAttributes( { limit: value } )
						}
						min={ 1 }
						max={ 48 }
					/>
					<RangeControl
						label={ __( 'Columns', 'weave-starter-plugin' ) }
						value={ columns }
						onChange={ ( value ) =>
							setAttributes( { columns: value } )
						}
						min={ 1 }
						max={ 6 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<Placeholder
					icon="portfolio"
					label={ __(
						'Starter Items Display',
						'weave-starter-plugin'
					) }
					instructions={ __(
						'This block displays a filterable grid of starter items on the frontend. Configure the number of items and columns in the block settings panel.',
						'weave-starter-plugin'
					) }
				/>
			</div>
		</>
	);
}
