<?php
namespace Lazy\DB\Table;

class Connection {
    private $db_info;
    private $pdo = null;

    public function __construct($array)
    {
        $this->db_info = $array;
    }

    public function join() {
        if (!$this->pdo) {
            $db_url = "mysql:host={$this->db_info['host']};dbname={$this->db_info['name']};charset={$this->db_info['encode']}";
            $this->pdo = new \PDO($db_url, $this->db_info['user'], $this->db_info['pass']/*, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$db_info['encode']}")*/);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        }
    }

    public function close() {
        $this->pdo = null;
    }

    public function startTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollBack() {
        return $this->pdo->rollBack();
    }

    public function execute($sql, $bindings = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindings);
    }

    public function query($sql, $bindings = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}
