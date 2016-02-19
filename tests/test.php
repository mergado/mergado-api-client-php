<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use MergadoClient\ApiClient;

$api = new ApiClient('p6vis3Z0R5jVhnHdIOkCVRjnxR8mk7Zq5nHfGQg');

//$response = $client->get('http://localhost:3002/todos',[
//	'headers' => [
//		'Auth' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbiI6IlUyRnNkR1ZrWDE4MkU3RkRaWjBLaEVLcmJKVXJHVE83TTZWNmhOZWZPekdyd0RyMzRES0NOOVc2aDVPNEI4MHRDM0VhSlhHQWdvYUp3amFjWlVIbDRBPT0iLCJpYXQiOjE0NTUwMDk2NDB9.iLV9JJfrnroLyeaZvwnvVP1NrdiY7G5R98eYkij8qdM'
//
//	]
//]);
//
//var_dump($response->getBody()->getContents());

$response = $api->users->get();

//foreach($response as $todo) {
//	var_dump($todo->description);
//}


//$client = new GuzzleHttp\Client();
//$response = $client->get('http://lab.mergado.com/api/users/', [
//	'headers' => [
//		'Authorization' => "Bearer p6vis3Z0R5jVhnHdIOkCVRjnxR8mk7Zq5nHfxGQg"
//	]
//]);

var_dump($response);
?>