<?php


class DB
{
    var $pdo = NULL;

    function __construct($db_url, $db_user, $db_pass)
    {
        $this->pdo = new PDO(
                $db_url,
                $db_user,
                $db_pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    }

    function execute($sql, $bindings = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindings);
    }

    function query($sql, $bindings = NULL)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

    function last_insert_id()
    {
        return $this->pdo->lastInsertId();
    }
}


