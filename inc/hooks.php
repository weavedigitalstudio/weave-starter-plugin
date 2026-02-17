<?php
/**
 * General hooks and filters.
 *
 * Handles activation/deactivation, plugin action links,
 * and conditional dashboard cleanup.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\Hooks;

defined( 'ABSPATH' ) || exit;

/**
 * Flush rewrite rules on activation so the CPT permalinks work immediately.
 */
register_activation_hook( WEAVE_STARTER_PLUGIN_FILE, function (): void {
	\WeaveStarterPlugin\PostTypes\register_post_types();
	\WeaveStarterPlugin\PostTypes\register_taxonomies();
	flush_rewrite_rules();
} );

/**
 * Flush rewrite rules on deactivation to clean up.
 */
register_deactivation_hook( WEAVE_STARTER_PLUGIN_FILE, function (): void {
	flush_rewrite_rules();
} );

/**
 * Add a "Settings" link to the plugin row on the Plugins screen.
 *
 * @param array $links Existing action links.
 * @return array Modified action links.
 */
function add_plugin_action_links( array $links ): array {
	$settings_link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'admin.php?page=weave-starter-plugin' ) ),
		esc_html__( 'Settings', 'weave-starter-plugin' )
	);
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter(
	'plugin_action_links_' . plugin_basename( WEAVE_STARTER_PLUGIN_FILE ),
	__NAMESPACE__ . '\add_plugin_action_links'
);

/**
 * Optionally remove some default dashboard widgets.
 *
 * Controlled by the cleanup_dashboard setting.
 */
function maybe_cleanup_dashboard(): void {
	$settings = \WeaveStarterPlugin\SettingsPage\get_settings();

	if ( empty( $settings['cleanup_dashboard'] ) ) {
		return;
	}

	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\maybe_cleanup_dashboard' );

/**
 * Log debug messages when WP_DEBUG and the plugin debug_mode are both enabled.
 *
 * @param string $message The message to log.
 */
function debug_log( string $message ): void {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	$settings = \WeaveStarterPlugin\SettingsPage\get_settings();

	if ( empty( $settings['debug_mode'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( '[Weave Starter Plugin] ' . $message );
}
