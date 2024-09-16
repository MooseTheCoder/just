<?php namespace Just\Database;

use Just\Response;

class Sqlite implements DatabaseInterface {

    private $connection;

    public function __construct($connectionDetails) {
        try{
            $this->connection = new \SQLite3($connectionDetails['PATH']);
            $this->connection->enableExceptions(true);
        }catch(\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function insert($table, $data) {
        try{
            $columns = implode(', ', array_keys($data));
            $values = implode(', :', array_keys($data));
            $stmt = $this->connection->prepare("INSERT INTO $table ($columns) VALUES (:$values)");
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function update($table, $data, $where = []) {
        try{
            $setValues = [];
            $bindValues = [];
    
            foreach ($data as $column => $value) {
                $setValues[] = "$column = :$column";
                $bindValues[":$column"] = $value;
            }    
            $setString = implode(', ', $setValues);

            $whereValues = [];
            if(!empty($where)){
                foreach ($where as $column => $value) {
                    $whereValues[] = "$column = :where$column";
                    $bindValues[":where$column"] = $value;
                }
                $whereString = implode(' AND ', $whereValues);
            }
            $stmt = $this->connection->prepare("UPDATE $table SET $setString" . (empty($where) ? '' : " WHERE $whereString"));
            foreach ($bindValues as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function delete($table, $where) {
        try{
            $whereValues = [];
            foreach ($where as $column => $value) {
                $whereValues[] = "$column = :where$column";
                $bindValues[":where$column"] = $value;
            }
            $whereString = implode(' AND ', $whereValues);
    
            $stmt = $this->connection->prepare("DELETE FROM $table" . (empty($where) ? '' : " WHERE $whereString"));
    
            foreach ($bindValues as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }

    }

    public function select($table, $where) {
        try{
            $whereValues = [];
            $bindValues = [];
            if(!empty($where)){
                foreach ($where as $column => $value) {
                    $whereValues[] = "$column = :$column";
                    $bindValues[":$column"] = $value;
                }
                $whereString = implode(' AND ', $whereValues);
            }
    
            $stmt = $this->connection->prepare("SELECT * FROM $table" . (empty($where) ? '' : " WHERE $whereString"));
    
            foreach ($bindValues as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $result = $stmt->execute();

            while($row = $result->fetchArray(SQLITE3_ASSOC)){
                $rows[] = $row;
            }
    
            if(count($rows) === 1){
                return $rows[0];
            }

            return $rows;
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function query($query) {
        try{
            return $this->connection->exec($query);
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

}