<?php
$url = $_POST ['url'];
$uri = $_POST ['uri'];
$type = $_POST ['type'];
$params = $_POST ['params'];

$cURL = curl_init();

if (strcmp(strtolower($type), "post") == 0)
{
	curl_setopt($cURL, CURLOPT_URL, $uri);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_HTTPHEADER, array ('Content-Type: application/json','Content-Length: ' . strlen($params)));
}
else
{
	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_HTTPGET, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_HTTPHEADER, array ('Content-Type: application/json','Accept: application/json'));
}

$result = curl_exec($cURL);
curl_close($cURL);
print_r(trim($result));
?>