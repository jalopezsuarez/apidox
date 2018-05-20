<?php
$get = $_POST['get'];
$post = $_POST['post'];
$type = $_POST['type'];
$params = $_POST['params'];

$cURL = curl_init();

if (strcasecmp(strtolower($type), "get") === 0)
{
	curl_setopt($cURL, CURLOPT_URL, $get);
	curl_setopt($cURL, CURLOPT_HTTPGET, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
}
else
{
	curl_setopt($cURL, CURLOPT_URL, $post);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($params)));
}

$result = curl_exec($cURL);
curl_close($cURL);
print_r(trim($result));

?>