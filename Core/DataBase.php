<?php

namespace Core;

use PDO;
use PDOException;

class DataBase {
    private static $instance;
    private $connection;

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new DataBase();
            self::$instance->connect();
        }
        return self::$instance;
    }

    private function connect() {
        global $db;
        $this->connection = new PDO("{$db['driver']}:host={$db['host']};dbname={$db['dbname']};charset=utf8", $db['user'], $db['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function dispatch($sql, $data = null) {
        $statement = $this->connection->prepare($sql);
        $statement->execute($data);

        if(explode(" ", $sql)[0] == 'SELECT') {
            $teste = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $teste;
        }
    }

    public function getList($table, $fields, $condition = null, $filter = null, $order = null, $limit = null) {
        $query = "SELECT $fields FROM $table";

        if($condition != null) {
            foreach($condition as $column => $value) {
                $conditions[] = "$column = $value";
            }
            $conditions = implode(' AND ', $conditions);    
        }

        if(!empty($condition)) {
            $query .= " WHERE $conditions";
        }
        if(!empty($filter)) {
            $query .= " LIKE '$filter'";
        }
        if(!empty($order)) {
            $query .= " ORDER BY $order";
        }
        if(!empty($limit)) {
            $query .= " LIMIT $limit";
        }
        return $this->dispatch($query);
    }

    public function insert($table, $data) {
        foreach($data as $column => $value) {
            $columns[] = $column;
            $holders[] = "?";
            $values[] = $value;
        }
        $columns = implode(", ", $columns);
        $holders = implode(", ", $holders);
        $query = "INSERT INTO $table ($columns) VALUES ($holders)";
        try {
            $this->dispatch($query, $values);
        } catch(PDOException $e) {
            return false;
        }
        return true;
    }

    public function update($table, $data, $condition) {
        foreach($data as $column => $value) {
            $updatesColumns[] = "$column = :$column";
            $updateValues[":$column"] = $value;
        }
        $updatesColumns = implode(", ", $updatesColumns);

        foreach($condition as $column => $value) {
            $conditions[] = "$column = :$column";
        }
        $conditions = implode(' AND ', $conditions);

        $query = "UPDATE $table SET $updatesColumns WHERE $conditions";
        try {
            $this->dispatch($query, $updateValues);
        } catch(PDOException $e) {
            return false;
        }
        return true;
    }

    public function delete($table, $condition) {
        foreach($condition as $column => $value) {
            $conditions[] = "$column = $value";
        }
        $conditions = implode(' AND ', $conditions);
        $query = "DELETE FROM $table WHERE $conditions";
        try {
            $this->dispatch($query);
        } catch(PDOException $e) {
            return false;
        }
        return true;
    }
}
