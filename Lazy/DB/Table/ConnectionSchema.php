<?php
namespace Lazy\DB\Table;
use Lazy\Utils\String;

class ConnectionSchema {
    private $define_list;
    private $mst_conn_list = array();
    private $mst_tran_list = array();
    private $rep_conn_list = array();

    public function __construct($array) {
        $this->define_list = $array;
        foreach ($array as $info) {
            $this->mst_conn_list[] = null;
            $this->mst_tran_list[] = null;
            $this->rep_conn_list[] = null;
        }
    }

    public function getConMst($tran, $suffix = null) {
        $conn_index = null;
        $conn_info = null;
        self::setConIndexInfo($conn_index, $conn_info, $suffix);

        if ($this->mst_conn_list[$conn_index] == null) {
            $connection_pool = new ConnectionPool($conn_info['mst']);
            $this->mst_conn_list[$conn_index] = $connection_pool->getConn();
            $this->mst_conn_list[$conn_index]->join();
        }

        if ($tran && !$this->mst_tran_list[$conn_index]) {
            $this->mst_conn_list[$conn_index]->startTransaction();
            $this->mst_tran_list[$conn_index] = true;
        }

        return $this->mst_conn_list[$conn_index];
    }

    public function commitMst() {
        for ($i = 0; $i < count($this->mst_conn_list); $i++) {
            if ($this->mst_conn_list[$i] && $this->mst_tran_list[$i]) {
                $this->mst_conn_list[$i]->commit();
                $this->mst_tran_list[$i] = false;
            }
        }
    }

    public function getConRep($suffix = null) {
        $conn_index = null;
        $conn_info = null;
        self::setConIndexInfo($conn_index, $conn_info, $suffix);

        if ($this->rep_conn_list[$conn_index] == null) {
            $connection_pool = new ConnectionPool($conn_info['rep']);
            $this->rep_conn_list[$conn_index] = $connection_pool->getConn();
            $this->rep_conn_list[$conn_index]->join();
        }

        return $this->rep_conn_list[$conn_index];
    }

    public function closeAll() {
        for ($i = 0; $i < count($this->mst_conn_list); $i++) {
            if ($this->mst_conn_list[$i]) {
                $this->mst_conn_list[$i]->close();
                $this->mst_conn_list[$i] = null;
                $this->mst_tran_list[$i] = null;
            }
        }

        for ($i = 0; $i < count($this->rep_conn_list); $i++) {
            if ($this->rep_conn_list[$i]) {
                $this->rep_conn_list[$i]->close();
                $this->rep_conn_list[$i] = null;
            }
        }
    }

    public function rollBackAll() {
        for ($i = 0; $i < count($this->mst_conn_list); $i++) {
            if ($this->mst_tran_list[$i]) {
                $this->mst_conn_list[$i]->rollBack();
                $this->mst_tran_list[$i] = null;
            }
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

    public function isConnect() {
        for ($i = 0; $i < count($this->mst_conn_list); $i++) {
            if ($this->mst_conn_list[$i])
                return true;
        }

        for ($i = 0; $i < count($this->rep_conn_list); $i++) {
            if ($this->rep_conn_list[$i])
                return true;
        }

        return false;
    }
}


