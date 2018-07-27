<?php
	// connection information
	function db_open() {
	$host = '';
	$dbname = '';
	$user = '';
	$pass = '';
	// connect to database or return error
	try
	{
	 $db = new PDO("mysql:unix_socket=$host;dbname=$dbname;charset=utf8", $user, $pass);
	 $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	 $db->query('set character_set_client=utf8');
	 $db->query('set character_set_connection=utf8');
	 $db->query('set character_set_results=utf8');
	 $db->query('set character_set_server=utf8');
	 }

	catch(PDOException $e)
	{die('Connection error:' . $pe->getmessage()); }
	return $db;
	}
?>
