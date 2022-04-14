<?php

require '../vendor/autoload.php';

use DI2\Container;
use DI2\MP;

class Test {
	use Container;

	private function print($text){
		echo "$text\n";
	}
}


echo MP::GET(Test::class, 'HEROS')->print('hello world');

echo Test::print('hello world');