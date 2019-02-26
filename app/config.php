<?php
include_once(dirname(__FILE__) . "/../framework/config.php");

include_once(FRAMEWORK_PATH . "/helper.php");
include_once(FRAMEWORK_PATH . "/logging.php");
include_once(FRAMEWORK_PATH . "/tpl.php");
include_once(FRAMEWORK_PATH . "/cache.php");
include_once(FRAMEWORK_PATH . "/database.php");

include_once(dirname(__FILE__) . "/database/db_info.class.php");

include_once(dirname(__FILE__) . "/app/login.class.php");
include_once(dirname(__FILE__) . "/app/setting.class.php");
include_once(dirname(__FILE__) . "/app/upload.php");

// database
defined('MYSQL_SERVER') or define('MYSQL_SERVER', 'localhost');
defined('MYSQL_USERNAME') or define('MYSQL_USERNAME', 'prober');
defined('MYSQL_PASSWORD') or define('MYSQL_PASSWORD', 'prober');
defined('MYSQL_DATABASE') or define('MYSQL_DATABASE', 'prober');
defined('MYSQL_PREFIX') or define('MYSQL_PREFIX', 'prober_');




