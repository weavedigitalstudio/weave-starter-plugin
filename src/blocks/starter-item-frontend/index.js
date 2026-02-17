/**
 * OPTIONAL MODULE: Starter Item Frontend Block.
 * Remove this directory (src/blocks/starter-item-frontend/) if not needed.
 *
 * Registration for the Interactivity API frontend display block.
 * This block is server-rendered (render.php) with client-side
 * interactivity (view.js).
 */
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';

registerBlockType( metadata, {
	edit: Edit,
} );
