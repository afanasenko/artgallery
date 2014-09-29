<?php 
	require_once("./catalogue_routines.php");		
	require("./header.php");
	
	//FIXME: при ошибках не нарушать скелет страницы!!!
	require_once("./db_connect.php");
	
	if (isset($_GET['artist']))
	{
		// загрузка содержимого персональной страницы художника
		$aid = $_GET['artist'];
		
		//Запрос на имя художников
		$result = mysql_query("SELECT first_name, middle_name, last_name, token FROM artists WHERE id_artist = {$aid};");
		
		if (!$result) {
			die("<p>Неверный запрос: " . mysql_error() . "</p>");
		}		

		$row = mysql_fetch_row($result); 
		
		// Маленькое меню по художнику
		echo "<h2>{$row[0]} {$row[1]} {$row[2]}</h2>";
		
		echo "<table class=\"small-menu\">";
			echo "<tr>";
				// общее число картин, принадлежащих данному художнику
				$count = count_elements('paintings', 'id_artist', $aid);
				if ($count)
					echo "<td><a class=\"small_link\" href=/catalogue.php?artist={$aid}>Работы ({$count})</a></td>";
					
				$count = count_elements('exhibitions', 'id_artist', $aid);
				if ($count)					
					echo "<td><a class=\"small_link\" href=/exhibitions.php?artist={$aid}>Выставки ({$count})</a></td>";		

				$count = count_elements('publications', 'id_artist', $aid);
				if ($count)										
					echo "<td><a class=\"small_link\" href=/publications.php?artist={$aid}>Публикации ({$count})</a></td>";						
			echo "</tr>";
		echo "</table>";
		echo "</br>";

		// Подгружаем текст

		echo '<img src="./img/' . $row[3] . '.jpg" alt="' . $row[0] . ' ' . $row[1] . '" class="image-right">';
		
		$pers = "./html/" . $row[3] . ".html";		
		insert_editable_block('editable', $pers);
			
		echo "<a class=\"small_link\" href={$_SERVER['PHP_SELF']}>Все художники</a>";
	}
	else
	{
		//Запрос на список художников
		$result = mysql_query("SELECT id_artist, first_name, middle_name, last_name, token FROM artists ORDER BY last_name;");

		if (!$result) {
			die("<p>Неверный запрос: " . mysql_error() . "</p>");
		}		

		echo "<table><tr>";
		$i = 0;
		while ($row = mysql_fetch_row($result)) 
		{
			$artist_name = "{$row[1]} {$row[2]} {$row[3]}";
			// при щелчке по художнику динамически формируется его страница, используя переданный идентификатор для загрузки содержимого
			$artist_page = "/artists.php?artist={$row[0]}";				
			$works_page = "/catalogue.php?artist={$row[0]}";				
			$num_works = count_elements('paintings', 'id_artist', $row[0]);		

			$pic = "./img/{$row[4]}.jpg";

			echo "<td><img src={$pic} alt=\"{$row[1]} {$row[3]}\" title=\"{$row[1]} {$row[3]}\" width=\"150px\"></td>";
			echo '<td>';
			echo "<a class=\"common-link\" href={$artist_page}>{$artist_name}</a></br>";
			
			if ($num_works)
				echo "<a class=\"small_link\" href={$works_page}>Работы ({$num_works})</a></br>";
			else
				echo "<p class=\"small_text\">Работ не найдено</p>";
				
			echo "</td>";
			
			$i++;
			if (!($i % 2))
				echo '</tr><tr>';			
		}
		
		echo "</tr></table>";
	}
	
	require("./footer.php"); 
?>