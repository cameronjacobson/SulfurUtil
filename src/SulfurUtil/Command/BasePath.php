<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class BasePath implements CommandInterface
{
	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = &$context;
	}

	public function execute(){
		switch(count($this->arguments)){
			case 0:
				return $this->context->basepath;
				break;
			case 1:
				$this->context->basepath = strpos($this->arguments[0],'/') === 0 
					? $this->arguments[0]
					: SULFUR_UTIL_DIR.'/'.$this->arguments[0];
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

	public function getHelp(){
		return 'Usage: basepath [value]';
	}
}
