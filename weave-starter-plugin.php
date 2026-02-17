<?php
/**
 * Plugin Name:       Weave Starter Plugin
 * Plugin URI:        https://github.com/weavedigitalstudio/weave-starter-plugin
 * Description:       A modern WordPress plugin scaffold for Weave Digital Studio and HumanKind.
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            Weave Digital Studio
 * Author URI:        https://weave.co.nz
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       weave-starter-plugin
 * Domain Path:       /languages
 * GitHub Plugin URI: weavedigitalstudio/weave-starter-plugin
 *
 * @package WeaveStarterPlugin
 */

declare( strict_types=1 );

namespace WeaveStarterPlugin;

defined( 'ABSPATH' ) || exit;

// Plugin constants.
define( 'WEAVE_STARTER_PLUGIN_VERSION', '1.0.0' );
define( 'WEAVE_STARTER_PLUGIN_FILE', __FILE__ );
define( 'WEAVE_STARTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WEAVE_STARTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Core modules — always loaded.
require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/settings-page.php';
require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/post-types.php';
require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/blocks.php';
require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/hooks.php';
require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/github-updater.php';

// Optional modules — loaded based on settings.
$weave_starter_settings = SettingsPage\get_settings();

if ( ! empty( $weave_starter_settings['enable_admin_page'] ) ) {
	require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/admin-page.php';
}

if ( ! empty( $weave_starter_settings['enable_shortcodes'] ) ) {
	require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/shortcodes.php';
}

if ( ! empty( $weave_starter_settings['enable_admin_columns'] ) ) {
	require_once WEAVE_STARTER_PLUGIN_DIR . 'inc/admin-columns.php';
}
