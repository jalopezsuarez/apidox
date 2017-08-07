<?php
if (!function_exists('getallheaders'))
{
	function getallheaders() {
		$headers = array ();
		foreach ($_SERVER as $name => $value)
			if (substr($name, 0, 5) == 'HTTP_')
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
		return $headers;
	}
}

$get = $_POST['get'];
$post = $_POST['post'];
$type = $_POST['type'];
$headers = json_decode($_POST['headers']);
$params = $_POST['params'];

$headerOpts = array();

foreach ( $headers as $key => $val)
	array_push($headerOpts, "$key: $val");
array_merge($headerOpts, array('Content-Type: application/json'));

$cURL = curl_init();

if (strcasecmp(strtolower($type), "get") === 0)
{
	curl_setopt($cURL, CURLOPT_URL, $get);
	curl_setopt($cURL, CURLOPT_HTTPGET, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	array_merge($headerOpts, array('Accept: application/json'));
	curl_setopt($cURL, CURLOPT_HTTPHEADER, $headerOpts);
}
else
{
	curl_setopt($cURL, CURLOPT_URL, $post);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	array_merge($headerOpts, array('Content-Length: ' . strlen($params)));
	curl_setopt($cURL, CURLOPT_HTTPHEADER, $headerOpts);
}

$result = curl_exec($cURL);
curl_close($cURL);
print_r(trim($result));

?>
