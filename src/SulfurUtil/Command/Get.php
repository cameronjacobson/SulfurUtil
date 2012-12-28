<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class Get implements CommandInterface
{
	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = &$context;
	}

	public function execute(){
		switch(count($this->arguments)){
			case 1:
				return '<fg=green>'.json_encode($this->context->request[$this->arguments[0]]).'</fg=green>';
				break;
			case 2:
				return '<fg=green>'.json_encode($this->context->query[$this->arguments[1]]).'</fg=green>';
				break;
			default:
				return false;
				break;
		}
	}

	public function isValid(){
		switch(count($this->arguments)){
			case 1:
				return true;
				break;
			case 2:
				return $this->arguments[0] === 'query';
				break;
			default:
				return false;
				break;
		}
	}

    public function getDescription(){
        return 'command description here';
    }

	public function getHelp(){
		return 'get [query] {key}';
	}
}
