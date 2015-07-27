<?php
namespace Lazy\DB\Redis;

class Connection {
    private $db_info;
    private $conn = null;

    public function __construct($array)
    {
        $this->db_info = $array;
    }

    public function join() {
        if (!$this->conn) {
            $host = $this->db_info['host'];
            $port = $this->db_info['port'];

            $redis = new Redis();
            $redis->connect($host, $port);
            $this->conn = $redis;
        }
    }

}

