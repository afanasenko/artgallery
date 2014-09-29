<?php 
	require("./catalogue_routines.php");	

	function new_basket($name)
	{
		$create_stmt = 'CREATE TABLE IF NOT EXISTS `' . $name . '`(id_painting int primary key, description varchar(128), price int);';
		$result = mysql_query($create_stmt);

		if (!$result)
			die("<p>Неверный запрос: " . mysql_error() . "</p>");
	}

	if (isset($_GET['painting']) and $_GET['painting'] > 0)
	{
		$basket = basket_name();
		new_basket($basket);
	
		$pic_id = $_GET['painting'];
		
		$query = "SELECT name_painting, price, first_name, last_name FROM paintings, artists WHERE paintings.id_artist = artists.id_artist AND id_painting = {$pic_id};";	
		
		$result = mysql_query($query);
		if (!$result)
			die("<p>Неверный запрос: " . mysql_error() . "</p>");		
		
		
		if ($result)
		{
			$row = mysql_fetch_assoc($result);
			$desc = $row['first_name'] . ' ' . $row['last_name'] . '. ' . $row['name_painting'];
			
			$query = 'INSERT INTO ' . $basket . ' (id_painting, description, price) VALUES(' . $pic_id . ', \'' . $desc . '\', ' . $row['price'] . ');';
			$res = mysql_query($query);			
			/*
			if (!isset($_GET['r']))
			{
				if (!$res)
					handle_error();
			}
			*/
		}
	}
	echo count_elements($basket, '', '');
?>