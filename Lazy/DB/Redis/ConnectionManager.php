<?php
namespace Lazy\DB\Redis;

class ConnectionManager
{
    private $schema_define_list;
    public function __construct($redis_conf) {
        $this->schema_define_list = $redis_conf;
    }

    public function getCon($schema_name, $db_type, $suffix=null) {
        if (!isset($this->schema_list[$schema_name])) {
            $define_list = $this->schema_define_list[$schema_name];
            $this->schema_list[$schema_name] = new ConnectionSchema($define_list);
        }
        $schema = $this->schema_list[$schema_name];

        return $schema->getCon($db_type, $suffix);
    }

}
