<?php

namespace Application;

require_once realpath(dirname(__FILE__) . '/XMLParser.php');
require_once realpath(dirname(__FILE__) . '/Annotations.php');
require_once realpath(dirname(__FILE__) . '/Apidox.php');
use Application\Annotations;
use Application\XMLParser;
use Application\Apidox;

class Controller
{

	public function __construct()
	{
	}

	public function parse($dirname)
	{
		$xmlParser = new XMLParser();
		$annotations = new Annotations();
		
		$apidox = new Apidox();
		$xmlParser->parse($apidox, $dirname);
		$annotations->parse($apidox, $dirname);
		
		return $apidox;
	}
}