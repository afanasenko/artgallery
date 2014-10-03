<?php 
	require_once("./db_connect.php");	
	require_once("./translation.php");	

	//------------------------------------------------------------------------------
	// Возвращает имя и фамилию художника по идентификатору
	function get_artist_name_short($aid)
	{
		$is_english = !strcmp(current_lang(), 'en');	
		if ($is_english)
			$query = "SELECT first_name_en as first_name, last_name_en as last_name FROM artists WHERE id_artist = {$aid};";
		else
			$query = "SELECT first_name as first_name, last_name as last_name FROM artists WHERE id_artist = {$aid};";

		$result = mysql_query($query);
		
		if (!$result) {
			die("<p>Неверный запрос: " . mysql_error() . "</p>");
		}		

		$row = mysql_fetch_row($result);
		return "{$row[0]}&nbsp;{$row[1]}";
	}
	
	//------------------------------------------------------------------------------
	// общее число записей с заданным значением заданного поля
	function count_elements($table_name, $field_name, $field_value) 
	{
		if (empty($field_name) or empty($field_value))
			$clause = '';
		else
			$clause = "WHERE `{$field_name}` = {$field_value}";
	
		$result = mysql_query("SELECT count(*) as total FROM `{$table_name}` {$clause};");
		if (!$result)
			return 0;
		$data = mysql_fetch_assoc($result);
		return $data['total'];
	}			
	
	//------------------------------------------------------------------------------	
	//
	
	function basket_name()
	{
		if (isset($_COOKIE['username']))
			return 'order_' . $_COOKIE['username'];
		else
			return 'order_' . $_SERVER['REMOTE_ADDR'];	
	}	
	
	//------------------------------------------------------------------------------	
	//
	function artist_header($aid)
	{
		$is_english = !strcmp(current_lang(), 'en');	
		//Полное имя художника со ссылкой на его страницу
		if ($is_english)
			$result = mysql_query("SELECT first_name_en, middle_name_en, last_name_en FROM artists WHERE id_artist = {$aid};");	
		else
			$result = mysql_query("SELECT first_name, middle_name, last_name FROM artists WHERE id_artist = {$aid};");
			
		if ($result)
		{
			if (!count_elements('paintings', 'id_artist', $aid))
				return;
		
			$num_rows = mysql_num_rows($result);		
			if ($num_rows)
			{
				$row = mysql_fetch_row($result);

				$artist_name = "{$row[0]} {$row[1]} {$row[2]}";

				// при щелчке по художнику динамически формируется его страница, используя переданный идентификатор для загрузки содержимого
				$artist_page = "/artists.php?artist={$aid}";				
				
				echo "<p><a class=\"common-link\" href={$artist_page}>{$artist_name}</a></p>";				
			}
			else
			{
				exit("<p>Такого художника в базе не найдено.</p>");
			}			
		}		
		else
		{
			die("<p>Ошибка при выполнении SQL-запроса: " . mysql_error() . "</p>");
		}
	}	
	
	function picture_desc_short($pid)
	{
		if (!strcmp(current_lang(), 'en'))
		{
			$query = "SELECT name_painting_en as name_painting, first_name_en as first_name, last_name_en as last_name FROM paintings, artists WHERE paintings.id_artist = artists.id_artist AND id_painting = {$pid};";
		}
		else
		{
			$query = "SELECT name_painting, first_name, last_name FROM paintings, artists WHERE paintings.id_artist = artists.id_artist AND id_painting = {$pid};";			
		}
	
		$result = mysql_query($query);	
	
		if ($result)
		{
			$row = mysql_fetch_assoc($result);
			
			return "{$row['first_name']} {$row['last_name']}. {$row['name_painting']}";
		}
		else
			return "nothing for {$pid}";
	}	

	function picture_description($pid)
	{
		$is_english = !strcmp(current_lang(), 'en');
		$cm = $is_english ? 'cm' : 'см';

		$result = mysql_query("SELECT * FROM paintings WHERE id_painting = {$pid};");
		
		if ($result)
		{
			$row = mysql_fetch_array($result);
			
			$picsize = '';
			if ($row['height'] and $row['width'])
				$picsize = "{$row['height']}&nbsp;{$cm}&nbsp;x&nbsp;{$row['width']}&nbsp;{$cm}";

			$mat = '';
			if ($row['id_material'])
			{
				if ($is_english)
					$res2 =  mysql_query("SELECT name_material_en FROM materials WHERE id_material = {$row['id_material']};");
				else
					$res2 =  mysql_query("SELECT name_material FROM materials WHERE id_material = {$row['id_material']};");
				
				if ($res2)
				{
					$row2 = mysql_fetch_row($res2);
					$mat = ", {$row2[0]}";
				}
			}
			
			$year = '';			
			if ($row['created'])
				$year = ", {$row['created']}";			
			
			
			return "{$picsize}{$mat}{$year}";
		}
		else
			return "nothing for {$pid}";
	}	

	function picture_selection_count($attribs, $show_artist)
	{
		$clause = "";
		foreach ($attribs as $att => $val)
		{
			if (empty($clause))
				$clause = "{$att} = {$val}";
			else
				$clause .= " AND {$att} = {$val}";
		}
		
		$result = mysql_query("SELECT id_painting FROM paintings WHERE {$clause};");		
	
		if ($result)
			return mysql_num_rows($result);
		else
			return 0;
	}
	
	function picture_selection($attribs, $show_artist, $clause = '', $limits = array())
	{
		$row_cells = 3;
	
		if (empty($clause))
		{
			$clause = '';
			foreach ($attribs as $att => $val)
			{
				if (empty($clause))
					$clause = "{$att} = {$val}";
				else
					$clause .= " AND {$att} = {$val}";
			}
			
			$query = "SELECT id_painting, token, name_painting, id_artist, on_sale FROM paintings WHERE {$clause} ORDER BY paintings.name_painting";
			
			if (count($limits) == 2)
			{
				$query .= ' LIMIT ' . $limits[0] . ', ' . $limits[1];
			}
			$query .= ';';
			$result = mysql_query($query);
		}
		else
		{
			$query = "SELECT paintings.id_painting, paintings.token, paintings.name_painting, paintings.id_artist, paintings.on_sale FROM {$clause};";
			$result = mysql_query($query);		
		}
	
		if ($result)
		{
			$num_works = mysql_num_rows($result);
			
			if ($num_works)
			{
				echo '<table class="gallery-content">';
				echo '<tr>';
				$c = 0;
				while ($row = mysql_fetch_row($result)) 
				{
					$filename_thumb = "./pictures/thumbs/T_{$row[1]}";
					$filename_big = "./pictures/{$row[1]}";
					$thumb_width = 0;
					
					# если нет уменьшенной копии, устанавливаем ширину для отображения
					if (!file_exists($filename_thumb))
					{
						$filename_thumb = $filename_big;
						$thumb_width = 160;
					}
					
					# чтобы видеть кривые записи в базе, не проверяем наличие картины на диске
					//if (!file_exists($filename_big))
						//continue;
						
					#$desc_str = picture_description($row[0]);
					$desc_str = picture_desc_short($row[0]);
					
					if ($row[4] != 0)
						echo '<td class="highlight">';
					else
						echo '<td>';
					
					echo '<div class="img">';
						if ($show_artist)
						{
							$hudname = get_artist_name_short($row[3]);
							echo "<div class=\"desc\">{$hudname}</div>";
						}
						
						# FIXME: числовое id - не очень хорошо как-то
						echo "<a href=\"./picinfo.php?painting={$row[0]}\" id=\"{$row[0]}\" onclick=\"click_painting(this.id);\"><img src={$filename_thumb} alt=\"{$row[2]}\" title=\"{$row[2]}\"";
						
						if ($thumb_width)
							echo ' width="' . $thumb_width . 'px"';

						echo '></a>';
						echo "<div class=\"desc\">";
							echo "{$desc_str}</br>";
						echo "</div>";
					echo "</div></td>";
					
					$c = $c+1;
					if ($c >= $row_cells)
					{
						$c = 0;
						echo "</tr>";
						echo "<tr>";
					}
				}
				
				echo "</tr>";				
				echo "</table>";			
			}
			else
			{
				echo "<p>Ничего не найдено.</p>";
			}
		}
		else
		{
			die("<p>Ошибка при выполнении SQL-запроса: " . mysql_error() . "</p>");
		}	
	}	
?>