<?php namespace Just;

class Request {
    
    /**
     * Get the current URI
     * 
     * @return string The current URI
     */
    public static function uri(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $uri;
    }
    
    /**
     * Get the current request method
     * 
     * @return string The current request method
     */
    public static function method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get all user input.
     * 
     * @param  boolean $assoc Return the input as an associative array.
     * @return array The user input.
     */

    /** @disregard P1006 Function can return an object or an array. */
    public static function input($assoc=false){
        // Get the input from the standard sources
        $inputArray = array_merge($_GET, $_POST);

        // If the request body is not empty, parse it as JSON and add it to the input array
        $phpInput = file_get_contents('php://input');
        if(!empty($phpInput)){
            $inputParsed = json_decode($phpInput, true);
            if(gettype($inputParsed) === 'array'){
                $inputArray = array_merge($inputArray, $inputParsed);
            }
        }

        // If dynamic URL parameters are set, add them to the input array
        if(isset($GLOBALS['URL_PARAMS']) && !empty($GLOBALS['URL_PARAMS'])){
            $inputArray = array_merge($GLOBALS['URL_PARAMS'], $inputArray);
        }

        // Return the input array
        return json_decode(json_encode($inputArray), $assoc);
    }

    /**
     * Check if the input contains all required parameters
     * 
     * @param array $required The required parameters
     * @return boolean True if all required parameters are set, false otherwise
     */
    public static function requires($required){
        $input = self::input(true);
        foreach($required as $key){
            if(!isset($input[$key])){
                // If the parameter is not set, return a 400 error with an appropriate message
                Response::json(['error'=>'Missing required parameter ' . $key], 400);
            }
        }
        // If all required parameters are set, return true
        return true;
    }
}