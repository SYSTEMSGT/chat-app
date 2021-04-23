<?php

namespace Classes;

class DB {

    private $connection;

    public function __construct() {

        $config = \Helpers\Config::get('database');
        
        $dsn = sprintf('%s:dbname=%s;host=%s', $config['database_driver'], $config['database_name'], $config['database_host']);
        $options = [
            \PDO::ATTR_EMULATE_PREPARES => FALSE, 
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, 
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];
        try {
            $pdo = new \PDO($dsn, $config['database_username'], $config['database_password'], $options);
            $this->connection = $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function __destruct(){
        $this->connection = null;
    }

    public function query(string $query, array $data) {
        try {
            $stm = $this->connection->prepare($query);
            $stm->execute($data);
            return $stm->fetch(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function selectOneColumn(string $query, array $data) {
        try {
            $stm = $this->connection->prepare($query);
            $stm->execute($data);
            return $stm->fetch();
        } catch(\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function insert(string $query, array $data) {
        try {
            $stm = $this->connection->prepare($query);
            $stm->execute($data);
            return $this->connection->lastInsertId();
        } catch(\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}