<?php

define('DOMAIN_LOCAL',    'localhost');

$http_host = getenv('HTTP_HOST');
if (!$http_host)
    $http_host = $argv[1];
if (!$http_host)
    $http_host = DOMAIN_LOCAL;

$http_host = preg_replace("/:\d+$/", "",$http_host, 1);

global $DOCUMENT_ROOT;
$DOCUMENT_ROOT = dirname(__FILE__) . '/..';

if ($http_host == DOMAIN_LOCAL) {
    require('db/env_local.php');
    require('mem/env_local.php');
}

