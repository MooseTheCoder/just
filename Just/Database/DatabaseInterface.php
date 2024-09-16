<?php namespace Just\Database;

interface DatabaseInterface {

    public function __construct($connectionDetails);

    public function insert($table, $data);

    public function update($table, $data, $where);

    public function delete($table, $where);

    public function select($table, $where);

    public function query($query);

}