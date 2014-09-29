<?php 
	require_once("./db_connect.php");	

	//------------------------------------------------------------------------------
	// Возвращает имя и фамилию художника по идентификатору
	function new_basket()
	{
create table if not exists `site_stat`(
	visit_date date primary key, 
	hits int,
	hosts int
);	
	
		if (isset($_COOKIE['username']) and $_COOKIE['access_level'] == 1)
			$tbl = 'order_' . $_COOKIE['username'];
		else
			$tbl = 'order_' . $_COOKIE['username'];
		if (isset($_COOKIE['access_level']) and $_COOKIE['access_level'] == 2)	
	
		$create_stmt = 'CREATE TABLE IF NOT EXISTS `' . $tbl . '` ();';
		
		$result = mysql_query("SELECT first_name, last_name FROM artists WHERE id_artist = {$aid};");

	}

?>