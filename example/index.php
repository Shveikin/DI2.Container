<?php

require '../vendor/autoload.php';

use DI2\Container;
use DI2\MP;

class Test {
	use Container;

	private $alert = '';

	function __construct($super, $alert, $hh) {
		echo "alert: $alert, hh: $hh;\n";

		$this->alert = $alert;
	}

	private function print($text) {
		echo "$this->alert: $text\n";
	}
}


echo MP::GET(
	class: Test::class, 
	alias: 'HEROS', 
	constructor: ['hh' => 'dss', 'alert' => 'method']
)->print('hello world');

echo Test::print('hello world');
