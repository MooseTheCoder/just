<?php namespace Just;

class Database {

    private $dbClass;

    public function __construct(){
        $dbType = Environment::get('DB_TYPE');
        if($dbType === 'SQLITE'){
            $this->dbClass = new \Just\Database\Sqlite(['PATH'=>Environment::get('DB_PATH')]);
        }
    }

    public function __call($name, $arguments){
        return call_user_func_array([$this->dbClass, $name], $arguments);
    }
}