<?php

define('DB_HOST1', '127.0.0.1');
define('DB_HOST2', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');


global $DB_CONF;
$DB_CONF = array(
    'default' => array(
                    array( 'split' => 'none', 'info' => array(
                                                                'mst' => array( array( 'host'=>DB_HOST1, 'user'=>DB_USER, 'pass'=>DB_PASS, 'name'=>'hr',    'encode'=>'UTF8' ) ),
                                                                'rep' => array( array( 'host'=>DB_HOST1, 'user'=>DB_USER, 'pass'=>DB_PASS, 'name'=>'hr',    'encode'=>'UTF8' ) )
                                                            )
                         ),
                   ),
);
