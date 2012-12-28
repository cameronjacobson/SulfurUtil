<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class Parse implements CommandInterface
{
	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = $context;
	}

	public function execute(){
		return 'this is class: '.__CLASS__;
	}

	public function isValid(){
		return true;
	}

    public function getDescription(){
        return 'command description here';
    }

	public function getHelp(){
		return 'this is help';
	}
}
