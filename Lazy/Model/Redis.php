<?php
namespace Lazy\Model;

class Redis
{
    protected static $SCHEMA = null;
    public $client = null;

    public function __construct($suffix, $db_type = DB_TYPE_MST) {
        global $redis_connection_manager;
        $this->client = $redis_connection_manager->getCon(self::$SCHEMA, $db_type, $suffix);
    }


}
