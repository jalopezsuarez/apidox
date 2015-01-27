<?php

function generatePhpBaseClasses(&$arrayFiles)
{
	$request = "<?php \r\n\r\n";
	$response = "<?php \r\n\r\n";

	$request .= "namespace Rest\WebService;\r\n\r\n";
	$response .= "namespace Rest\WebService;\r\n\r\n";

	$request .= "class Request\r\n{\r\n\r\n}\r\n";
	$response .= "class Response\r\n{\r\n\r\n}\r\n";

	$arrayFiles['Rest/Request.php'] = $request;
	$arrayFiles['Rest/Response.php'] = $response;
}

function generatePhpCode($className, $methods, &$arrayFiles)
{
	$request = "<?php \r\n\r\n";
	$response = "<?php \r\n\r\n";

	$request .= "namespace Rest\WebService;\r\n\r\n";
	$response .= "namespace Rest\WebService;\r\n\r\n";

	$request .= "use Rest\Request;\r\n\r\n";
	$response .= "use Rest\Request;\r\n";
	$response .= "use Rest\Response;\r\n\r\n";

	$request .= "class " . ucwords($className) . "Request extends Request\r\n{\r\n\r\n";
	$response .= "class " . ucwords($className) . "Response extends Response\r\n{\r\n\r\n";

	foreach ($methods as $method)
	{
		$request .= "\t// " . $method['name'] . " method\r\n";
		foreach ($method['params'] as $param)
		{
			$request .= "\tconst " . strtoupper($className) . "_REQUEST_" . strtoupper($param) . " = \"" . $param . "\";\r\n";
		}

		$request .= "\r\n";
	}

	foreach ($methods as $method)
	{
		$request .= "\tpublic function " . $method['name'] . "(\$requestParams)\r\n\t{\r\n\r\n\t}\r\n\r\n";
		$response .= "\tpublic function " . $method['name'] . "(Request \$request)\r\n\t{\r\n\r\n\t}\r\n\r\n";
	}

	$request .= "}\r\n";
	$response .= "}\r\n";

	$arrayFiles['Rest/WebService/' . ucwords($className) . 'Request.php'] = $request;
	$arrayFiles['Rest/WebService/' . ucwords($className) . 'Response.php'] = $response;
}

function generatePhpErrorClass($errors)
{
	$content = "<?php \r\n\r\n";
	$content .= "namespace Rest\WebService;\r\n\r\n";
	$content .= "class RestException extends \\Exception\r\n{\r\n";

	foreach ($errors as $key => $value)
	{
		$content .= "\t// " . $key . "\r\n";

		foreach ($value as $error)
		{
			$content .= "\tconst ERROR_" . $error['code'] . " = " . "\"" . $error['code'] . "\";\r\n";
		}

		$content .= "\r\n";
	}

	$content .= "}\r\n";

	return array('name' => 'Rest/RestException.php', 'content' => $content);
}

$path = rtrim($_GET["v"], '/') . '/';
$lang = $_GET["l"];

if (strcmp($lang, 'php') != 0)
{
	exit('Parameter "lang" is not valid.');
}

$file = tempnam("tmp", "zip");
$zip = new ZipArchive();
$zip->open($file, ZipArchive::OVERWRITE);

switch ($lang)
{
	case 'php':
		$zip->addEmptyDir("Rest");
		$zip->addEmptyDir("Rest/WebService");
		break;
}

$codeClass = array();

$scan = scandir($path);
foreach ($scan as $result)
{
	if ($result === '.' or $result === '..')
	{
		continue;
	}

	$endpoint = $path . $result;

	if (is_dir($endpoint) && (strlen($result) < 2 || $result[0] !== 'v' || $result[1] !== '#'))
	{
		$scanMethods = scandir($endpoint);
		$codeMethods = array();
		$allClassParams = array();
		foreach ($scanMethods as $method)
		{
			$methodPath = $endpoint . '/' . $method;
			if (is_file($methodPath) && pathinfo($methodPath, PATHINFO_EXTENSION) == "xml" && pathinfo($methodPath, PATHINFO_FILENAME) != "index")
			{
				$methodInfo = array('name' => pathinfo($method, PATHINFO_FILENAME));

				$methodConfig = simplexml_load_file($methodPath);
				$params = array();
				if (count($methodConfig->param) > 0)
				{
					foreach ($methodConfig->param as $param)
					{
						$name = (string) $param->attributes()['name'];
						if (array_search($name, $allClassParams) === FALSE)
						{
							$params[] = $name;
							$allClassParams[] = $name;
						}
					}
				}

				$methodInfo['params'] = $params;
				$codeMethods[] = $methodInfo;
			}
		}

		$codeClass[$result] = $codeMethods;
	}
}

if (count($codeClass) > 0)
{
	$arrayFiles = array();

	foreach ($codeClass as $className => $arrayMethods)
	{
		switch ($lang)
		{
			case 'php':
				generatePhpCode($className, $arrayMethods, $arrayFiles);
				break;
		}
	}

	switch ($lang)
	{
		case 'php':
			generatePhpBaseClasses($arrayFiles);
			break;
	}

	foreach ($arrayFiles as $fileName => $content)
	{
		$zip->addFromString($fileName, $content);
	}
}

$errorPath = $path . 'errorcodes.xml';
if (file_exists($errorPath))
{
	$errorsConfig = simplexml_load_file($errorPath);

	$errors = array();
	if (count($errorsConfig->error) > 0)
	{
		$uncategorized = array();

		foreach ($errorsConfig->error as $error)
		{
			$error = array('code' => $error->attributes()['code'], 'description' => $error->attributes()['description']);
			$uncategorized[] = $error;
		}

		$errors['Uncategorized'] = $uncategorized;
	}

	if (count($errorsConfig->category) > 0)
	{
		foreach ($errorsConfig->category as $category)
		{
			if (count($category->error) > 0)
			{
				$categorized = array();

				foreach ($category->error as $error)
				{
					$error = array('code' => $error->attributes()['code'], 'description' => $error->attributes()['description']);
					$categorized[] = $error;
				}

				$categoryName = (string) $category->attributes()['name'];
				$errors[$categoryName] = $categorized;
			}
		}
	}

	if (count($errors) > 0)
	{
		switch ($lang)
		{
			case 'php':
				$errorFile = generatePhpErrorClass($errors);
				break;
		}

		$zip->addFromString($errorFile['name'], $errorFile['content']);
	}
}

$zip->close();
header('Content-Type: application/zip');
header('Content-Length: ' . filesize($file));
header('Content-Disposition: attachment; filename="file.zip"');
readfile($file);
unlink($file);
