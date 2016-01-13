<?php

$req = [
	__DIR__ . '/client/Object.php',
	__DIR__ . '/client/Client.php',
	__DIR__ . '/client/Request.php',
	__DIR__ . '/client/CurlHandler.php',
	__DIR__ . '/client/exceptions.php',
];

array_map(function($v) {
	require $v;
}, $req);
