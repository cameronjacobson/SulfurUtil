<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class Mapping implements CommandInterface
{
	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = &$context;
	}

	public function execute(){
		switch(count($this->arguments)){
			case 0:
				return $this->context->mapping;
				break;
			case 1:
				$this->context->mapping = $this->arguments[0];
				break;
			default:
				return false;
				break;
		}
	}

	public function isValid(){
		switch(count($this->arguments)){
			case 0:
			case 1:
				return true;
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
		return 'mapping [value]';
	}
}
