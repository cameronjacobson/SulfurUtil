<?php

namespace SulfurUtil;

use \SulfurUtil\CLI;
use \Symfony\Component\Console\Application;
use \Symfony\Component\Console\Input\ArgvInput;
use \Symfony\Component\Console\Output\ConsoleOutput;

class SulfurUtil
{
	public function __construct($argv){
		$input = new ArgvInput($argv);
		$output = new ConsoleOutput();

		$application = new Application();
		$application->add(new CLI);
		$application->run($input, $output);
	}
}
