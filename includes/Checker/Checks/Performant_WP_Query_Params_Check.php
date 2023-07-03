<?php
/**
 * Class WordPress\Plugin_Check\Checker\Checks\Performant_WP_Query_Params_Check
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Checker\Checks;

use WordPress\Plugin_Check\Checker\Check_Categories;

/**
 * Check for running WordPress performant WP_Query params sniffs.
 *
 * @since n.e.x.t
 */
class Performant_WP_Query_Params_Check extends Abstract_PHP_CodeSniffer_Check {

	/**
	 * Gets the category of the check.
	 *
	 * @since n.e.x.t
	 */
	public function get_category() {
		return Check_Categories::CATEGORY_PERFORMANCE;
	}

	/**
	 * Returns an associative array of arguments to pass to PHPCS.
	 *
	 * @since n.e.x.t
	 *
	 * @return array An associative array of PHPCS CLI arguments.
	 */
	protected function get_args() {
		return array(
			'extensions' => 'php',
			'standard'   => 'WordPress,WordPressVIPMinimum',
			'sniffs'     => 'WordPress.DB.SlowDBQuery,WordPressVIPMinimum.Performance.WPQueryParams',
		);
	}
}
