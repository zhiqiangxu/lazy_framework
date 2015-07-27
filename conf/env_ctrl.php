<?php

define('DOMAIN_LOCAL',    'localhost');

$httpHost = getenv('HTTP_HOST');
if (!$httpHost)
    $httpHost = $argv[1];
$httpHost = preg_replace("/:\d+$/", "",$httpHost, 1);

global $DOCUMENT_ROOT;
$DOCUMENT_ROOT = dirname(__FILE__) . '/..';

if ($httpHost == DOMAIN_LOCAL) {
    require('db/env_local.php');
    require('mem/env_local.php');
}

