<?php

namespace SulfurUtil;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;
use \Sulfur\SulfurContext;

class CLI extends Command
{
	protected function configure()
	{
		$this
			->setName('Sulfur:CLI')
			->setDescription('CLI for ElasticSearch in PHP')
			->addOption(
				'host',
				null,
				InputOption::VALUE_REQUIRED,
				'Which elasticsearch server?',
				'localhost'
			)
			->addOption(
				'port',
				'p',
				InputOption::VALUE_REQUIRED,
				'Which port number?',
				9200
			)
			->addOption(
				'mapping',
				'm',
				InputOption::VALUE_REQUIRED,
				'Which elasticsearch mapping?'
			)
			->addOption(
				'index',
				'i',
				InputOption::VALUE_REQUIRED,
				'Which elasticsearch index?'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$context = new SulfurContext();
		$context->host = $input->getOption('host');
		$context->port = $input->getOption('port');
		$context->mapping = $input->getOption('mapping');
		$context->index = $input->getOption('index');

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
