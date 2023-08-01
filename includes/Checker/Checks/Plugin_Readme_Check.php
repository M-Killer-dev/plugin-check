<?php
/**
 * Class WordPress\Plugin_Check\Checker\Checks\Plugin_Readme_Check
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Checker\Checks;

use WordPress\Plugin_Check\Checker\Check_Categories;
use WordPress\Plugin_Check\Checker\Check_Result;
use WordPress\Plugin_Check\Traits\Stable_Check;

/**
 * Check the plugins readme file and contents.
 *
 * @since n.e.x.t
 */
class Plugin_Readme_Check extends Abstract_File_Check {

	use Stable_Check;

	/**
	 * Gets the categories for the check.
	 *
	 * Every check must have at least one category.
	 *
	 * @since n.e.x.t
	 *
	 * @return array The categories for the check.
	 */
	public function get_categories() {
		return array( Check_Categories::CATEGORY_PLUGIN_REPO );
	}

	/**
	 * Check the readme file.
	 *
	 * @since n.e.x.t
	 *
	 * @param Check_Result $result The Check Result to amend.
	 * @param array        $files  Array of plugin files.
	 */
	protected function check_files( Check_Result $result, array $files ) {
		// Find the readme file.
		$readme = self::filter_files_by_regex( $files, '/readme\.(txt|md)$/i' );

		// If the readme file does not exist, add a warning and skip other tests.
		if ( empty( $readme ) ) {
			$result->add_message(
				false,
				__( 'The plugin readme.txt does not exist.', 'plugin-check' ),
				array(
					'code' => 'no_plugin_readme',
					'file' => 'readme.txt',
				)
			);

			return;
		}

		// Check the readme file for default text.
		$this->check_default_text( $result, $readme );

		// Check the readme file for a valid license.
		$this->check_license( $result, $readme );

		// Check the readme file for a valid version.
		$this->check_stable_tag( $result, $readme );
	}

	/**
	 * Checks the readme file for default text.
	 *
	 * @since n.e.x.t
	 *
	 * @param Check_Result $result The Check Result to amend.
	 * @param array        $files  Array of plugin files.
	 */
	private function check_default_text( Check_Result $result, array $files ) {
		$default_text_patterns = array(
			'Here is a short description of the plugin.',
			'Tags: tag1',
			'Donate link: http://example.com/',
		);

		$file = '';
		foreach ( $default_text_patterns as $pattern ) {
			$file = self::file_str_contains( $files, $pattern );
			if ( $file ) {
				break;
			}
		}

		if ( $file ) {
			$result->add_message(
				false,
				sprintf(
					/* translators: %s: readme file */
					__( 'The %s appears to contain default text.', 'plugin-check' ),
					str_replace( $result->plugin()->path( '/' ), '', $file )
				),
				array(
					'code' => 'default_readme_text',
					'file' => $file,
				)
			);
		}
	}

	/**
	 * Checks the readme file for a valid license.
	 *
	 * @since n.e.x.t
	 *
	 * @param Check_Result $result The Check Result to amend.
	 * @param array        $files  Array of plugin files.
	 */
	private function check_license( Check_Result $result, array $files ) {
		$matches = array();
		// Get the license from the readme file.
		$file = self::file_preg_match( '/(License:|License URI:)\s*(.+)*/i', $files, $matches );

		if ( empty( $matches ) ) {
			return;
		}

		// Test for a valid SPDX license identifier.
		if ( ! preg_match( '/^([a-z0-9\-\+\.]+)(\sor\s([a-z0-9\-\+\.]+))*$/i', $matches[2] ) ) {
			$result->add_message(
				false,
				sprintf(
					/* translators: %s: readme file */
					__( 'Your plugin has an invalid license declared. Please update your %s with a valid SPDX license identifier.', 'plugin-check' ),
					str_replace( $result->plugin()->path( '/' ), '', $file )
				),
				array(
					'code' => 'invalid_license',
					'file' => $file,
				)
			);
		}
	}

	/**
	 * Checks the readme file stable tag.
	 *
	 * @since n.e.x.t
	 *
	 * @param Check_Result $result The Check Result to amend.
	 * @param array        $files  Array of plugin files.
	 */
	private function check_stable_tag( Check_Result $result, array $files ) {
		$matches = array();
		// Get the Stable tag from readme file.
		$file = self::file_preg_match( '/Stable tag:\s*([a-z0-9\.]+)/i', $files, $matches );
		if ( ! $file ) {
			return;
		}

		$stable_tag = isset( $matches[1] ) ? $matches[1] : '';

		if ( 'trunk' === $stable_tag ) {
			$result->add_message(
				false,
				__( "It's recommended not to use 'Stable Tag: trunk'.", 'plugin-check' ),
				array(
					'code' => 'trunk_stable_tag',
					'file' => $file,
				)
			);
		}

		// Check the readme file Stable tag against the plugin's main file version.
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $result->plugin()->basename() );

		if (
			$stable_tag && ! empty( $plugin_data['Version'] ) &&
			$stable_tag !== $plugin_data['Version']
		) {
			$result->add_message(
				false,
				sprintf(
					/* translators: %s: readme file */
					__( 'The Stable Tag in your %s file does not match the version in your main plugin file.', 'plugin-check' ),
					str_replace( $result->plugin()->path( '/' ), '', $file )
				),
				array(
					'code' => 'stable_tag_mismatch',
					'file' => $file,
				)
			);
		}
	}
}
