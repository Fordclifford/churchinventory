<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ob_start();
session_start();

define('PROJECT_NAME', 'User Registration with Email Verification ');

define('DB_DRIVER', 'mysql');
define('DBHOST', 'localhost');
define('DBUSER', 'clifford');
define('DBPASS', 'cliffkaka');
define('DBNAME', 'project');

// must end with a slash


$conn = mysql_connect(DBHOST,DBUSER,DBPASS);
	$dbcon = mysql_select_db(DBNAME);
	
	if ( !$conn ) {
		die("Connection failed : " . mysql_error());
	}
	
	if ( !$dbcon ) {
		die("Database Connection failed : " . mysql_error());
	}

?>
