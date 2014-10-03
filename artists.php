<?php 
	require("./header.php");
	
	$is_english = !strcmp(current_lang(), 'en');
	
	if (isset($_GET['artist']))
	{
		// загрузка содержимого персональной страницы художника
		$aid = $_GET['artist'];
		
		//Запрос на имя художников
		if ($is_english)
			$result = mysql_query("SELECT first_name_en, middle_name_en, last_name_en, token FROM artists WHERE id_artist = {$aid};");
		else
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
					echo '<td><a class="small_link" href="/catalogue.php?artist=' . $aid . '">' . tr('Works') . '(' . $count . ')</a></td>';
					
				$count = count_elements('exhibitions', 'id_artist', $aid);
				if ($count)					
					echo '<td><a class="small_link" href="/exhibitions.php?artist=' . $aid . '">' . tr('Exhibitions') . '(' . $count . ')</a></td>';		

				$count = count_elements('publications', 'id_artist', $aid);
				if ($count)										
					echo '<td><a class="small_link" href="/publications.php?artist=' . $aid . '">' . tr('Publications') . '(' . $count . ')</a></td>';
			echo "</tr>";
		echo "</table>";
		echo "</br>";

		// Подгружаем текст

		echo '<img src="./img/' . $row[3] . '.jpg" alt="' . $row[0] . ' ' . $row[1] . '" class="image-right">';
		
		if ($is_english)
			$pers = "./html/en_" . $row[3] . ".html";		
		else
			$pers = "./html/ru_" . $row[3] . ".html";		
			
		insert_editable_block('editable', $pers);
			
		echo '<a class="small_link" href="' . $_SERVER['PHP_SELF'] . '">' . tr('All artists') . '</a>';
	}
	else
	{
		//Запрос на список художников
		$result = mysql_query("SELECT id_artist, first_name, middle_name, last_name, token FROM artists ORDER BY last_name;");
		
		if ($is_english)
			$result = mysql_query("SELECT id_artist, first_name_en, middle_name_en, last_name_en, token FROM artists ORDER BY last_name;");
		else
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
				echo '<a class="small_link" href=' . $works_page . '>' . tr('Works') . ' (' . $num_works . ')</a></br>';
			else
				echo '<p class="small_text">' . tr('No works') . '</p>';
				
			echo "</td>";
			
			$i++;
			if (!($i % 2))
				echo '</tr><tr>';			
		}
		
		echo "</tr></table>";
	}
	
	require("./footer.php"); 
?>