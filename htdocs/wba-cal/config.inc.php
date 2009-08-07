<?
/* ©2004 Proverbs, LLC. All rights reserved. */ 

if (eregi("config.inc.php", $_SERVER['PHP_SELF']))
{
	// redirect to calendar page
	header("Location: calendar.php");
	exit;
}

if(!defined("CONFIGURATION_INFO")) 
{ 
	define("CONFIGURATION_INFO", TRUE);
	
	class cal_config
	{
		/* This is the sql server name */
		var $databasehost 		= 'localhost';
		/* Name of the database used */
		var $databasename 		= 'westbranchresort_com_-_rivermaster';
		/* Name used to connect to the server database.  Must have read/write access */
		var $databaseuser 		= 'root';
		/* Password used to connect to the server database */
		var $databasepassword 	= 's1a2mayfly';

		function cal_config()
		{
		}
	}
}
?>