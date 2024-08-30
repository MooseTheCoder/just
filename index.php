<?php

require_once __DIR__ . '/Just/Autoloader.php';

$api = new Just\Api();

$api->post('/example/post', 'Controller\Example\ExampleController@postExample');
$api->get('/example/get', 'Controller\Example\ExampleController@getExample');
$api->get('/example/{dynamicId}/get/{dynamicId2}', 'Controller\Example\ExampleController@dynamicExample');

$api->get('/user/{userId}/get/{userId2}', 'Controller\IndexController@index');

$api->run();