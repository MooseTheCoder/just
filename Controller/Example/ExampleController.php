<?php namespace Controller\Example;

use Just\Guard;
use Just\Request;
use Just\Response;

class ExampleController
{
    public function postExample(){
        Request::requires(['name']);
        Response::json(['message'=>'Hello ' . Request::input()->name]);
    }

    public function getExample(){
        Request::requires(['name']);
        Response::json(['message'=>'Hello ' . Request::input()->name]);
    }

    public function dynamicExample(){
        Guard::protect();
        Response::json([
            'DynamicId1'=>Request::input()->dynamicId,
            'DynamicId2'=>Request::input()->dynamicId2
        ]);
    }
}