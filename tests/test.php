<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use MergadoClient\Api;

$api = new Api();
$client = new \GuzzleHttp\Client();

//$response = $client->get('http://localhost:3002/todos',[
//	'headers' => [
//		'Auth' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbiI6IlUyRnNkR1ZrWDE4MkU3RkRaWjBLaEVLcmJKVXJHVE83TTZWNmhOZWZPekdyd0RyMzRES0NOOVc2aDVPNEI4MHRDM0VhSlhHQWdvYUp3amFjWlVIbDRBPT0iLCJpYXQiOjE0NTUwMDk2NDB9.iLV9JJfrnroLyeaZvwnvVP1NrdiY7G5R98eYkij8qdM'
//
//	]
//]);
//
//var_dump($response->getBody()->getContents());

$api->todos->post([
	'description' => "Walk the new dogs ",
	'completed' => true
]);

$response = $api->todos->get();

//foreach($response as $todo) {
//	var_dump($todo->description);
//}
var_dump($response);