<?php
/**
 * Class WordPress\Plugin_Check\Plugin_Context
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check;

/**
 * Class representing the context in which the plugin is running.
 *
 * @since n.e.x.t
 */
class Plugin_Context {

	/**
	 * Absolute path of the plugin main file.
	 *
	 * @since n.e.x.t
	 * @var string
	 */
	protected $main_file;

	/**
	 * Constructor.
	 *
	 * @since n.e.x.t
	 *
	 * @param string $main_file The absolute path to the plugin main file.
	 */
	public function __construct( $main_file ) {
		$this->main_file = $main_file;
	}

	/**
	 * Returns the plugin basename.
	 *
	 * @since n.e.x.t
	 *
	 * @return string Plugin basename.
	 */
	public function basename() {
		return plugin_basename( $this->main_file );
	}

	/**
	 * Returns the absolute path for a relative path to the plugin directory.
	 *
	 * @since n.e.x.t
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Absolute path.
	 */
	public function path( $relative_path = '/' ) {
		return plugin_dir_path( $this->main_file ) . ltrim( $relative_path, '/' );
	}

	/**
	 * Returns the full URL for a pth relative to the plugin directory.
	 *
	 * @since n.e.x.t
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Full URL.
	 */
	public function url( $relative_path = '/' ) {
		return plugin_dir_url( $this->main_file ) . ltrim( $relative_path, '/' );
	}
}
