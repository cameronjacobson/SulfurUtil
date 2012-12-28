<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class Get implements CommandInterface
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

	public function getHelp(){
		return 'this is help';
	}
}
