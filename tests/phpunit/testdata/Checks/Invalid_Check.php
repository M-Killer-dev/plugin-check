<?php

namespace WordPress\Plugin_Check\Test_Data;

use WordPress\Plugin_Check\Checker\Check;
use WordPress\Plugin_Check\Checker\Check_Result;
use WordPress\Plugin_Check\Checker\Stable_Check;

class Invalid_Check implements Check {

	use Stable_Check;

	public function run( Check_Result $check_result ) {
		return;
	}
}
