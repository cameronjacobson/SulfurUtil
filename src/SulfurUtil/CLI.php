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
	protected $validOptions = ['host','port','mapping','index','debug','basepath'];
	protected $commands;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->registerCommands();

		define('DEBUG_MODE',$input->getOption('debug'));
		$context = new SulfurContext();
		if($configfile = $input->getOption('config')){
			$this->setConfigFile($configfile);
		}
		else {
			foreach($this->validOptions as $option){
				$context->$option = $input->getOption($option);
			}
		}

		do{
			$command = null;
			$output->write('<fg=yellow>sulfur >> </fg=yellow>');
			$in = fgets(STDIN,1024);
			$arguments = $this->parseCommand($in);

			switch($cmd = array_shift($arguments)){
				case 'exit':
					die('Goodbye!'.PHP_EOL);
					break;
				case 'edit':
					$a = `echo "# Enter your command:\n" | vipe | tee`;
					$output->write($a);
					break;
				case 'configfile':
					if((count($arguments) == 1) && null !== $cmd=array_shift($arguments)){
						$output->writeln($this->setConfigFile($cmd = array_shift($arguments)));
					}
					else {
						$output->writeln('Usage: configfile {filename}');
					}
					break;
				case 'commands':
					$output->write($this->showCommands());
					break;
				case '{apicall}':
					break;
				default:
					if(isset($this->commands[$cmd])){
						$command = call_user_func($this->commands[$cmd]['command'],$arguments,$context);
					}
					else{
						$output->writeln('<fg=red>Invalid Command: '.$cmd.'</fg=red>');
					}
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

	protected function setConfigFile($configfile)
	{
		$configfile = strpos($configfile,'/') === 0 ? $configfile : SULFUR_UTIL_DIR.'/'.$configfile;
		if(file_exists($configfile)){
			$options = parse_ini_file($configfile,true);
			foreach($options['SulfurUtil'] as $key=>$value){
				if(in_array($key,$this->validOptions)){
					switch($key){
						case 'basepath':
							$this->context->basepath = strpos($value,'/') === 0 ? $value : SULFUR_UTIL_DIR.'/'.$value;
							break;
						default:
							$this->context->$key = $value;
							break;
					}
				}
			}
			return '<fg=green>OK</fg=green>';
		}
		else{
			return '<fg=red>File "'.$configfile.'" does not exist</fg=red>';
		}
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
						$components[] = $tmp;
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
			return '<fg=magenta>Usage:</fg=magenta> <fg=blue>'.$command->getHelp().'</fg=blue>';
		}
	}

	protected function showCommands()
	{
		$buffer = '';
		foreach($this->commands as $name=>$info){
			$buffer .= '<fg=cyan>Name:</fg=cyan> '.$name.PHP_EOL;
			$command = call_user_func($info['command'],[],new SulfurContext());
			$buffer .= '  <fg=magenta>Usage:</fg=magenta>       '.$command->getHelp().PHP_EOL;
			$buffer .= '  <fg=magenta>Description:</fg=magenta> '.$command->getDescription().PHP_EOL;
		}
		return $buffer;
	}

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
				'basepath',
				'b',
				InputOption::VALUE_REQUIRED,
				'Where are relevant templates/etc?'
			)
			->addOption(
				'config',
				'c',
				InputOption::VALUE_REQUIRED,
				'Which config file contains all settings?'
			)
			->addOption(
				'debug',
				'd',
				InputOption::VALUE_NONE,
				'Debug Mode?'
			);
	}

	protected function registerCommands(){
		$this->commands = [
			'basepath' => [
				'command' => function ($arguments,$context) {return new Command\BasePath($arguments,$context);}
			],
			'delete' => [
				'command' => function ($arguments,$context) {return new Command\Delete($arguments,$context);}
			],
			'get' => [
				'command' => function ($arguments,$context) {return new Command\Get($arguments,$context);}
			],
			'help' => [
				'command' => function ($arguments,$context) {return new Command\Help($arguments,$context);}
			],
			'host' => [
				'command' => function ($arguments,$context) {return new Command\Host($arguments,$context);}
			],
			'index' => [
				'command' => function ($arguments,$context) {return new Command\Index($arguments,$context);}
			],
			'json' => [
				'command' => function ($arguments,$context) {return new Command\Json($arguments,$context);}
			],
			'load' => [
				'command' => function ($arguments,$context) {return new Command\Load($arguments,$context);}
			],
			'mapping' => [
				'command' => function ($arguments,$context) {return new Command\Mapping($arguments,$context);}
			],
			'new' => [
				'command' => function ($arguments,$context) {return new Command\NewCmd($arguments,$context);}
			],
			'parse' => [
				'command' => function ($arguments,$context) {return new Command\Parse($arguments,$context);}
			],
			'port' => [
				'command' => function ($arguments,$context) {return new Command\Port($arguments,$context);}
			],
			'set' => [
				'command' => function ($arguments,$context) {return new Command\Set($arguments,$context);}
			],
			'show' => [
				'command' => function ($arguments,$context) {return new Command\Show($arguments,$context);}
			]
		];
	}
}
