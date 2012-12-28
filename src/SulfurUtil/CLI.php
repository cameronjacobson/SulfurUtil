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
			$output->write('<fg=yellow>sulfur > </fg=yellow>');
			$cmd = fgets(STDIN,1024);
			$arguments = $this->parseCommand($cmd);
			$command = array_shift($arguments);
			$command = trim($command);
			switch($command){
				case 'exit':
					die('you exited'.PHP_EOL);
					break;
				case 'edit':
					$a = `echo "# Enter your command:\n" | vipe | tee`;
					$output->write($a);
					break;
				case 'new':// context,query
					$context = new SulfurContext();
					$output->writeln('You said: '.$command);
					break;
				case 'delete':// query
					$output->writeln('You said: '.$command);
					break;
				case 'get': // query
					$output->writeln('You said: '.$command);
					break;
				case 'set': // query
					$output->writeln('You said: '.$command);
					break;
				case 'show': // url,{apicall}
					$output->writeln('You said: '.$command);
					break;
				case 'help': // {apicall}
					$output->writeln('You said: '.$command);
					break;
				case 'load': // template
					$output->writeln('You said: '.$command);
					break;
				case 'show':// template
					$output->writeln('You said: '.$command);
					break;
				case 'parse': // template
					$output->writeln('You said: '.$command);
					break;
				case '{apicall}':
					$output->writeln('You said: '.$command);
					break;
				case 'json':
					$output->writeln('You said: '.$command);
					break;
				default:
					$output->writeln('You said: '.$command);
					break;
			}
if(DEBUG_MODE){
			$output->writeln('ARGUMENTS:');
			foreach($arguments as $v){
				$output->writeln("\t".$v);
			}
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
}
