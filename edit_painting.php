<script type="text/javascript" src="./js/jquery-1.10.2.min.js" charset="utf-8"></script>
<script>
	$( document ).ready(function() {
		document.getElementById("painting-name").focus();
	});
</script>

<?php 
	require_once("./catalogue_routines.php");	
	require_once("./admin_routines.php");	
	
	$image_fieldname = 'file_painting';		
	$thumb_fieldname = 'file_thumbnail';		
	$new_item_val = -5;
	
	# ---------------------------------------------------------------------
	# Заполняем список художников
	$result = mysql_query('SELECT id_artist, first_name, last_name FROM artists;');			
	if (!$result) {
		die('<p>Неверный запрос: ' . mysql_error() . '</p>');
	}		

	$artists = null;	
	while ($row = mysql_fetch_row($result))
		$artists[$row[0]] = "{$row[1]} {$row[2]}";

	# ---------------------------------------------------------------------
	# Заполняем список жанров
	$genres = load_array('id_genre', 'name_genre', 'genres');
		
	# ---------------------------------------------------------------------
	# Заполняем список техник
	$materials = load_array('id_material', 'name_material', 'materials');	
	
	# ---------------------------------------------------------------------
	# Заполняем список статусов продажи
	$sale_states = load_array('id_state', 'state_str', 'sale_states');		

	$pic = null;
	$error_message ='';

	# ---------------------------------------------------------------------
	# обработка заполненной формы

	if (isset($_POST['erase']))
	{
		echo '<script type="text/javascript">window.confirm("Вы действительно хотите удалить запись?")</script>';
	
		#echo '<h4>Удаление на разрешено</h4>';
		
		$pic_id = $_POST['id_painting'];
		if ($pic_id)
		{
			$result = mysql_query('SELECT token FROM paintings WHERE id_painting=' . $pic_id . ';');
			if ($result)
			{
				$row = mysql_fetch_row($result);
				$filename = $row[0];
			}

			$query = 'DELETE FROM paintings WHERE id_painting=' . $pic_id . ';';
			$result = mysql_query($query);			
			if ($result)
			{
				echo '<script type="text/javascript">window.alert("Запись была удалена.")</script>';
				$pic = empty_pic();
			}	
			
			if (isset($filename))
			{
				if (file_exists(PICTURE_DIR . $filename))
					unlink(PICTURE_DIR . $filename);
					
				if (file_exists(PICTURE_DIR . 'thumbs/T_' . $filename))
					unlink(PICTURE_DIR . 'thumbs/T_' . $filename);
			}
		}
		
	}	

	if (isset($_POST['reset']))
	{
		echo '<h4>Добавление новой картины</h4>';
	}		
	
	if (isset($_POST['update']))
	{
		# заполнение $pic
		if (validate_form_data($pic, $error_message))
		{
			# новая картина приходит с нулевым id_painting, для нее нужно проверить файл изображения		
			if (!$pic['id_painting'])
			{
				# создаем пробную запись в БД
				$query = 'INSERT INTO paintings (name_painting) VALUES ("' . $pic['name_painting'] . '");';
				$result = mysql_query($query);	
				if ($result) 
				{
					$pic['dt_added'] = date("Y-m-d H:i:s");
					# идентификатор новой записи
					$pic['id_painting'] = mysql_insert_id();				
					# уникальное имя для загружаемого изображения
					$pic['token'] = 'pic' . time() . '.jpg';
					# полный путь для загружаемого изображения
					$dst_path = PICTURE_DIR . $pic['token'];
					
					if (!validate_image($image_fieldname, $dst_path, $error_message))
					{
						# уничтожаем пробную запись
						$result = mysql_query('DELETE FROM `paintings` WHERE id_painting = ' . $pic['id_painting'] . ';');			
						$pic['id_painting'] = 0;
						
						if (!$result) 
						{
							$error_message = 'Ошибка при выполнении SQL-запроса: ' . mysql_error();					
						}										
					}
				}
				else
					$error_message = 'Ошибка при выполнении SQL-запроса: ' . mysql_error();														
			}

			# обновление записи в БД			
			if ($pic['id_painting'])
			{
				# список обновляемых полей
				$clause = '';
				foreach ($pic as $att => $val)
				{
					# идентификатор идет в другую часть запроса
					if (!strcmp($att, 'id_painting'))
						continue;
							
					if (!empty($clause))
						$clause .= ", {$att} = \"{$val}\"";
					else
						$clause = "{$att} = \"{$val}\"";
				}		
				$query = "UPDATE paintings SET {$clause} WHERE id_painting={$pic['id_painting']};";
				$result = mysql_query($query);		
				if ($result)
				{
					# Изменения были успешно сохранены
					header("Location: ./picinfo.php?painting={$pic['id_painting']}"); 
				}
				else
				{
					$error_message = 'Ошибка при выполнении SQL-запроса: ' . mysql_error();
				}					
			}
		}
	}
	else
	{
		# редактирование записи о существующей картине
		if (isset($_GET['painting']))
		{
			$pic_id = $_GET['painting'];	
			
			$result = mysql_query("SELECT * FROM paintings WHERE id_painting = {$pic_id};");		

			if ($result) 
				$pic = mysql_fetch_assoc($result);
			else
				$error_message = 'Ошибка при выполнении SQL-запроса: ' . mysql_error();		
		}
		else
		{
			$pic = empty_pic();
			echo '<h4>Добавление новой картины</р4><hr></hr>';
		}
	}

	if (strlen($error_message))
		echo '<p class="error-box">' . $error_message . '</p>';
		
	if ($pic['id_painting'])
	{
		$painter = get_artist_name_short($pic['id_artist']);
		echo '<h4>' . $painter . '. ' . $pic['name_painting'] . ': ' . picture_description($pic['id_painting']) . '</h4>';		
				
		$filename_thumb = PICTURE_DIR . 'thumbs/T_' . $pic['token'];
		if (!file_exists($filename_thumb))
			$filename_thumb = PICTURE_DIR . $pic['token'];
			
		if (file_exists($filename_thumb))		
		{
			echo '<table class="gallery-content">';			
				echo '<tr>';
					echo '<td><div class="img">';			
						echo '<img src="' . $filename_thumb . '" width="200px">';
					echo '</div></td>';			
				echo '</tr>';				
			echo '</table>';
		}
		else
			echo '<p>Файл ' . $filename_thumb . ' не найден на сервере</p>';
	}
	
