/**
 * OPTIONAL MODULE: DataViews Admin Page.
 * Remove this directory (src/js/admin/) if not needed for your plugin.
 *
 * Entry point for the DataViews admin screen.
 * Mounts into #weave-starter-admin rendered by inc/admin-page.php.
 */
import { createRoot } from '@wordpress/element';
import App from './components/App';
import './style.scss';

const container = document.getElementById( 'weave-starter-admin' );

if ( container ) {
	const root = createRoot( container );
	root.render( <App /> );
}
