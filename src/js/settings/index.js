/**
 * Settings page entry point.
 *
 * Mounts the React settings app into the #weave-starter-settings div
 * rendered by inc/settings-page.php.
 */
import { createRoot } from '@wordpress/element';
import SettingsApp from './components/SettingsApp';

const container = document.getElementById( 'weave-starter-settings' );

if ( container ) {
	const root = createRoot( container );
	root.render( <SettingsApp /> );
}
