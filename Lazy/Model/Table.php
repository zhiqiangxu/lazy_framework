<?php
namespace Lazy\Model;
use Lazy\Utils\String;

class Struct
{
    public function __get($field)
    {
        assert(in_array($field, static::$fields), "No such field:$field");
        return isset($this->$field) ? $this->$field : '';
    }

    public function __set($field, $value)
    {
        assert(in_array($field, static::$fields), "No such field:$field");
        $this->$field = $value;
    }

    public static function exchangeArray($array)
    {
        $struct = new static;
        foreach ($array as $field => $value)
        {
            $struct->$field = $value;
        }
        return $struct;
    }
}

class Table
{
    public static function fetchRow($suffix, $db_type, $sql, $bindings, $array = false) {
        $result = self::query($suffix, $db_type, $sql, $bindings);
        if ($result) {
            $row = $result[0];
            if ($array)
                return $row;

            $fq_class_name = 'Application\Model\\' . static::$STRUCT;
            $struct = $fq_class_name::exchangeArray($row);
            return $struct;
        }

        return null;
    }

    public static function fetchAll($suffix, $db_type, $sql, $bindings = array(), $array = false) {
        $result = self::query($suffix, $db_type, $sql, $bindings);
        if ($array)
            return $result;

        $list = array();
        $fq_class_name = 'Application\Model\\' . static::$STRUCT;
        foreach ($result as $row) {
            $struct = $fq_class_name::exchangeArray($row);

            $list[] = $struct;
        }

        return $list;
    }

    private static function toInsertCSV($struct, $nobinding = array())
    {
        $fields = array();
        $bindings = array();
        $class_name = get_class($struct);
        foreach ($class_name::$fields as $field)
        {
            if (isset($nobinding[$field]))
                $fields[] = $nobinding[$field];
            else {
                $key = ":$field";
                $fields[] = $key;
                $bindings[$key] = $struct->$field;
            }
        }

        return array(join(',', $fields), $bindings);
    }

    public static function do_insert($suffix, $table_name, $struct, $nobinding = array())
    {
        list ($values, $bindings) = self::toInsertCSV($struct, $nobinding);
        $insert_sql = "INSERT INTO $table_name VALUES ($values)";
        global $db_connection_manager;
        $db_con = $db_connection_manager->getCon(static::$SCHEMA, DB_TYPE_MST, $suffix);
        $stmt = $db_con->prepare($insert_sql);
        $stmt->execute($bindings);
        return $db_con->lastInsertId();
    }

    private static function toUpdateSet($struct, $nobinding = array(), $old = array())
    {
        $sets = array();
        $bindings = array();
        foreach ($struct as $field => $value)
        {
            if (in_array($field, explode(',', static::$PK)))
                continue;
            if (isset($old[$field]) && $old[$field] == $value)
                continue;

            if (isset($nobinding[$field]))
                $sets[] = "$field=" . $nobinding[$field];
            else {
                $key = ":$field";
                $sets[] = "$field=$key";
                $bindings[$key] = $struct->$field;
            }
        }

        return array(join(',', $sets), $bindings);
    }

    public static function toUpdateWhere($struct)
    {
        $where = array();
        $bindings = array();
        foreach (explode(',', static::$PK) as $field)
        {
            $key = ":$field";
            $where[] = "$field=$key";
            $bindings[$key] = $struct->$field;
        }

        return array(join(' AND ', $where), $bindings);
    }

    public static function do_update($suffix, $table_name, $struct, $nobinding = array())
    {
        list ($set, $set_bindings) = self::toUpdateSet($struct, $nobinding);
        list ($where, $where_bindings) = self::toUpdateWhere($struct);
        $update_sql = "UPDATE $table_name SET $set WHERE $where";
        $bindings = array_merge($set_bindings, $where_bindings);
        return self::execute($suffix, DB_TYPE_MST, $update_sql, $bindings);
    }

    public static function do_delete($suffix, $table_name, $struct)
    {
        list ($where, $where_bindings) = self::toUpdateWhere($struct);
        $delete_sql = "DELETE FROM $table_name WHERE $where";
        return self::execute($suffix, DB_TYPE_MST, $delete_sql, $where_bindings);
    }

    public static function makeTabeNameUserid($user_id)
    {
        return static::$TABLENAME . '_' . String::getDbTableSuffixUserid($user_id);
    }

    public static function execute($suffix, $db_type, $sql, $bindings)
    {
        global $db_connection_manager;
        $db_con = $db_connection_manager->getCon(static::$SCHEMA, $db_type, $suffix);
        $stmt = $db_con->prepare($sql);
        return $stmt->execute($bindings);
    }

    public static function query($suffix, $db_type, $sql, $bindings = NULL)
    {
        global $db_connection_manager;
        $db_con = $db_connection_manager->getCon(static::$SCHEMA, $db_type, $suffix);

        $stmt = $db_con->prepare($sql);
        $stmt->execute($bindings);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

}


