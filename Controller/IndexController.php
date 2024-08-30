<?php namespace Controller;
use Just\Response;
class IndexController{

    public function index(){

        return Response::json([
            'JustApi'=>'Lightweight API Framework',
        ]);
    }

}