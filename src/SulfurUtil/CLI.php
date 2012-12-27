<?php

namespace SulfurUtil;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

class CLI extends Command
{
	protected function configure()
	{
		$this
			->setName('Sulfur:CLI')
			->setDescription('CLI for ElasticSearch in PHP')
			->addArgument(
				'name',
				InputArgument::OPTIONAL,
				'Who do you want to greet?'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		do{
			$output->write('<fg=yellow>sulfur > </fg=yellow>');
			$cmd = fgets(STDIN,1024);
			switch(trim($cmd)){
				case 'exit':
					die('you exited'.PHP_EOL);
					break;
				case 'edit':
					$a = `echo "# Enter your command:\n" | vipe | tee`;
					$output->write($a);
					break;
				default:
					$output->write('You said: '.$cmd);
					break;
			}
		} while(true);
	}
}
