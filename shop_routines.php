<?php 
	require_once("./db_connect.php");	

	// Function to get the client IP address
	function get_client_ip() {
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}	
	
	//------------------------------------------------------------------------------
	// Возвращает имя и фамилию художника по идентификатору
	function new_basket()
	{
		$tbl = 'order_' . get_client_ip();	
		$create_stmt = 'CREATE TABLE IF NOT EXISTS `' . $tbl . '` (item_id int primary key auto_increment, id_painting int, description varchar(255), price int);';	
		
		mysql_query($create_stmt);
	}
);	

?>