<?php
namespace Lazy\DB\Memcached;

class ConnectionManager
{
    private $schema_define_list;
    private $conn_list;
    public function __construct($mem_conf) {
        $this->schema_define_list = $mem_conf;
    }

    public function getCon($schema_name) {
        if (!isset($this->conn_list[$schema_name])) {
            $define_list = $this->schema_define_list[$schema_name];
            assert($define_list, "Missing memecached schema $schema_name");

            $conn = new Connection($define_list);
            $conn->join();
            $this->conn_list[$schema_name] = $conn;
        }
        return $this->conn_list[$schema_name];
    }
}
