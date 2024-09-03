<?php namespace Just;

class Environment {

    private $vars = [];

    public function __construct(){
        if (!file_exists(__DIR__ . '/../.env')) {
            return;
        }
        $fileEnv = file_get_contents(__DIR__ . '/../.env');
        $this->vars = parse_ini_string($fileEnv, true);
    }

    public function get ($key) {
        if (!isset($this->vars[$key])) {
            return null;
        }
        return $this->vars[$key];
    }

}