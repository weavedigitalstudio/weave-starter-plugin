/**
 * Starter Item Meta block registration.
 *
 * This block provides the meta field editing UI inside the block editor.
 * It stores data in post meta (via useEntityProp), not in the block content.
 */
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';
import './editor.scss';

registerBlockType( metadata, {
	edit: Edit,
	save: () => null,
} );
