<?php
namespace Application\Model;
use Lazy\Model\Struct;
use Lazy\Model\Table;

class ExampleSentence extends Struct
{
    public static $fields = array('article_id', 'user_id', 'create_time');
}

class ExampleSentence_DAO extends Table
{
    public static $SCHEMA = 'default';
    public static $STRUCT = 'ExampleSentence';
    public static $PK = 'article_id';
    public static $TABLENAME = 'ExampleSentence';

    public static function insert($struct)
    {
        return parent::do_insert($struct->user_id, self::$TABLENAME, $struct, array('article_id' => 'null', 'create_time' => 'now()'));
    }
    
    public static function delete($struct)
    {
        return parent::do_delete($struct->user_id, self::$TABLENAME, $struct);
    }
    
    public static function update($struct)
    {
        return parent::do_update($struct->user_id, self::$TABLENAME, $struct, array('create_time' => 'now()'));
    }
    
    public static function getRecord($article_id, $db_type = DB_TYPE_REP)
    {
        $sql = "select * from " . self::$TABLENAME . " where article_id=:article_id";
        $bindings = array(':article_id' => $article_id);
        return parent::fetchRow(null, DB_TYPE_REP, $sql, $bindings);
    }
    
    public static function getAll()
    {
        $sql = 'select * from ' . self::$TABLENAME;
        return parent::fetchAll(null, DB_TYPE_REP, $sql);
    }
}
