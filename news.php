<?php 
	require("./header.php"); 

/*
	$result = mysql_query("SELECT `id_artist`, `first_name`, `last_name`, `token` FROM `artists` ORDER BY RAND() LIMIT 4");
	
	if (!$result) {
		die("<p>Неверный запрос: " . mysql_error() . "</p>");
	}		

	echo "<table><tr><td><div class=\"gallery-content\"><ul>";			
	
	while ($row = mysql_fetch_row($result)) 
	{
		$pic = "./img/{$row[3]}.jpg";
		
		if (!file_exists($pic))
			continue;
			
		echo "<div class=\"img\"><li>";
		echo "<a href=/artists.php?artist={$row[0]}><img src={$pic} alt=\"{$row[1]} {$row[2]}\" title=\"{$row[1]} {$row[2]}\" height=\"100px\"></a>";
		echo "</li></div>";
	}
	
	echo "</ul></div></td></tr></table>";	
*/	

	if (!strcmp(current_lang(), 'ru'))
		insert_editable_block('editable', './html/ru_welcome.html');
	else
		insert_editable_block('editable', './html/en_welcome.html');

	echo '</br><hr></hr>';
	echo '<h4>' . tr('Frequently watched') . '</h4>';

	$clause = "paintings, paintings_stat WHERE paintings.id_painting=paintings_stat.id_painting ORDER by paintings_stat.num_clicks 	DESC LIMIT 3;";
	picture_selection(null, False, $clause);
	
	echo '</br><hr></hr>';
	echo '<h4>' . tr('Recently added') . '</h4>';

	$clause = "paintings ORDER by dt_added DESC LIMIT 3;";
	picture_selection(null, False, $clause);	

	require("./altfooter.php"); 
?>