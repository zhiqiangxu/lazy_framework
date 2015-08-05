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
        return parent::insert($struct->user_id, self::$TABLENAME, $struct, array('article_id' => 'null', 'create_time' => 'now()'));
    }
}
