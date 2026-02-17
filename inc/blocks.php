<?php
/**
 * Block registration.
 *
 * Auto-discovers and registers all blocks from the build/blocks/ directory.
 * Skips the frontend display block if disabled in settings.
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin\Blocks;

defined( 'ABSPATH' ) || exit;

/**
 * Register all blocks found in the build directory.
 *
 * Each subdirectory of build/blocks/ containing a block.json is registered
 * automatically. The frontend block can be disabled via plugin settings.
 */
function register_blocks(): void {
	$blocks_dir = WEAVE_STARTER_PLUGIN_DIR . 'build/blocks/';

	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	$settings    = \WeaveStarterPlugin\SettingsPage\get_settings();
	$skip_blocks = [];

	// Skip frontend block if disabled in settings.
	if ( empty( $settings['enable_frontend_block'] ) ) {
		$skip_blocks[] = 'starter-item-frontend';
	}

	// Skip meta block if CPT is disabled.
	if ( empty( $settings['enable_cpt'] ) ) {
		$skip_blocks[] = 'starter-item-meta';
	}

	$block_folders = glob( $blocks_dir . '*', GLOB_ONLYDIR );

	if ( ! $block_folders ) {
		return;
	}

	foreach ( $block_folders as $block_folder ) {
		$block_name = basename( $block_folder );

		if ( in_array( $block_name, $skip_blocks, true ) ) {
			continue;
		}

		register_block_type( $block_folder );
	}
}
add_action( 'init', __NAMESPACE__ . '\register_blocks' );
