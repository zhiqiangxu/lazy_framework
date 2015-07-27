<?php

define('APPLI_ROOT', realpath('..'));

require APPLI_ROOT . "/conf/env_ctrl.php";
require APPLI_ROOT . "/Lazy/Utils.php";
require APPLI_ROOT . "/Lazy/Constants.php";
// M
require APPLI_ROOT . "/Lazy/Model/Table.php";
require APPLI_ROOT . "/Lazy/Model/Redis.php";
// V
require APPLI_ROOT . "/Lazy/Template.php";
// C
require APPLI_ROOT . "/Lazy/Controller/Base.php";
require APPLI_ROOT . "/Lazy/Controller/Scaffold.php";
