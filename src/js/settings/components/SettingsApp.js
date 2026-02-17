/**
 * Main settings app with tabbed navigation.
 *
 * Uses @wordpress/components TabPanel for General / Developer / About tabs.
 * All UI is built with WordPress components — no custom CSS needed.
 */
import { TabPanel, Notice, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSettings } from '../hooks/useSettings';
import GeneralTab from './GeneralTab';
import DeveloperTab from './DeveloperTab';
import AboutTab from './AboutTab';

export default function SettingsApp() {
	const {
		settings,
		setSettings,
		saveSettings,
		isSaving,
		notice,
		setNotice,
	} = useSettings();

	if ( settings === null ) {
		return <Spinner />;
	}

	const tabs = [
		{
			name: 'general',
			title: __( 'General', 'weave-starter-plugin' ),
		},
		{
			name: 'developer',
			title: __( 'Developer', 'weave-starter-plugin' ),
		},
		{
			name: 'about',
			title: __( 'About', 'weave-starter-plugin' ),
		},
	];

	return (
		<div style={ { maxWidth: '800px' } }>
			<h1>{ __( 'Weave Starter Plugin', 'weave-starter-plugin' ) }</h1>

			{ notice && (
				<Notice
					status={ notice.status }
					isDismissible
					onDismiss={ () => setNotice( null ) }
				>
					{ notice.message }
				</Notice>
			) }

			<TabPanel tabs={ tabs }>
				{ ( tab ) => {
					switch ( tab.name ) {
						case 'general':
							return (
								<GeneralTab
									settings={ settings }
									setSettings={ setSettings }
									saveSettings={ saveSettings }
									isSaving={ isSaving }
								/>
							);
						case 'developer':
							return (
								<DeveloperTab
									settings={ settings }
									setSettings={ setSettings }
									saveSettings={ saveSettings }
									isSaving={ isSaving }
								/>
							);
						case 'about':
							return <AboutTab />;
						default:
							return null;
					}
				} }
			</TabPanel>
		</div>
	);
}
