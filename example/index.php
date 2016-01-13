<?php

require __DIR__ . "/../src/loader.php";

$api = new Mergado\ApiClient\Client;

$a = $api('v1/project');

$a->get();
// GET: appcloud.mergado.com/api/v1

$a->queries()->get();
// GET: appcloud.mergado.com/api/v1/queries

$api('v3/project')->rules->get();
// GET: appcloud.mergado.com/api/v3/project

$api('v0')->project(1)->rules(3)->get();
// GET: appcloud.mergado.com/api/v0/project/1/rules/3

$api("project/123/queries/456")->get();
// GET: appcloud.mergado.com/api/project/123/queries/456

$api("project", 123, "queries", 456)->get();
// GET: appcloud.mergado.com/api/project/123/queries/456

$api()->project(123)->queries(456)->get();
// GET: appcloud.mergado.com/api/project/123/queries/456
