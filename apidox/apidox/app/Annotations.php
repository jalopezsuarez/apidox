<?php

namespace Application;

require_once realpath(dirname(__FILE__) . '/Apidox.php');
use Application\Apidox;

class Annotations
{

	public function __construct()
	{
	}

	public function parse(&$apidox, $dirname)
	{
		if (is_null($apidox) || !$apidox instanceof Apidox)
		{
			new \Exception();
		}
		if (is_null($dirname) || !is_string($dirname) || !is_dir($dirname))
		{
			new \Exception();
		}
		
		// =======================================================
		// -------------------------------------------------------
		$test = "test";
		// -------------------------------------------------------
		// =======================================================
	}
}
