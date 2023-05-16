<?php
/**
 * Tests for the Plugin_Main class.
 *
 * @package plugin-check
 */

use WordPress\Plugin_Check\Plugin_Context;
use WordPress\Plugin_Check\Plugin_Main;

class Plugin_Main_Tests extends WP_UnitTestCase {
	public function set_up() {
		parent::set_up();

		$this->plugin_main = new Plugin_Main( WP_PLUGIN_CHECK_MAIN_FILE );
	}

	public function test_context() {
		$this->assertInstanceOf( Plugin_Context::class, $this->plugin_main->context() );
	}
}
