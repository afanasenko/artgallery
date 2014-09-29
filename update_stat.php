<?php 
	require_once("./db_connect.php");
	require('./catalogue_routines.php');
	
	$tbl_stat = 'paintings_stat';
	
	if (isset($_REQUEST['painting']))	
	{
		$pic_id = $_REQUEST['painting'];
	
		if (!count_elements($tbl_stat, 'id_painting', $pic_id))
		{
			$query = 'INSERT INTO ' . $tbl_stat . ' (id_painting, num_clicks, num_rates, total_rate) values (' . $pic_id . ',0,0,0);';
			$result = mysql_query($query);
			if (!$result)
				die('<p>Ошибка при выполнении SQL-запроса: ' . mysql_error() . '</p>');
		}
	
		if (isset($_REQUEST['click']))
		{
			$query = 'UPDATE ' . $tbl_stat . ' SET num_clicks = num_clicks + 1 WHERE id_painting = ' . $pic_id . ';';
			$result = mysql_query($query);
		}
		
		if (isset($_REQUEST['vote']))
		{
			$query = 'UPDATE ' . $tbl_stat . ' SET num_rates = num_rates + 1, total_rate = total_rate + ' . $_REQUEST['vote'] . ' WHERE id_painting = ' . $pic_id . ';';
			$result = mysql_query($query);
			if (!$result)
				die('<p>Ошибка при выполнении SQL-запроса: ' . mysql_error() . '</p>');			
			
			$result = mysql_query('SELECT num_rates, total_rate FROM ' . $tbl_stat . ' WHERE id_painting = ' . $pic_id . ';');
			if (!$result)
				die('<p>Ошибка при выполнении SQL-запроса: ' . mysql_error() . '</p>');			
				
			$stat = mysql_fetch_assoc($result);
			if (!isset($stat['num_rates']))
				$stat['num_rates'] = 0;
			if (!isset($stat['total_rate']))
				$stat['total_rate'] = 0;				
				
			echo $stat['num_rates'] . ' ' . $stat['total_rate'];
		}
	}
?>