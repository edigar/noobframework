<?php

namespace Core;

use PDO;
use PDOException;

class DataBase {

    /** @var DataBase */
    private static $instance;

    /** @var PDO|null */
    private $connection;

    /**
     * Doesn't allow multiple instances (final)
     */
    final private function __construct() {}

    /**
     * Doesn't allow cloning of instances
     */
    private function __clone() {}

    /**
     * Get an instance of DataBase (Singleton)
     * 
     * @return self
     */
    public static function getInstance(): DataBase {
        if(!self::$instance) {
            self::$instance = new DataBase();
            self::$instance->connect();
        }
        return self::$instance;
    }

    /**
     * Connect to database
     * 
     * @return void
     */
    private function connect(): void {
        $db = config('db');
        $this->connection = new PDO("{$db['driver']}:host={$db['host']};dbname={$db['dbname']};charset=utf8", $db['user'], $db['pass'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Run query
     * 
     * @param string        $sql
     * @param array|null    $data
     * 
     * @return array|void query result
     */
    private function dispatch(string $sql, array $data = null): ?array {
        $statement = $this->connection->prepare($sql);
        $statement->execute($data);

        if(explode(" ", $sql)[0] == 'SELECT') {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return null;
    }

    /**
     * Get data from database
     * 
     * @param string        $table      query table
     * @param string        $fields     query fields
     * @param array|null    $condition  query conditions (optional)
     * @param string|null   $filter     query filter (optional)
     * @param string|null   $order      query order (optional)
     * @param string|null   $limit      query limit (optional)
     * 
     * @return array|null query result
     */
    public function getList(string $table, string $fields, array $condition = null, string $filter = null, string $order = null, string $limit = null): ?array {
        $query = "SELECT $fields FROM $table";

        if($condition != null) {
            foreach($condition as $column => $value) {
                $conditions[] = "$column = '$value'";
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

    /**
     * Insert data on database
     * 
     * @param string    $table  Table that will store data
     * @param array     $data   Data to be stored
     * 
     * @return bool query result
     */
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

    /**
     * Update data on database
     * 
     * @param string    $table      Table that will update data
     * @param array     $data       Data to be updated
     * @param array     $condition  Conditions to update register
     * 
     * @return bool query result
     */
    public function update(string $table, array $data, array $condition): bool {
        foreach($data as $column => $value) {
            $updatesColumns[] = "$column = :$column";
            $updateValues[":$column"] = $value;
        }
        $updatesColumns = implode(", ", $updatesColumns);

        foreach($condition as $column => $value) {
            $conditions[] = "$column = :$column";
            $conditions[":$column"] = $value;
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

    /**
     * Delete data on database
     * 
     * @param string    $table      Table that will delete data
     * @param array     $condition  Conditions to delete register
     * 
     * @return bool query result
     */
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
