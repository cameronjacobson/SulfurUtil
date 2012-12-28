<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class NewCmd implements CommandInterface
{
	protected $validCommands = ['context','query'];

	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = &$context;
	}

	public function execute(){
		switch($this->what){
			case 'context':
				$this->context->clear();
				break;
			case 'query':
				$this->context->query = [];
				break;
		}
	}

	public function isValid(){
		if(in_array($this->what = array_shift($this->arguments),$this->validCommands)){
			return true;
		}
		return false;
	}

    public function getDescription(){
        return 'command description here';
    }

	public function getHelp(){
		return 'new [context|query]';
	}
}
