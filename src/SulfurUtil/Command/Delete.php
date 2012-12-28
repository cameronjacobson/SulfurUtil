<?php

namespace SulfurUtil\Command;

use SulfurUtil\Interfaces\CommandInterface;

class Delete implements CommandInterface
{
	public function __construct($arguments, $context){
		$this->arguments = $arguments;
		$this->context = &$context;
	}

    public function execute(){
        switch(count($this->arguments)){
            case 1:
                unset($this->context->request[$this->arguments[0]]);
				return 'OK';
                break;
            case 2:
                unset($this->context->query[$this->arguments[1]]);
				return 'OK';
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

    public function getHelp(){
        return 'Usage: delete [query] {key}';
    }
}
