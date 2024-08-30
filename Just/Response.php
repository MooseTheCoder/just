<?php namespace Just;

class Response{
    
    /**
     * Send a JSON response to the user.
     *
     * @param mixed $data The data to send to the user in the response.
     * @param int $code The HTTP status code to use for the response.
     * @return void
     */
    public static function json($data, $code = 200){
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}