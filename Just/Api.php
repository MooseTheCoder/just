<?php namespace Just;
use Just\Request;
use Just\Response;

class Api{
    private $routes = [];
    private $notFound = [
        'error'=>'Route not found'
    ];

    /**
     * Magic method to handle dynamic calls to HTTP methods.
     *
     * @param string $name The name of the method being called.
     * @param array $args The arguments passed to the method.
     */
    public function __call($name, $args){
        $allowedMethods = ['get', 'post', 'put', 'delete', 'patch'];
        
        // Check if the method name is in the list of allowed HTTP methods
        if(!in_array($name, $allowedMethods)){
            // Respond with a 404 error if the method is not allowed
            Response::json(['just_error'=>'Function not in defined list'], 404);
            die;
        }
        
        // Add the HTTP method to the arguments
        $args[] = strtoupper($name);
        
        // Route the request with the provided arguments
        $this->route(...$args);
    }

    /**
     * Set the response for when a route is not found.
     *
     * @param array $notFound The response to send when a route is not found.
     */
    public function setNotFound($notFound){
        // Set the custom 404 response
        $this->notFound = $notFound;
    }

    /**
     * Run the API.
     */
    public function run(){
        // Check if the route is set
        if(!isset($this->routes[Request::method()])){
            // If not, return 404
            Response::json($this->notFound, 404);
        }

        // Get the current URI
        $uri = Request::uri();

        // Store the route to call
        $routeCall = '';

        // Loop through all routes
        foreach(array_keys($this->routes[Request::method()]) as $route){
            // Split the route into parts
            $routeParts = explode('/', $route);
            // Split the URI into parts
            $uriParts = explode('/', $uri);

            // Check if the route and URI have the same number of parts
            if(count($routeParts) === count($uriParts)){
                // Assume this is the right route
                $foundRoute = true;
                // Loop through all parts of the route
                foreach($routeParts as $index=>$part){
                    // Check if the part is equal to the URI part or if it starts with a { and the URI part is set
                    if($part === $uriParts[$index] || $part[0] === '{' && isset($uriParts[$index])){
                        // If so, set the vars
                        if(strlen($part) && $part[0] === '{'){
                            $partName = substr($part, 1, -1);
                            $GLOBALS['URL_PARAMS'][$partName] = $uriParts[$index];
                        }
                        // And continue to the next part
                        continue;
                    }else{
                        // If not, assume this is not the right route
                        $foundRoute = false;
                    }
                }
                // If this is the right route, store the route to call
                if($foundRoute){
                    $routeCall = $route;
                    break;
                }
            }
        }

        // If no route was found, return 404
        if(empty($routeCall)){
            Response::json($this->notFound, 404);
        }

        // Get the class and method to call
        $routeCall = explode('@', $this->routes[Request::method()][$routeCall]['function']);

        // Check if the class exists
        if(!class_exists($routeCall[0])){
            // If not, return 404
            Response::json(['error'=>'Class not found'], 404);
        }

        // Create the class
        $route = new $routeCall[0]();
        // Get all methods of the class
        $methods = get_class_methods($route);
        // Check if the method exists
        if(!in_array($routeCall[1], $methods)){
            // If not, return 404
            Response::json(['error'=>'Method not found'], 404);
        }

        // Call the method
        $route->{$routeCall[1]}();
    }


    /**
     * Add a route to the API.
     *
     * @param string $route The route path.
     * @param string $function The function to call when the route is accessed.
     * @param string $method The HTTP method to use for the route.
     * @return void
     */
    public function route($route, $function, $method='GET'){
        // Store the route with the method
        $this->routes[$method][$route] = ['function'=>$function];
    }
}