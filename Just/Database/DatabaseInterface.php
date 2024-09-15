<?php namespace Just\Database;

interface DatabaseInterface {

    public function __construct($connectionDetails);

    public function insert($table, $data);

    public function update($table, $data, $where);

    public function delete($table, $where);

    public function select($table, $columns, $where);

    public function count($table, $where);

    public function query($query);

    public function getById($table, $id);

    public function updateById($table, $data, $id);

    public function deleteById($table, $id);

}