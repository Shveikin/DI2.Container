## php di 2


`
	require '../vendor/autoload.php';
	use DI2\Container;

	class Test {
		use Container;

		private function print($text){
			echo "$text\n";
		}
	}


	echo Test::print('hello world');
`