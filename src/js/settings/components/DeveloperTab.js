/**
 * Developer settings tab.
 *
 * Debug mode and UI cleanup options.
 */
import {
	PanelBody,
	PanelRow,
	ToggleControl,
	Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function DeveloperTab( {
	settings,
	setSettings,
	saveSettings,
	isSaving,
} ) {
	const updateSetting = ( key, value ) => {
		setSettings( { ...settings, [ key ]: value } );
	};

	return (
		<div style={ { marginTop: '16px' } }>
			<PanelBody
				title={ __( 'Developer Options', 'weave-starter-plugin' ) }
				initialOpen
			>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Debug Mode', 'weave-starter-plugin' ) }
						help={ __(
							'Log plugin events to the WordPress debug log when WP_DEBUG is enabled.',
							'weave-starter-plugin'
						) }
						checked={ settings.debug_mode }
						onChange={ ( value ) =>
							updateSetting( 'debug_mode', value )
						}
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Dashboard Cleanup',
							'weave-starter-plugin'
						) }
						help={ __(
							'Remove Quick Draft and WordPress Events widgets from the dashboard.',
							'weave-starter-plugin'
						) }
						checked={ settings.cleanup_dashboard }
						onChange={ ( value ) =>
							updateSetting( 'cleanup_dashboard', value )
						}
					/>
				</PanelRow>
			</PanelBody>

			<div style={ { marginTop: '16px' } }>
				<Button
					variant="primary"
					isBusy={ isSaving }
					disabled={ isSaving }
					onClick={ () => saveSettings( settings ) }
				>
					{ isSaving
						? __( 'Saving…', 'weave-starter-plugin' )
						: __( 'Save Settings', 'weave-starter-plugin' ) }
				</Button>
			</div>
		</div>
	);
}
