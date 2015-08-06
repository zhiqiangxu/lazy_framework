<?php
namespace Lazy\Utils;


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
