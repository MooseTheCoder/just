<?php namespace Controller;
use Just\Response;
class JustController{

    public function document(){
        global $api;
        $routes = $api->getRoutes();

        foreach($routes as $methodKey=>$method){
            foreach($method as $route=>$routeData){
                $routeMethodParts = explode('@', $routeData['function']);
                $routeClass = $routeMethodParts[0];
                $routeMethod = $routeMethodParts[1];

                $reflection = new \ReflectionClass(new $routeClass);
                $reflectionMethod = $reflection->getMethod($routeMethod);

                if($route !== '/docs'){
                    $routeMethodStartLine = $reflectionMethod->getStartLine();
                    $routeMethodEndLine = $reflectionMethod->getEndLine();
                    $routeClassFilename = $reflection->getFileName();
                    
                    $fileContents = file($routeClassFilename);

                    $routeMethodCode = array_slice($fileContents, $routeMethodStartLine-1, $routeMethodEndLine-$routeMethodStartLine+1);
                    $routeMethodCode = implode("\n", $routeMethodCode);

                    if(preg_match_all('/Request::requires\((.*)\);/', $routeMethodCode, $matches)){
                        $requestParams = [];
                        foreach($matches[1] as $x){
                            $x = str_replace(['[',']','\''], '', $x);
                            $requestParams[] = $x;   
                        }
                        $routes[$methodKey][$route]['params'] = $requestParams;
                    }
                }
            }
        }
        Response::json($routes);
    }

}