<?php
namespace Lazy\DB\Redis;

class ConnectionSchema
{
    private $define_list;
    private $conn_list = array();
    public function __construct($array) {
        $this->define_list = $array;
    }

    public function getCon($db_type, $suffix) {
        $conn_index = null;
        $conn_info = null;
        self::setConIndexInfo($conn_index, $conn_info, $suffix);

        if ($this->conn_list[$conn_index] == null) {
            $connection_pool = new ConnectionPool($conn_info[$db_type == DB_TYPE_MST ? 'mst' : 'rep']);
            $this->conn_list[$conn_index] = $connection_pool->getConn();
            $this->conn_list[$conn_index]->join();
        }
    }

    private function setConIndexInfo(&$conn_index, &$conn_info, $suffix = null) {
        if (count($this->define_list) == 1) {
            $conn_index = 0;
            $conn_info = $this->define_list[0]['info'];
        } else {
            if ($suffix === null) {
                global $user_id;
                $suffix = $user_id;
            }

            $group_id = String::getDbTableSuffixUserid($suffix);

            for ($i = 0; $i < count($this->define_list); $i++) {
                $split_info = $this->define_list[$i]['split'];
                $tmp_conn_info = $this->define_list[$i]['info'];

                if (strpos($split_info, $group_id) === false)
                    continue;
                else {
                    $conn_index = $i;
                    $conn_info = $tmp_conn_info;
                    break;
                }
            }
        }
    }


}
