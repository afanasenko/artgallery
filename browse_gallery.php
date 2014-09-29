<?php 
	#require_once("./authorization.php");
	require_once("./catalogue_routines.php");			
	require_once("./db_connect.php");
	
	echo "<h4 align=\"center\">Живопись:</h4>";		
	echo "<ul>";	
	
	$result = mysql_query("SELECT id_genre, name_genre, order_genre FROM genres ORDER by order_genre;");		
	
	if ($result)
	{
		while ($row = mysql_fetch_row($result)) 
		{
			$count = count_elements('paintings', 'id_genre', $row[0]);
			
			if ($count)
			{
				$genre = $row[1];
				if (!strcmp($genre,""))
					$genre = "Другое";
				
				echo "<li><a class=\"common-link\" href=/catalogue.php?genre={$row[0]}>{$genre} ({$count})</a></li>";				
			}
		}
	}
	else
	{
		die("<p>Ошибка при выполнении SQL-запроса: " . mysql_error() . "</p>");		
	}	

	echo "</ul>";	
	
	echo "<h4 align=\"center\">Посетители сайта:</h4>";		
	echo "<ul>";				
	
	$today = date('Y-m-d', time()); //узнаем сегодняшнюю дату
	$result = mysql_query("SELECT hosts FROM site_stat WHERE visit_date = \"{$today}\";");		
	
	if ($result)
	{
		$row = mysql_fetch_array($result);
		echo "<li>За сегодня: {$row[0]}</li>";						
	}	
	else
	{
		die("<p>Ошибка при выполнении SQL-запроса: " . mysql_error() . "</p>");		
	}	
	
	$result = mysql_query("SELECT SUM(hosts) as total FROM site_stat;");		
	
	if ($result)
	{
		$row = mysql_fetch_assoc($result);
		echo "<li>За все время: {$row['total']}</li>";								
	}	
	else
	{
		die("<p>Ошибка при выполнении SQL-запроса: " . mysql_error() . "</p>");		
	}		
	echo "</ul>";						
?>