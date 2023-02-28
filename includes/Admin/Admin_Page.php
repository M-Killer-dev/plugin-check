<?php
/**
 * Class WordPress\Plugin_Check\Admin\Admin_Page
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Admin;

/**
 * Class is handling admin tools page functionality.
 *
 * @since n.e.x.t
 */
class Admin_Page {

	/**
	 * Admin AJAX class instance.
	 *
	 * @since n.e.x.t
	 * @var Admin_AJAX
	 */
	protected $admin_ajax;

	/**
	 * Constructor.
	 *
	 * @since n.e.x.t
	 */
	public function __construct() {
		$this->admin_ajax = new Admin_AJAX( 'run-check-via-check-it-button' );
	}

	/**
	 * Initializes hooks.
	 *
	 * @since n.e.x.t
	 */
	public function add_hooks() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_filter( 'plugin_action_links', array( $this, 'filter_plugin_action_links' ), 10, 2 );

		$this->admin_ajax->add_hooks();
	}

	/**
	 * Registers the admin page under the tools menu.
	 *
	 * @since n.e.x.t
	 */
	public function add_page() {
		$hook = add_management_page(
			__( 'Plugin Check', 'plugin-check' ),
			__( 'Plugin Check', 'plugin-check' ),
			'activate_plugins',
			'plugin-check',
			array( $this, 'render_page' )
		);

		add_action( "load-{$hook}", array( $this, 'initialize_page' ) );
	}

	/**
	 * Initializes page hooks.
	 *
	 * @since n.e.x.t
	 */
	public function initialize_page() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Load check's script.
	 *
	 * @since n.e.x.t
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'plugin-check-admin', WP_PLUGIN_CHECK_PLUGIN_DIR_URL . 'assets/js/plugin-check-admin.js', array(), '1.0.0', true );

		wp_add_inline_script(
			'plugin-check-admin',
			'const PLUGIN_CHECK = ' . json_encode(
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => $this->admin_ajax->get_nonce(),
				)
			),
			'before'
		);
	}

	/**
	 * Returns the list of plugins.
	 *
	 * @since n.e.x.t
	 *
	 * @return array List of available plugins.
	 */
	private function get_available_plugins() {
		$available_plugins = get_plugins();

		if ( empty( $available_plugins ) ) {
			return array();
		}

		$plugin_check_base_name = plugin_basename( WP_PLUGIN_CHECK_MAIN_FILE );

		if ( isset( $available_plugins[ $plugin_check_base_name ] ) ) {
			unset( $available_plugins[ $plugin_check_base_name ] );
		}

		return $available_plugins;
	}

	/**
	 * Render the "Plugin Check" page.
	 *
	 * @since n.e.x.t
	 */
	public function render_page() {
		$available_plugins = $this->get_available_plugins();

		$selected_plugin_basename = filter_input( INPUT_GET, 'plugin', FILTER_SANITIZE_STRING );

		require WP_PLUGIN_CHECK_PLUGIN_DIR_PATH . '/templates/admin-page.php';
	}

	/**
	 * Adds "check this plugin" link in the plugins list table.
	 *
	 * @since n.e.x.t
	 *
	 * @param array  $actions     List of actions.
	 * @param string $plugin_file Plugin main file.
	 * @return array The modified list of actions.
	 */
	public function filter_plugin_action_links( $actions, $plugin_file ) {

		if ( current_user_can( 'activate_plugins' ) ) {

			$actions[] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( admin_url() . 'tools.php?page=plugin-check&plugin=' . $plugin_file ),
				esc_html__( 'Check this plugin', 'plugin-check' )
			);
		}

		return $actions;
	}
}