# ========================================================
# ФОРМА
# ========================================================		
	echo '<form id="db_edit_form" action="' . $_SERVER['PHP_SELF'] . '?page=1" method="POST" enctype="multipart/form-data">';
		# идентификатор картины в базе	
		echo '<input type="hidden" name="id_painting" value="' . $pic['id_painting'] . '">';			
		# имя файла на сервере
		echo '<input type="hidden" name="token" value="' . $pic['token'] . '">';						

		echo '<table class="admin-form">';	
# --------------------------------------------			
# Название	
			echo '<tr class="oddrow">';
				echo '<th><label for="name_painting">Название:</label></th>';
				echo '<td><textarea cols="50" name="name_painting" id="painting-name">' . $pic['name_painting'] . '</textarea></td>';	
			echo '</tr>';
# --------------------------------------------				
# File

			echo '<tr>';
				echo '<th><label for="' . $image_fieldname . '">Основное изображение:</label></th>';
				echo '<td><input name="' . $image_fieldname . '" type="file"/></td>';	
			echo '</tr>';			
# Thumbnail	image
			echo '<tr class="oddrow">';
				echo '<th><label for="' . $thumb_fieldname . '">Ярлык:</label></th>';
				echo '<td><input name="' . $thumb_fieldname . '" type="file"/></td>';	
			echo '</tr>';						
# --------------------------------------------			
# Artist
			echo '<tr>';
				echo '<th><label for="id_artist">Художник:</label></th>';
				echo '<td>';
					echo '<select name="id_artist">';
						if (isset($artists))
						{
							foreach ($artists as $aid => $aname)
							{
								if ($aid == $pic['id_artist'])
									echo '<option value="' . $aid . '" selected="selected">' . $aname . '</option>';
								else
									echo '<option value="' . $aid . '">' . $aname . '</option>';
							}
						}			
					echo '</select>';
				echo '</td>';
			echo '</tr>';	
