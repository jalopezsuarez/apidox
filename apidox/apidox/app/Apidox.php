<?php

namespace Application;

class Apidox
{
	const CONFIG_XML = "config.xml";
	const INDEX_XML = "index.xml";
	const ERRORS_XML = "errors.xml";
	const TITLE = "title";
	const URI = "uri";
	const SCHEME = "scheme";
	const ORDER = "order";
	const CATEGORY = "category";
	const CATEGORY_NONE = "Uncategorized";
	const NAME = "name";
	const ERRORS = "errors";
	const ERROR = "error";
	const CODE = "code";
	const METHODS = "methods";
	const COUNTER = "counter";
	const METHOD = "method";
	const TYPE = "type";
	const VERSION = "version";
	const DESCRIPTION = "description";
	const DEPRECATED = "deprecated";
	const HIDDEN = "hidden";
	const PARAMS = "params";
	const PARAM = "param";
	const REQUIRED = "required";
	const TYPE_BOOLEAN = "boolean";
	const TYPE_ARRAY = "array";
	const TYPE_FILE = "file";
	const TYPE_ENUMERATED = "enumerated";
	const OPTION = "option";
	const VALUE = "value";
	const EXAMPLE = "example";
	const INFORMATION = "information";
	const PATH = "path";
	const INDEX = "index";
	const XML = "xml";
	protected $title;
	protected $version;
	protected $uri;
	protected $scheme;
	protected $counter;
	protected $endpoints;
	protected $errors;

	public function __construct()
	{
		$title = "";
		$version = "";
		$uri = "";
		$scheme = "";
		
		$counter = 0;
		
		$endpoints = array();
		$errors = array();
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function setVersion($version)
	{
		$this->version = $version;
	}

	public function getUri()
	{
		return $this->uri;
	}

	public function setUri($uri)
	{
		$this->uri = $uri;
	}

	public function getScheme()
	{
		return $this->scheme;
	}

	public function setScheme($scheme)
	{
		$this->scheme = $scheme;
	}

	public function getCounter()
	{
		return $this->counter;
	}

	public function setCounter($counter)
	{
		$this->counter = $counter;
	}

	public function getEndpoints()
	{
		return $this->endpoints;
	}

	public function setEndpoints($endpoints)
	{
		$this->endpoints = $endpoints;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function setErrors($errors)
	{
		$this->errors = $errors;
	}
}
