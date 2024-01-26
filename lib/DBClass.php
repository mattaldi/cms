<?php

require_once('DBSettings.php');

class DBClass
{
    private static $sharedConnection = null;
    private $connection;
    private $stmt; 

    public function __construct($connection = null)
    {
        if ($connection instanceof mysqli) {
            $this->connection = $connection;
        } elseif ($connection === null) {
            $settings = new DatabaseSettings();
            $this->connection = self::createConnection($settings);
        } else {
            throw new InvalidArgumentException("Invalid connection parameter.");
        }
    
        $this->checkConnection();
    }
    

    private static function createConnection(DatabaseSettings $settings): mysqli
    {
        $connection = new mysqli($settings->dbhost, $settings->dbusername, $settings->dbpassword, $settings->dbname);
        if ($connection->connect_error) {
            error_log("Connection failed: " . $connection->connect_error); // Log error
            self::handleError($connection->connect_error);
        }
        return $connection;
    }
    

    public static function shareConnection(mysqli $connection): void
    {
        self::$sharedConnection = $connection;
    }

    public function query(string $sql): mysqli_result
    {
        $result = $this->connection->query($sql);
        self::handleError($this->connection->error);
        return $result;
    }

    public function prepare(string $sql): self {
        $this->stmt = $this->connection->prepare($sql);
        self::handleError($this->connection->error);
        return $this;
    }
    

    public function bindParams(array $params): self
    {
        $this->stmt->bind_param(str_repeat('s', count($params)), ...$params);
        return $this;
    }

    public function execute(): mysqli_result
    {
        $this->stmt->execute();
        self::handleError($this->stmt->error);
        return $this->stmt->get_result();
    }


       

    public function beginTransaction(): void
    {
        $this->connection->begin_transaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }

    public function insert(string $table, array $data): int
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($keys) - 1) . '?';
        $sql = "INSERT INTO {$table} (" . implode(', ', $keys) . ") VALUES ({$placeholders})";
        $stmt = $this->prepare($sql);
        $this->executePrepared($stmt, $values);
        return $this->lastInsertedID();
    }
  
    public function update(string $table, array $data, array $where): int
    {
        $setPart = implode(', ', array_map(fn($key) => "{$key} = ?", array_keys($data)));
        $wherePart = implode(' AND ', array_map(fn($key) => "{$key} = ?", array_keys($where)));

        $sql = "UPDATE {$table} SET {$setPart} WHERE {$wherePart}";
        $stmt = $this->prepare($sql);
        $this->executePrepared($stmt, array_merge(array_values($data), array_values($where)));
        return $stmt->affected_rows;
    }

    public function delete(string $table, array $where): int
    {
        $wherePart = implode(' AND ', array_map(fn($key) => "{$key} = ?", array_keys($where)));
        $sql = "DELETE FROM {$table} WHERE {$wherePart}";
        $stmt = $this->prepare($sql);
        $this->executePrepared($stmt, array_values($where));
        return $stmt->affected_rows;
    }

    private function executePrepared(array $params): mysqli_result {
        $this->stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $this->stmt->execute();
        self::handleError($this->stmt->error);
        return $this->stmt->get_result();
    }
    

    public function select(string $table, array $columns, array $where = [], array $options = []): mysqli_result {
        $columnsPart = implode(', ', $columns);
        $sql = "SELECT {$columnsPart} FROM {$table}";
        
        $params = [];
        if (!empty($where)) {
            $wherePart = implode(' AND ', array_map(fn($key) => "{$key} = ?", array_keys($where)));
            $sql .= " WHERE {$wherePart}";
            $params = array_values($where);
        }
    
        if (!empty($options)) {
            if (isset($options['orderBy'])) {
                $sql .= " ORDER BY " . $options['orderBy'];
            }
            if (isset($options['limit'])) {
                $sql .= " LIMIT " . $options['limit'];
            }
        }
    
        $this->prepare($sql);
        if (!empty($params)) {
            $this->bindParams($params);
        }
        return $this->execute();
    }

    public function fetchArray(mysqli_result $result): ?array {
        return $result->fetch_assoc();
    }

    public function fetchAll(mysqli_result $result): array {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function checkConnection(): void
    {
        if (!$this->connection) {
            throw new DatabaseConnectionException("No valid connection provided.");
        }
    }
    
    private static function handleError($error): void
    {
        if ($error) {
            throw new DatabaseException("Database error: " . $error);
        }
    }
}