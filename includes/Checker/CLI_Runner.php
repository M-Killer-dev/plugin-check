<?php
/**
 * Class WordPress\Plugin_Check\Checker\CLI_Runner
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Checker;

/**
 * CLI Runner class.
 *
 * @since n.e.x.t
 */
class CLI_Runner extends Abstract_Check_Runner {

	/**
	 * Checks if the current request is a CLI request for the Plugin Checker.
	 *
	 * @since n.e.x.t
	 *
	 * @return bool
	 */
	public function is_plugin_check() {
		if ( empty( $_SERVER['argv'] ) ) {
			return false;
		}

		if (
			'wp' === $_SERVER['argv'][0] &&
			'plugin' === $_SERVER['argv'][1] &&
			'check' === $_SERVER['argv'][2]
		) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the plugin main file based on the request.
	 *
	 * @since n.e.x.t
	 *
	 * @return string The absolute path to the plugin main file.
	 */
	private function get_plugin_main_file() {
		// Get the plugin name from the command line arguments.
		$plugin_name = $_SERVER['argv'][3];

		return WP_PLUGIN_DIR . '/' . $plugin_name . '/' . $plugin_name . '.php';
	}

	/**
	 * Retruns an instance of the Checks class.
	 *
	 * @since n.e.x.t
	 *
	 * @return Checks
	 */
	protected function get_checks_instance() {
		return new Checks( $this->get_plugin_main_file() );
	}

	/**
	 * Returns an array of Check instances to run.
	 *
	 * @since n.e.x.t
	 *
	 * @return array An array of Check instances to run.
	 */
	protected function get_check_slugs_to_run() {
		$checks = array();

		foreach ( $_SERVER['argv'] as $value ) {
			if ( false !== strpos( $value, '--checks=' ) ) {
				$checks = explode( ',', str_replace( '--checks=', '', $value ) );
				break;
			}
		}

		return $checks;
	}

	/**
	 * Returns the Check instances to run.
	 *
	 * @since n.e.x.t
	 *
	 * @return array An array of Check instances.
	 */
	protected function get_checks_to_run() {
		$check_slugs = $this->get_check_slugs_to_run();
		$all_checks  = $this->get_checks_instance()->get_checks();

		if ( empty( $checks ) ) {
			return $all_checks;
		}

		return array_intersect_key( $all_checks, array_flip( $checks ) );
	}
}
