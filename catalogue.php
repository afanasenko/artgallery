<?php 
	require('./header.php'); 
	
	function ggp($url) { // get GET-parameters string
		preg_match('/^([^?]+)(\?.*?)?(#.*)?$/', $url, $matches);
		$gp = (isset($matches[2])) ? $matches[2] : '';
		return $gp;
	}	
	
	$pic_filter = array();	

	//----------------------------------------------------------------------------------------
	// вывод картин заданного художника
	if (isset($_GET['artist']))
	{
		// Вывод списка картин заданного художника
		$pic_filter['id_artist'] = $_GET['artist'];

		if (picture_selection_count($pic_filter))
		{
			artist_header($_GET['artist']);
			picture_selection($pic_filter, False);
		}
	}
	//----------------------------------------------------------------------------------------
	// вывод картин заданного жанра	
	elseif (isset($_GET['genre']))
	{
		$gid = $_GET['genre'];
		//Запрос на название жанра
		$result = mysql_query("SELECT name_genre FROM genres WHERE id_genre = {$gid};");

		if (!$result) {
			die("<p>Ошибка при запросе жанра: " . mysql_error() . "</p>");
		}
		
		$row = mysql_fetch_row($result);
		echo "<h4>{$row[0]}</h4>";	
		
		$pic_filter['id_genre'] = $gid;
		picture_selection($pic_filter, False);
	}
	else
	{
		// Запрос на список художников
		$result = mysql_query("SELECT id_artist FROM artists ORDER BY last_name;");

		if (!$result) {
			die("<p>Ошибка при выводе списка художников: " . mysql_error() . "</p>");
		}

		while ($row = mysql_fetch_row($result)) 
		{
			$pic_filter['id_artist'] = $row[0];		
			$cnt = picture_selection_count($pic_filter);
			if ($cnt)
			{		
				artist_header($row[0]);
				$range = array(0 => 0, 1 => 9);				
				picture_selection($pic_filter, False, '', $range);
				
				if ($cnt > 9)
					echo '<p><a class="small_link" href="./catalogue.php?artist=' . $row[0] . '">' . tr('All artist\'s works') . '</a></p>';	
				echo '<hr></hr>';			
			}
		}
	}
	
	echo '<table class="small-menu">';
		echo "<tr>";	
			echo '<td><a class="small_link" href="' . $_SERVER['PHP_SELF'] . '">' . tr('All works') . '</a></td>';
			echo '<td><a class="small_link" href=#>' . tr('To page top') . '</a></td>';
		echo "</tr>";
	echo "</table>";
	
	require("./footer.php"); 
?>