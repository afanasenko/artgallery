<?php 
	require("./header.php");
	require_once("./catalogue_routines.php");		
	require_once("./admin_routines.php");

	if (isset($_GET['article']))
	{
		$result = mysql_query('SELECT * FROM publications WHERE id_article=' . $_GET['article'] . ';');
		if (!$result)
			die("<p>Неверный запрос: " . mysql_error() . "</p>");
			
		$row = mysql_fetch_assoc($result);
	
		$link = './html/' . $row['token'];
		
		if (file_exists($link))
			include($link);
		else
			echo("<p>Файл {$link} не найден на сервере.</p>");	
			
		echo "<a class=\"small_link\" href={$_SERVER['PHP_SELF']}>Все публикации</a>";			
	}
	else
	{
		//Запрос на список статей
		$result = mysql_query("SELECT * FROM publications ORDER BY dt_added DESC;");

		if (!$result)
			die("<p>Неверный запрос: " . mysql_error() . "</p>");
			
		if (mysql_num_rows($result))
		{
			echo "<ul>";
			while ($row = mysql_fetch_assoc($result)) 
			{
				$link = './html/' . $row['token'];
				
				if (isset($row['title']))
					$article_name = $row['title'];
				else
					$article_name = 'Без названия';

				echo "<li>";
				echo "<a href={$_SERVER['PHP_SELF']}?article={$row['id_article']}>{$article_name}</a>";
				echo "<p> (автор: {$row['author']})</p>";
				echo "</li>";
			}
			echo "</ul>";			
		}
		else
		{
			echo "<p>Публикаций в настоящее время не размещено.</p>";
		}
		
		if (is_admin())
			echo '<a class="small_link" href="./management.php?page=2">Новая публикация</a>';				
	}
	
	require("./footer.php"); 
?>