# --------------------------------------------
# Размеры			
			echo '<tr class="oddrow">';
				echo '<th><label for="height">Размеры (см):</label></th>';
				echo '<td>';
				echo 'высота <input type="text" size="4" maxlength="5" name="height" value="' . $pic['height'] .'"/>';
				echo ', ширина <input type="text" size="4" maxlength="5" name="width" value="' . $pic['width'] . '"/>';				
				echo '</td>';
			echo '</tr>';
# --------------------------------------------
# Год создания
			echo '<tr>';
				echo '<th><label for="created">Год создания:</label></th>';
				echo '<td><input type="text" size="4" maxlength="4" name="created" value="' . $pic['created'] . '"/></td>';			
			echo '</tr>';
# --------------------------------------------
# Жанр
			echo '<tr class="oddrow">';
				echo '<th><label for="id_genre">Жанр:</label></th>';
				echo '<td>';
					echo '<select name="id_genre">';
					
					if (isset($genres))
					{
						foreach ($genres as $iid => $iname)
						{
							if ($iid == $pic['id_genre'])
								echo '<option value="' . $iid . '" selected="selected">' . $iname . '</option>';
							else
								echo '<option value="' . $iid . '">' . $iname . '</option>';
						}
						
						#echo '<option value="' . $new_item_val . '">другой ...</option>';
					}						
					echo '</select>';			
					#echo ' другой жанр: <input type="text" size="20" name="new_genre"';
					
				echo '</td>';			
			echo '</tr>';
# --------------------------------------------
# Техника
			echo '<tr>';
				echo '<th><label for="id_material">Техника:</label></th>';
				echo '<td>';
					echo '<select name="id_material">';
					
					if (isset($materials))
					{
						foreach ($materials as $iid => $iname)
						{
							if ($iid == $pic['id_material'])
								echo '<option value="' . $iid . '" selected="selected">' . $iname . '</option>';
							else
								echo '<option value="' . $iid . '">' . $iname . '</option>';
						}
						
						#echo '<option value="' . $new_item_val . '">другая ...</option>';
					}						
					echo '</select>';			
					#echo ' другая техника: <input type="text" size="20" name="new_material"';
					
				echo '</td>';			
			echo '</tr>';			
# --------------------------------------------
# Продажа
			echo '<tr  class="oddrow">';
				echo '<th><label for="for_sale">Выставлена на продажу:</label></th>';
				echo '<td>';
					if ($pic['on_sale'])
					{
						$state = 'checked';
						$state_inv = '';
					}
					else
					{
						$state = '';
						$state_inv = 'checked';
					}
				echo '<label><input type="radio" name="on_sale" value="1"' . $state . '/>Да</label>';
				echo '<label><input type="radio" name="on_sale" value="0"' . $state_inv . '/>Нет</label>';
					
				echo '</td>';			
			echo '</tr>';	
# --------------------------------------------
# Статус продажи			
			echo '<tr>';
				echo '<th><label for="sale_status">Состояние:</label></th>';
				echo '<td>';
					echo '<select name="sale_status">';
					
					if (isset($sale_states))
					{
						foreach ($sale_states as $iid => $iname)
						{
							if ($iid == $pic['sale_status'])
								echo '<option value="' . $iid . '" selected="selected">' . $iname . '</option>';
							else
								echo '<option value="' . $iid . '">' . $iname . '</option>';
						}
					}						
					echo '</select>';			
				echo '</td>';			
			echo '</tr>';				
# --------------------------------------------
# Цена
			echo '<tr class="oddrow">';
				echo '<th><label for="price">Цена ($):</label></th>';	
				echo '<td><input type="text" size="4" maxlength="8" name="price" value="' . $pic['price'] . '"/></td>';
			echo '</tr>';
# --------------------------------------------			
# Название англ
			echo '<tr>';
				echo '<th><label for="name_painting_en">Название (англ.):</label></th>';
				echo '<td><textarea cols="50" name="name_painting_en">' . $pic['name_painting_en'] . '</textarea></td>';	
			echo '</tr>';			
# --------------------------------------------
			echo '<tr>';
				echo '<th></th>';
				echo '<td>';
					echo '<input type="submit" name="update" value="Сохранить текущие значения"/>';
					echo '<input type="submit" name="erase" value="Удалить картину" onclick=show_message("Подтверждаете удаление?");/>';
					echo '<input type="submit" name="reset" value="Добавить ЕЩЕ ОДНУ картину (не эту)"/>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	
	echo '</form>';
?>
