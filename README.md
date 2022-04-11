## php di 2


`
	class Test {
		use Container;

		private function print($text){
			echo "$text\n";
		}
	}


	echo Test::print('hello world');
`
