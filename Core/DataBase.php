<?php

namespace Core;

use PDO;
use PDOException;

class DataBase {
    private static $instance;
    private $connection;

    public static function getInstance(): DataBase {
        if(!self::$instance) {
            self::$instance = new DataBase();
            self::$instance->connect();
        }
        return self::$instance;
    }

    private function connect(): void {
        global $config;
        $db = isset($config['db']) ? $config['db'] : null;
        $this->connection = new PDO("{$db['driver']}:host={$db['host']};dbname={$db['dbname']};charset=utf8", $db['user'], $db['pass'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function dispatch(string $sql, array $data = null): ?array {
        $statement = $this->connection->prepare($sql);
        $statement->execute($data);

        if(explode(" ", $sql)[0] == 'SELECT') {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function getList(string $table, string $fields, array $condition = null, string $filter = null, string $order = null, string $limit = null): ?array {
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

    public function insert(string $table, array $data): bool {
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

    public function update(string $table, array $data, array $condition): bool {
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

    public function delete(string $table, array $condition): bool {
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
