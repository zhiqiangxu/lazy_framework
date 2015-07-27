<?php
namespace Lazy\DB\Table;

class ConnectionPool {
    private $conns = array();
    public function __construct($array)
    {
        foreach ($array as $db_info) {
            $this->conns[] = new Connection($db_info);
        }
    }

    public function getConn() {
        $target = mt_rand(0, count($this->conns) - 1);
        return $this->conns[$target];
    }
}

