<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class Set implements CommandInterface
{
	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = &$context;
	}

	public function execute(){
		switch(count($this->arguments)){
			case 2:
				$this->context->request[$this->arguments[0]] = $this->arguments[1];
				break;
			case 3:
				$this->context->query[$this->arguments[1]] = $this->arguments[2];
				break;
			default:
				return false;
				break;
		}
	}

	public function isValid(){
		switch(count($this->arguments)){
			case 2:
				return true;
				break;
			case 3:
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
		return 'set [query] {key} {value}';
	}
}
