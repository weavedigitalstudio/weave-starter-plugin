<?php
/**
 * Settings page registration and REST API exposure.
 *
 * Renders a minimal wrapper div; the React app (built from src/js/settings/)
 * mounts into it. All UI uses @wordpress/components — no custom CSS needed.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\SettingsPage;

defined( 'ABSPATH' ) || exit;

const OPTION_NAME = 'weave_starter_plugin_settings';

/**
 * Default settings values.
 *
 * These are used on first activation before the user saves anything.
 *
 * @return array<string, bool> Settings with default values.
 */
function get_defaults(): array {
	return [
		'enable_cpt'            => true,
		'cpt_public'            => true,
		'enable_shortcodes'     => true,
		'enable_admin_columns'  => true,
		'enable_admin_page'     => false,
		'enable_frontend_block' => true,
		'debug_mode'            => false,
		'cleanup_dashboard'     => false,
	];
}

/**
 * Retrieve plugin settings merged with defaults.
 *
 * @return array<string, bool> Current settings.
 */
function get_settings(): array {
	$saved = get_option( OPTION_NAME, [] );
	return wp_parse_args( $saved, get_defaults() );
}

/**
 * Register the settings page under a top-level admin menu.
 */
function add_settings_page(): void {
	add_menu_page(
		__( 'Weave Starter', 'weave-starter-plugin' ),
		__( 'Weave Starter', 'weave-starter-plugin' ),
		'manage_options',
		'weave-starter-plugin',
		__NAMESPACE__ . '\render_settings_page',
		'dashicons-portfolio',
		80
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_settings_page' );

/**
 * Render the settings page wrapper. React mounts into the inner div.
 */
function render_settings_page(): void {
	echo '<div class="wrap"><div id="weave-starter-settings"></div></div>';
}

/**
 * Enqueue the React settings app on the settings page only.
 *
 * @param string $hook The current admin page hook suffix.
 */
function enqueue_settings_assets( string $hook ): void {
	if ( 'toplevel_page_weave-starter-plugin' !== $hook ) {
		return;
	}

	$asset_file = WEAVE_STARTER_PLUGIN_DIR . 'build/settings/index.asset.php';
	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = require $asset_file;

	wp_enqueue_script(
		'weave-starter-settings',
		WEAVE_STARTER_PLUGIN_URL . 'build/settings/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	// Belt-and-braces: ensure wp-components styles are loaded.
	wp_enqueue_style( 'wp-components' );

	wp_localize_script(
		'weave-starter-settings',
		'weaveStarterPlugin',
		[
			'version' => WEAVE_STARTER_PLUGIN_VERSION,
			'iconUrl' => \WeaveStarterPlugin\GitHubUpdater\Weave_Starter_Plugin_Updater::ICON_SMALL,
		]
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_settings_assets' );

/**
 * Register the plugin setting for both admin and REST API contexts.
 *
 * Using register_setting with show_in_rest makes the option available
 * at GET/POST /wp/v2/settings. The React app reads and writes via this
 * endpoint using @wordpress/api-fetch.
 */
function register_plugin_settings(): void {
	register_setting(
		'weave_starter_plugin',
		OPTION_NAME,
		[
			'type'              => 'object',
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_settings',
			'default'           => get_defaults(),
			'show_in_rest'      => [
				'schema' => [
					'type'       => 'object',
					'properties' => [
						'enable_cpt'            => [ 'type' => 'boolean' ],
						'cpt_public'            => [ 'type' => 'boolean' ],
						'enable_shortcodes'     => [ 'type' => 'boolean' ],
						'enable_admin_columns'  => [ 'type' => 'boolean' ],
						'enable_admin_page'     => [ 'type' => 'boolean' ],
						'enable_frontend_block' => [ 'type' => 'boolean' ],
						'debug_mode'            => [ 'type' => 'boolean' ],
						'cleanup_dashboard'     => [ 'type' => 'boolean' ],
					],
				],
			],
		]
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\register_plugin_settings' );
add_action( 'rest_api_init', __NAMESPACE__ . '\register_plugin_settings' );

/**
 * Sanitise settings input. Casts all values to booleans.
 *
 * @param mixed $input Raw input from the REST API or form submission.
 * @return array<string, bool> Sanitised settings.
 */
function sanitize_settings( $input ): array {
	$defaults  = get_defaults();
	$sanitised = [];

	foreach ( $defaults as $key => $default ) {
		$sanitised[ $key ] = isset( $input[ $key ] ) ? (bool) $input[ $key ] : $default;
	}

	return $sanitised;
}
