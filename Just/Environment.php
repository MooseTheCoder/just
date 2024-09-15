<?php namespace Just;

class Environment {
    public static function get ($key) {
        if (!file_exists(__DIR__ . '/../.env')) {
            return null;
        }
        $fileEnv = file_get_contents(__DIR__ . '/../.env');
        $vars = parse_ini_string($fileEnv, true);

        if (!isset($vars[$key])) {
            return null;
        }
        return $vars[$key];
    }

}