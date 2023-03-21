<?php
/**
 * Sets up the CLI command early in the WordPress load process.
 *
 * This is necessary to setup the environment to perform runtime checks.
 *
 * @package plugin-check
 * @since n.e.x.t
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

// Check if the plugin autoloading is set up.
if ( ! class_exists( 'WordPress\Plugin_Check\CLI\Plugin_Check_Command' ) ) {
	// Check the autoload file exists.
	if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		WP_CLI::error( 'Plugin Check autoloaded not found.' );
		return;
	}

	// Load the Composer autoloader.
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! isset( $context ) ) {
	$context = new WordPress\Plugin_Check\Plugin_Context( __DIR__ . '/plugin-check.php' );
}

// Create the CLI command instance and add to WP CLI.
$plugin_command = new WordPress\Plugin_Check\CLI\Plugin_Check_Command( $context );
WP_CLI::add_command( 'plugin', $plugin_command );


/**
 * Adds hook to set up the object-cache.php drop-in file.
 *
 * @since n.e.x.t
 */
WP_CLI::add_hook(
	'before_wp_load',
	function() {
		if ( ! file_exists( ABSPATH . 'wp-content/object-cache.php' ) ) {
			copy(  __DIR__ . '/object-cache.copy.php', ABSPATH . 'wp-content/object-cache.php' );
		}
	}
);
