<?php
namespace Lazy\DB\Table;

class ConnectionManager {

    private $schema_define_list;
    private $schema_list = array();
    private $tran_all = false;
    public function __construct($db_conf) {
        $this->schema_define_list = $db_conf;
    }

    public function getCon($schema_name, $db_type, $suffix=null) {
        if (!isset($this->schema_list[$schema_name])) {
            $define_list = $this->schema_define_list[$schema_name];
            $this->schema_list[$schema_name] = new ConnectionSchema($define_list);
        }
        $schema = $this->schema_list[$schema_name];

        if ($db_type == DB_TYPE_MST)
            return $schema->getConMst($this->tran_all, $suffix);
        else if ($db_type == DB_TYPE_REP)
            return $schema->getConRep($suffix);
    }

    public function startTranAll() {
        $this->tran_all = true;
    }

    public function commitAll() {
        foreach ($this->schema_list as $schema) {
            $schema->commitMst();
        }
    }
}
