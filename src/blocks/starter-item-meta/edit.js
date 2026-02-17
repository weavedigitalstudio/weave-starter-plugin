/**
 * Starter Item Meta block — editor component.
 *
 * Uses useEntityProp from @wordpress/core-data to read and write post meta
 * directly. No attributes are stored in the block content — all data lives
 * in the post meta fields registered in inc/post-types.php.
 */
import { __ } from '@wordpress/i18n';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { TextControl, TextareaControl } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps( {
		className: 'weave-starter-meta-block',
	} );

	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const updateMeta = ( key, value ) => {
		setMeta( { ...meta, [ key ]: value } );
	};

	return (
		<div { ...blockProps }>
			<h3 className="weave-starter-meta-block__title">
				{ __( 'Starter Item Details', 'weave-starter-plugin' ) }
			</h3>

			<TextControl
				label={ __( 'Subtitle', 'weave-starter-plugin' ) }
				value={ meta?._weave_starter_subtitle || '' }
				onChange={ ( value ) =>
					updateMeta( '_weave_starter_subtitle', value )
				}
			/>

			<TextareaControl
				label={ __( 'Description', 'weave-starter-plugin' ) }
				value={ meta?._weave_starter_description || '' }
				onChange={ ( value ) =>
					updateMeta( '_weave_starter_description', value )
				}
				rows={ 3 }
			/>

			<TextControl
				label={ __( 'Price', 'weave-starter-plugin' ) }
				type="number"
				step="0.01"
				min="0"
				value={
					meta?._weave_starter_price !== undefined
						? String( meta._weave_starter_price )
						: ''
				}
				onChange={ ( value ) =>
					updateMeta(
						'_weave_starter_price',
						value ? parseFloat( value ) : 0
					)
				}
			/>

			<TextControl
				label={ __( 'URL', 'weave-starter-plugin' ) }
				type="url"
				value={ meta?._weave_starter_url || '' }
				onChange={ ( value ) =>
					updateMeta( '_weave_starter_url', value )
				}
				placeholder="https://"
			/>

			<TextControl
				label={ __( 'Display Order', 'weave-starter-plugin' ) }
				type="number"
				step="1"
				min="0"
				value={
					meta?._weave_starter_display_order !== undefined
						? String( meta._weave_starter_display_order )
						: '0'
				}
				onChange={ ( value ) =>
					updateMeta(
						'_weave_starter_display_order',
						value ? parseInt( value, 10 ) : 0
					)
				}
			/>
		</div>
	);
}
