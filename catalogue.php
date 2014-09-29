<?php 
	#require_once('./authorization.php');	
	require('./catalogue_routines.php');	
	require('./header.php'); 
	
	function ggp($url) { // get GET-parameters string
		preg_match('/^([^?]+)(\?.*?)?(#.*)?$/', $url, $matches);
		$gp = (isset($matches[2])) ? $matches[2] : '';
		return $gp;
	}	
	
	$pic_filter = array();	

/*
<form id="catalogue-browsing" name="cataform" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
  <table>
    <tr>
      <td><label>
        <input type="radio" name="sales-filter" value="all-works" id="sales-filter-0" <?php if ($all_works_selected) echo "checked"; ?>/>
        Все работы</label></td>
      <td><label>
        <input type="radio" name="sales-filter" value="sale-works" id="sales-filter-1" <?php if (!$all_works_selected) echo "checked"; ?>/>
        Работы, выставленные на продажу</label></td>
	  <td>
		<input type="submit" value="Применить фильтр" /></td>
    </tr>
  </table>
</form>
*/

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
					echo "<p><a class=\"small_link\" href=/catalogue.php?artist={$row[0]}>Все работы художника</a></p>";	
				echo '<hr></hr>';			
			}
		}
	}
	
	echo "<table class=\"small-menu\">";
		echo "<tr>";	
			echo "<td><a class=\"small_link\" href={$_SERVER['PHP_SELF']}>Все работы галереи</a></td>";
			echo "<td><a class=\"small_link\" href=#>В начало страницы</a></td>";
		echo "</tr>";
	echo "</table>";
	
	require("./footer.php"); 
?>