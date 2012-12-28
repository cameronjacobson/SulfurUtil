<?php

namespace SulfurUtil\Interfaces;

interface CommandInterface
{
	public function __construct($arguments,$context);
	public function isValid();
	public function getHelp();
	public function execute();
}
