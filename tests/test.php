<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use MergadoClient\UrlBuilder;

$provider = new \MergadoClient\OAuth2\MergadoProvider([
    'clientId' => 'testclient',    // The client ID assigned to you by the provider
    'clientSecret' => 'secret',   // The client password assigned to you by the provider
    'redirectUri' => 'http://localhost/logbook/oauth2'
]);

$token = $provider->getAccessToken('authorization_code', [
    'code' => "asadouadadw"
]);

//$api = new ApiClient('n0iPcGlo0jFUiq36JJo0TMbdV3yEfbiMgBNSrX0c');
//
////$response = $client->get('http://localhost:3002/todos',[
////	'headers' => [
////		'Auth' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbiI6IlUyRnNkR1ZrWDE4MkU3RkRaWjBLaEVLcmJKVXJHVE83TTZWNmhOZWZPekdyd0RyMzRES0NOOVc2aDVPNEI4MHRDM0VhSlhHQWdvYUp3amFjWlVIbDRBPT0iLCJpYXQiOjE0NTUwMDk2NDB9.iLV9JJfrnroLyeaZvwnvVP1NrdiY7G5R98eYkij8qdM'
////
////	]
////]);
////
////var_dump($response->getBody()->getContents());
//
//$response = $api->users->get();
//
////foreach($response as $todo) {
////	var_dump($todo->description);
////}
//
//
////$client = new GuzzleHttp\Client();
////$response = $client->get('http://lab.mergado.com/api/users/', [
////	'headers' => [
////		'Authorization' => "Bearer p6vis3Z0R5jVhnHdIOkCVRjnxR8mk7Zq5nHfxGQg"
////	]
////]);
//
//var_dump($response);

//
//$client = new \MergadoClient\ApiClient("jAEEccZ4Nc6p7szbZq2InY2O8w9n6EvCddP4xK9Q", "dev");
//$newRule = [
//    'name' => 'vytvorene cez api',
//    'project_element_id' => "50730182",
//    "applies" => true,
//    "deletable" => true,
//    "priority" => 4,
//    "type" => "calc",
//    "data" => [
//        "expression" => "%HEUREKA_CPC%*1.5"
//    ]
//];
////$result = $client->projects(7912)->rules->post($newRule);
//////$result = $client->something->post($newRule);
////
////var_dump($result);
////
//$stack = \GuzzleHttp\HandlerStack::create();
//$stack->setHandler(new \GuzzleHttp\Handler\CurlHandler());
////$stack->push(\MergadoClient\ApiMiddleware::auth());
//$client = new \GuzzleHttp\Client(['handler' => $stack]);
//$object = new UrlBuilder();
//
//$response = $client->request("POST" ,"http://localhost:3000/test", [
//    'headers' => [
//        'Authorization' =>  'Bearer jAEEccZ4Nc6p7szbZq2InY2O8w9n6EvCddP4xK9Q'
//    ],
//    'json' => $object,
//    'content-type' => 'application/json'
//]);
////
////$data = json_decode($response->getBody());
////var_dump($data);
//
////for ($i = 0; $i < 6; $i++){
////
////}
?>