/**
 * General settings tab.
 *
 * CPT toggles and optional feature enable/disable controls.
 * All components from @wordpress/components.
 */
import {
	PanelBody,
	PanelRow,
	ToggleControl,
	Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function GeneralTab( {
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
				title={ __( 'Custom Post Type', 'weave-starter-plugin' ) }
				initialOpen
			>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Enable Starter Items CPT',
							'weave-starter-plugin'
						) }
						help={ __(
							'Register the Starter Items custom post type.',
							'weave-starter-plugin'
						) }
						checked={ settings.enable_cpt }
						onChange={ ( value ) =>
							updateSetting( 'enable_cpt', value )
						}
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Public Visibility',
							'weave-starter-plugin'
						) }
						help={ __(
							'Make the CPT publicly accessible with archive and single views.',
							'weave-starter-plugin'
						) }
						checked={ settings.cpt_public }
						onChange={ ( value ) =>
							updateSetting( 'cpt_public', value )
						}
					/>
				</PanelRow>
			</PanelBody>

			<PanelBody
				title={ __( 'Optional Features', 'weave-starter-plugin' ) }
				initialOpen
			>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Enable Shortcodes',
							'weave-starter-plugin'
						) }
						help={ __(
							'Register shortcodes for use in Beaver Builder and classic editor.',
							'weave-starter-plugin'
						) }
						checked={ settings.enable_shortcodes }
						onChange={ ( value ) =>
							updateSetting( 'enable_shortcodes', value )
						}
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Enable Admin Columns',
							'weave-starter-plugin'
						) }
						help={ __(
							'Add custom columns to the CPT list table.',
							'weave-starter-plugin'
						) }
						checked={ settings.enable_admin_columns }
						onChange={ ( value ) =>
							updateSetting( 'enable_admin_columns', value )
						}
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Enable Admin Page',
							'weave-starter-plugin'
						) }
						help={ __(
							'Add a DataViews-powered admin page for browsing items.',
							'weave-starter-plugin'
						) }
						checked={ settings.enable_admin_page }
						onChange={ ( value ) =>
							updateSetting( 'enable_admin_page', value )
						}
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __(
							'Enable Frontend Block',
							'weave-starter-plugin'
						) }
						help={ __(
							'Register the Interactivity API block for frontend display.',
							'weave-starter-plugin'
						) }
						checked={ settings.enable_frontend_block }
						onChange={ ( value ) =>
							updateSetting( 'enable_frontend_block', value )
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
