<?php
/**
 * OPTIONAL MODULE: DataViews Admin Page.
 * Remove this file if not needed for your plugin.
 *
 * Registers a submenu page under the CPT menu that renders a React-powered
 * DataViews interface for browsing, searching, and managing CPT items.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\AdminPage;

defined( 'ABSPATH' ) || exit;

/**
 * Register the DataViews admin page as a CPT submenu.
 */
function add_admin_page(): void {
	add_submenu_page(
		'edit.php?post_type=starter_item',
		__( 'Browse Items', 'weave-starter-plugin' ),
		__( 'Browse Items', 'weave-starter-plugin' ),
		'edit_posts',
		'weave-starter-admin',
		__NAMESPACE__ . '\render_admin_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_admin_page' );

/**
 * Render the admin page wrapper. React mounts into the inner div.
 */
function render_admin_page(): void {
	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Browse Starter Items', 'weave-starter-plugin' ) . '</h1>';
	echo '<div id="weave-starter-admin"></div>';
	echo '</div>';
}

/**
 * Enqueue the React DataViews app on the admin page only.
 *
 * @param string $hook The current admin page hook suffix.
 */
function enqueue_admin_assets( string $hook ): void {
	if ( 'starter_item_page_weave-starter-admin' !== $hook ) {
		return;
	}

	$asset_file = WEAVE_STARTER_PLUGIN_DIR . 'build/admin/index.asset.php';
	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = require $asset_file;

	wp_enqueue_script(
		'weave-starter-admin',
		WEAVE_STARTER_PLUGIN_URL . 'build/admin/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	wp_enqueue_style( 'wp-components' );

	// Enqueue optional layout styles if they exist.
	$css_file = WEAVE_STARTER_PLUGIN_DIR . 'build/admin/style-index.css';
	if ( file_exists( $css_file ) ) {
		wp_enqueue_style(
			'weave-starter-admin',
			WEAVE_STARTER_PLUGIN_URL . 'build/admin/style-index.css',
			[ 'wp-components' ],
			$asset['version']
		);
	}
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_assets' );
