<?php

namespace WordPress\Plugin_Check\Test_Data;

use WordPress\Plugin_Check\Checker\Check_Result;
use WordPress\Plugin_Check\Checker\Experimental_Check;
use WordPress\Plugin_Check\Checker\Runtime_Check;

class Experimental_Runtime_Check implements Runtime_Check {

	use Experimental_Check;

	public function run( Check_Result $check_result ) {
		return;
	}
}
