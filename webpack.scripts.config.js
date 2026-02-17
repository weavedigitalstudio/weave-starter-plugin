/**
 * Custom webpack config for non-block scripts.
 *
 * Extends the default @wordpress/scripts config to build:
 * - Settings page (CORE):  src/js/settings/index.js → build/settings/index.js
 * - Admin page (OPTIONAL): src/js/admin/index.js    → build/admin/index.js
 *
 * Blocks are built separately via `npm run build:blocks` using the
 * default wp-scripts config with --blocks-manifest.
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		'settings/index': path.resolve( process.cwd(), 'src/js/settings/index.js' ),
		'admin/index': path.resolve( process.cwd(), 'src/js/admin/index.js' ),
	},
	output: {
		...defaultConfig.output,
		path: path.resolve( process.cwd(), 'build' ),
	},
};
