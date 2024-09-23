<?php namespace Controller;
use Just\Response;
class JustController{

    public function document(){
        global $api;
        $routes = $api->getRoutes();
        Response::json($routes);
    }

}