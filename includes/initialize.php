<?php  
	error_reporting(E_ALL);
	//absolute path definition
	defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

	defined("SITE_ROOT") ? null : define("SITE_ROOT", DS . "var" . DS . "www" . DS . "html" . DS . "threatmap");

	defined("LIB_PATH") ? null : define("LIB_PATH", SITE_ROOT . DS . "includes");

	defined("TEMPORARY_ROOT_HOSTNAME") ? null : define("TEMPORARY_ROOT_HOSTNAME", "http://localhost/threatmap/");

	defined("THREATMAP_PUBLIC_URL") ? null : define("THREATMAP_PUBLIC_URL", TEMPORARY_ROOT_HOSTNAME);

	require_once(LIB_PATH . DS . "db_config.php");
	require_once(LIB_PATH . DS . "functions.php");

	//load core objects
	require_once(LIB_PATH . DS . "database.php");
	require_once(LIB_PATH . DS . "session.php");
	require_once(LIB_PATH . DS . "databaseObject.php");

	//load Database related objects
	require_once(LIB_PATH . DS . "user.php");
	require_once(LIB_PATH . DS . "client.php");
	require_once(LIB_PATH . DS . "office.php");
?>