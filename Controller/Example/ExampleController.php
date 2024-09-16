<?php namespace Controller\Example;

use Just\Guard;
use Just\Request;
use Just\Response;
use Just\Database;

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

    public function databaseExample(){
        Guard::protect();
        $db = new Database();

        $db->insert('example', [
            'name'=>'Just\Database',
            'my_field'=>'Hello World!',
            'date'=>date('Y-m-d H:i:s')
        ]);

        $db->update('example', ['name'=>'Updated Just!'], [
            'name'=>'Just\Database',
            'my_field'=>'Hello World!'
        ]);

        $returnedRows = $db->select('example', [
            'name'=>'Updated Just!'
        ]);

        $db->delete('example', [
            'my_field'=>'Hello World!'
        ]);

        Response::json($returnedRows);

    }
}