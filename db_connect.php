<?php
	require_once("./app_config.php");

	function handle_error()
	{
		die('<p>Ошибка при выполнении запроса: ' . mysql_error() . '</p>');
	}

	mysql_connect(DBHOST, DBUSER, DBPASSWORD) 
		or die("<p>Ошибка подключения к базе данных: " . mysql_error() . "</p>");

	mysql_select_db(DBNAME) 
		or die("<p>Ошибка при выборе базы данных " . DBNAME . ": " . mysql_error() . "</p>");
		
	mysql_query("SET NAMES 'utf8';"); //Задаем кодировку
	
	$today = date('Y-m-d', time()); //узнаем сегодняшнюю дату
	mysql_query("DELETE FROM list_ip WHERE visit_date != \"{$today}\";"); //удаляем строки, где не текущая дата
	
	$result = mysql_query("SELECT * FROM site_stat WHERE visit_date = \"{$today}\";"); //проверяем наличие в БД
	$row = mysql_num_rows($result);	
	if (!$row)
	{
		mysql_query("INSERT INTO site_stat (visit_date, hits, hosts) VALUES (\"{$today}\", 0, 0);") or die(mysql_error());	
	}
	//mysql_query("UPDATE site_stat SET hosts = 0, hits = 0, visit_date = \"{$today}\";"); //меняем дату на сегодняшнюю
	
	$ip = $_SERVER['REMOTE_ADDR']; //получаем ip пользователя
	$result = mysql_query("SELECT * FROM list_ip WHERE ip=\"{$ip}\";"); //проверяем его наличие в БД
	$row = mysql_num_rows($result);
	
	if ($row > 0) //если с этого хоста сегодня уже заходили, то ...
	{
		mysql_query("UPDATE site_stat SET hits = hits+1;") or die(mysql_error()); //обновляем данные 
	}
	else  //иначе ...
	{
		mysql_query("INSERT INTO list_ip (ip, visit_date) VALUES (\"{$ip}\", \"{$today}\");") or die(mysql_error());
   
		mysql_query("UPDATE site_stat SET hosts = hosts+1, hits = hits+1;") or die(mysql_error()); //обновляем данные
	}
?>