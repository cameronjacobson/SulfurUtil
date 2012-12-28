<?php

namespace SulfurUtil;

use \Symfony\Component\Console\Command\Command as ConsoleCommand;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;
use \Sulfur\SulfurContext;
use \SulfurUtil\Interfaces\CommandInterface;

class CLI extends ConsoleCommand
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
			)
			->addOption(
				'debug',
				'd',
				InputOption::VALUE_NONE,
				'Debug Mode?'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		define('DEBUG_MODE',$input->getOption('debug'));
		$context = new SulfurContext();
		$context->host = $input->getOption('host');
		$context->port = $input->getOption('port');
		$context->mapping = $input->getOption('mapping');
		$context->index = $input->getOption('index');

		do{
			$command = null;
			$output->write('<fg=yellow>sulfur > </fg=yellow>');
			$in = fgets(STDIN,1024);
			$arguments = $this->parseCommand($in);
//			$cmd = array_shift($arguments);

			switch($cmd = array_shift($arguments)){
				case 'exit':
					die('you exited'.PHP_EOL);
					break;
				case 'edit':
					$a = `echo "# Enter your command:\n" | vipe | tee`;
					$output->write($a);
					break;
				case 'new':// context,query
					$command = new Command\NewCmd($arguments,$context);
					//$context = new SulfurContext();
					break;
				case 'delete':// query
					$command = new Command\Delete($arguments,$context);
					break;
				case 'get': // query
					$command = new Command\Get($arguments,$context);
					break;
				case 'set': // query
					$command = new Command\Set($arguments,$context);
					break;
				case 'show': // template,url,{apicall}
					$command = new Command\Show($arguments,$context);
					break;
				case 'help': // {apicall}
					$command = new Command\Help($arguments,$context);
					break;
				case 'load': // template
					$command = new Command\Load($arguments,$context);
					break;
				case 'parse': // template
					$command = new Command\Parse($arguments,$context);
					break;
				case 'json':
					$command = new Command\Json($arguments,$context);
					break;
				case '{apicall}':
					break;
				default:
					$output->writeln('Invalid Command: '.$cmd);
					break;
			}
			if(DEBUG_MODE){
				$output->writeln('ARGUMENTS:');
				foreach($arguments as $v){
					$output->writeln("\t".$v);
				}
			}
			if(!empty($command)){
				$output->writeln($this->processCommand($command));
			}
		} while(true);
	}
	protected function parseCommand($cmd){
		$components = [];
		$tokenized = token_get_all('<?php '.$cmd);
		foreach($tokenized as $token){
			switch(token_name($token[0])){
				case 'T_OPEN_TAG':
				case 'T_WHITESPACE':
					break;
				default:
					$tmp = trim($token[1]," \t\n\r\0\x0B");

					if(strpos($tmp,'"') === 0){
						$tmp = trim($tmp,'"');
					}
					elseif(strpos($tmp,"'") === 0){
						$tmp = trim($tmp,"'");
					}

					if(!empty($tmp)){
						$components[] = $tmp;//trim($token[1]," \t\n\r\0\x0B\"'");
					}
					break;
			}
		}
		return $components;
	}

	protected function processCommand(CommandInterface $command){
		if($command->isValid()){
			return $command->execute();
		}
		else {
			return $command->getHelp();
		}
	}
}
