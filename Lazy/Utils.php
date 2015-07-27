<?php
namespace Lazy\Utils;

function get_default_db()
{
    global $DB_URL, $DB_USER, $DB_PASS;
    return new DB($DB_URL, $DB_USER, $DB_PASS);
}

class String {
    public static function getDbTableSuffixUserid($user_id)
    {
        return substr($suffix, strlen($user_id) - 1, 1);
    }

    public static function convertToSuffix($any_string)
    {
        $suffix = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $i = base_convert(md5($any_string), 16, 10) % strlen($suffix);
        return substr($suffix, $i, 1);
    }
}
