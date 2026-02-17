<?php
/**
 * Plugin uninstall handler.
 *
 * Removes all plugin data from the database when the plugin is deleted
 * from the WordPress admin (not just deactivated).
 *
 * @package WeaveStarterPlugin
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Remove plugin settings.
delete_option( 'weave_starter_plugin_settings' );

// Remove GitHub updater transient.
delete_transient( 'weave_starter_plugin_github_response' );

/*
 * Optionally remove all CPT posts and their meta data.
 * Uncomment the block below to delete everything on uninstall.
 * WARNING: This is destructive and cannot be undone.
 *
 * $posts = get_posts( [
 *     'post_type'   => 'starter_item',
 *     'numberposts' => -1,
 *     'post_status' => 'any',
 * ] );
 *
 * foreach ( $posts as $post ) {
 *     wp_delete_post( $post->ID, true );
 * }
 */
