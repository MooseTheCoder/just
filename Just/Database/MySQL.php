<?php namespace Just\Database;

use Just\Response;

class MySQL implements DatabaseInterface {

    private $connection;

    public function __construct($connectionDetails) {
        try{
            $this->connection = new \mysqli(
                $connectionDetails['HOST'],
                $connectionDetails['USER'],
                $connectionDetails['PASS'],
                $connectionDetails['DB']
            );
            if ($this->connection->connect_error) {
                throw new \Exception($this->connection->connect_error);
            }
        }catch(\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function insert($table, $data) {
        try{
            $columns = implode(', ', array_keys($data));

            $values = str_repeat('?,', count(array_keys($data)));
            $values = rtrim($values, ',');
            
            $stmt = $this->connection->prepare("INSERT INTO $table ($columns) VALUES ($values)");
            $parameters = [];
            foreach ($data as $key=>$value) {
                $parameters[] = $value;
            }
            $stmt->bind_param(str_repeat('s', count($parameters)), ...$parameters);
            $stmt->execute();
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function update($table, $data, $where = []) {
        try{
            $setValues = [];
            $parameters = [];
    
            foreach ($data as $column => $value) {
                $setValues[] = "$column = ?";
                $parameters[] = $value;
            }    
            $setString = implode(', ', $setValues);

            if(!empty($where)){
                foreach ($where as $column => $value) {
                    $whereValues[] = "$column = ?";
                    $parameters[] = $value;
                }
                $whereString = implode(' AND ', $whereValues);
            }
            
            $stmt = $this->connection->prepare("UPDATE $table SET $setString"  . (empty($where) ? '' : " WHERE $whereString"));
            $stmt->bind_param(str_repeat('s', count($parameters)), ...$parameters);
            $stmt->execute();

        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

    public function delete($table, $where) {
        try{
            $whereValues = [];
            $parameters = [];
            foreach ($where as $column => $value) {
                $whereValues[] = "$column = ?";
                $parameters[] = $value;
            }
            $whereString = implode(' AND ', $whereValues);
    
            $stmt = $this->connection->prepare("DELETE FROM $table" . (empty($where) ? '' : " WHERE $whereString"));
            $stmt->bind_param(str_repeat('s', count($parameters)), ...$parameters);
            $stmt->execute();
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }

    }

    public function select($table, $where) {
        try{
            $whereValues = [];
            $parameters = [];
            if(!empty($where)){
                foreach ($where as $column => $value) {
                    $whereValues[] = "$column = ?";
                    $parameters[] = $value;
                }
                $whereString = implode(' AND ', $whereValues);
            }
    
            $stmt = $this->connection->prepare("SELECT * FROM $table" . (empty($where) ? '' : " WHERE $whereString"));

            if(!empty($where)){
                $stmt->bind_param(str_repeat('s', count($parameters)), ...$parameters);   
            }
            $stmt->execute();
            $result = $stmt->get_result();
    
            $rows = [];
            while($row = $result->fetch_assoc()){
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
            return $this->connection->query($query);
        }catch (\Exception $e){
            Response::json(['message'=>$e->getMessage()]);
        }
    }

}
