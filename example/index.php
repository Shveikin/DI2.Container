<?php

use clases\Init;
use DI2\Container;

require '../vendor/autoload.php';

class site {
	use Init;

	function next(){
		echo $this->jsonFilter->print('HELLO');
	}

}


(new site())->next();