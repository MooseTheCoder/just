<?php namespace Just\Database;

class Sqlite implements DatabaseInterface {

    private $connection;

    public function __construct($connectionDetails) {
        $this->connection = new \SQLite3($connectionDetails['PATH']);
    }

    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $values = implode("','", array_values($data));
        $this->connection->query("INSERT INTO $table ($columns) VALUES ('$values')");
    }

    public function update($table, $data, $where) {
        $set = '';
        foreach($data as $key => $value) {
            $set .= "$key = '$value',";
        }
        $set = rtrim($set, ',');
        $this->connection->query("UPDATE $table SET $set WHERE $where");
    }

    public function delete($table, $where) {
        $this->connection->query("DELETE FROM $table WHERE $where");
    }

    public function select($table, $columns, $where) {
        $columns = implode(',', $columns);
        $this->connection->query("SELECT $columns FROM $table WHERE $where");
    }

    public function count($table, $where) {
        $this->connection->query("SELECT COUNT(*) FROM $table WHERE $where");
    }

    public function query($query) {
        $this->connection->query($query);
    }

    public function getById($table, $id) {
        $this->connection->query("SELECT * FROM $table WHERE id = $id");
    }

    public function updateById($table, $data, $id) {
        $set = '';
        foreach($data as $key => $value) {
            $set .= "$key = '$value',";
        }
        $set = rtrim($set, ',');
        $this->connection->query("UPDATE $table SET $set WHERE id = $id");
    }

    public function deleteById($table, $id) {
        $this->connection->query("DELETE FROM $table WHERE id = $id");
    }

}