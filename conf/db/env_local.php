<?php

define('DB_HOST1', 'localhost');
define('DB_HOST2', 'localhost');
define('DB_USER', '');
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
