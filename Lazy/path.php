<?php

define('APPLI_ROOT', realpath('..'));

require APPLI_ROOT . "/conf/env_ctrl.php";
require APPLI_ROOT . "/Lazy/Utils.php";
require APPLI_ROOT . "/Lazy/Constants.php";
// M
require APPLI_ROOT . "/Lazy/Model/Table.php";
require APPLI_ROOT . "/Lazy/Model/Redis.php";
// DB
require APPLI_ROOT . "/Lazy/DB/Table/ConnectionManager.php";
require APPLI_ROOT . "/Lazy/DB/Table/ConnectionSchema.php";
require APPLI_ROOT . "/Lazy/DB/Table/ConnectionPool.php";
require APPLI_ROOT . "/Lazy/DB/Table/Connection.php";
// V
require APPLI_ROOT . "/Lazy/Template.php";
// C
require APPLI_ROOT . "/Lazy/Controller/Base.php";
require APPLI_ROOT . "/Lazy/Controller/Scaffold.php";
