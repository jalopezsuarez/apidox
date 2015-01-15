<?php
$url = $_POST ['rq'];
$cURL = curl_init();

curl_setopt($cURL, CURLOPT_URL, $url);
curl_setopt($cURL, CURLOPT_HTTPPOST, true);
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURL, CURLOPT_HTTPHEADER, array ('Content-Type: application/json','Accept: application/json' ));

$result = curl_exec($cURL);

curl_close($cURL);

print_r(trim($result));
?>