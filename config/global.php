<?php

require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

define('MYSQL_SERVER_NAME', $_SERVER['MYSQL_SERVER_NAME']);
define('MYSQL_ROOT_PASSWORD', $_SERVER['MYSQL_ROOT_PASSWORD']);
define('MYSQL_DATABASE', $_SERVER['MYSQL_DATABASE']);
define('MYSQL_USER', $_SERVER['MYSQL_USER']);
define('MYSQL_PASSWORD', $_SERVER['MYSQL_PASSWORD']);
define('PASSPHRASE', $_SERVER['PASSPHRASE']);
define('PROJECT_DIR', dirname(__DIR__, 1));

// Установка часового пояса как в примере (где бы не выполнялся скрипт - одинаковое время)
date_default_timezone_set('Europe/Moscow');

session_start();