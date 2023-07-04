<?php
/**
 * Trait With_Mock_Filesystem.
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Test_Utils\Traits;

trait With_Mock_Filesystem {
	/**
	 * Sets up a Mock Filesystem.
	 *
	 * @since n.e.x.t
	 */
	protected function set_up_mock_filesystem() {
		global $wp_filesystem;

		add_filter(
			'filesystem_method_file',
			function() {
				return TESTS_PLUGIN_DIR . '/testdata/Filesystem/WP_Filesystem_MockFilesystem.php';
			}
		);
		add_filter(
			'filesystem_method',
			function() {
				return 'MockFilesystem';
			}
		);

		WP_Filesystem();

		// Simulate that the original object-cache.copy.php file exists.
		$wp_filesystem->put_contents( WP_PLUGIN_CHECK_PLUGIN_DIR_PATH . 'drop-ins/object-cache.copy.php', file_get_contents( WP_PLUGIN_CHECK_PLUGIN_DIR_PATH . 'drop-ins/object-cache.copy.php' ) );
	}

	/**
	 * Sets up a failing Mock Filesystem.
	 *
	 * @since n.e.x.t
	 */
	protected function set_up_failing_mock_filesystem() {
		global $wp_filesystem;

		add_filter(
			'filesystem_method_file',
			function() {
				return TESTS_PLUGIN_DIR . '/testdata/Filesystem/WP_Filesystem_FailingMockFilesystem.php';
			}
		);
		add_filter(
			'filesystem_method',
			function() {
				return 'FailingMockFilesystem';
			}
		);

		WP_Filesystem();

		// Simulate that the original object-cache.copy.php file exists.
		$wp_filesystem->put_contents( WP_PLUGIN_CHECK_PLUGIN_DIR_PATH . 'drop-ins/object-cache.copy.php', file_get_contents( WP_PLUGIN_CHECK_PLUGIN_DIR_PATH . 'drop-ins/object-cache.copy.php' ) );
	}
}
