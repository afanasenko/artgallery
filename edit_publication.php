<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="./js/jquery-1.10.2.min.js" charset="utf-8"></script>
<script>
	//---------------------------------------------------
	$( document ).ready(function() {
		tinymce.init({
			inline:false,
			selector:"div#article_body"
		});
	});
	
	//---------------------------------------------------	
	function onUploadArticle(fname) {
		var ed = tinymce.get("article_body");
		
		if (!ed)
			return false;

		var url = "./save_html.php";		
		
		$.post(url, { "content" : ed.getContent(), "token" : fname }, function(response){		
			if (response != 0){
				//Error message assumed
				alert(response);
				return false;
			}
		});
		return true;
	}
</script>

<?php 
	require_once("./catalogue_routines.php");	
	require_once("./admin_routines.php");	
	
	if (!is_admin())
		die("This page may be accessed only by administrator");

/*	
	# ---------------------------------------------------------------------
	# Заполняем список художников
	$result = mysql_query('SELECT id_artist, first_name, last_name FROM artists;');			
	if (!$result) {
		die('<p>Неверный запрос: ' . mysql_error() . '</p>');
	}		

	$artists = null;	
	while ($row = mysql_fetch_row($result))
		$artists[$row[0]] = "{$row[1]} {$row[2]}";
*/
	$pub = empty_pub();
	$error_message ='';

	# ---------------------------------------------------------------------
	# обработка заполненной формы
/*
	if (isset($_POST['erase']))
	{
		echo '<script type="text/javascript">window.confirm("Вы действительно хотите удалить запись?")</script>';
	
		#echo '<h4>Удаление на разрешено</h4>';
		
		$pub_id = $_POST['id_article'];
		if ($pub_id)
		{
			$result = mysql_query('SELECT token FROM publications WHERE id_article=' . $pub_id . ';');
			if ($result)
			{
				$row = mysql_fetch_row($result);
				$filename = $row[0];
			}

			$query = 'DELETE FROM publications WHERE id_article=' . $pub_id . ';';
			$result = mysql_query($query);			
			if ($result)
			{
				echo '<script type="text/javascript">window.alert("Запись была удалена.")</script>';
				$pub = empty_pub();
			}	
			
			if (isset($filename))
			{
				if (file_exists('./html/' . $filename))
					unlink('./html/' . $filename);
			}
		}
		
	}	

	if (isset($_POST['reset']))
	{
		echo '<h4>Добавление новой публикации</h4>';
	}		
*/
	if (isset($_POST['update']))
	{
		# заполнение $pub
		if (validate_pub_data($pub, $error_message))
		{
			# новая картина приходит с нулевым id_article, для нее нужно проверить файл изображения		
			if (!$pub['id_article'])
			{
				# создаем пробную запись в БД
				$query = 'INSERT INTO publications (title) VALUES ("' . $pub['title'] . '");';
				$result = mysql_query($query);	
				if ($result) 
				{
					$pub['dt_added'] = date("Y-m-d H:i:s");
					# идентификатор новой записи
					$pub['id_article'] = mysql_insert_id();				
					# уникальное имя для загружаемого изображения
					# $pub['token'] = 'article_' . $pub['id_article'];
				}
				else
					$error_message = 'Ошибка при выполнении SQL-запроса: ' . mysql_error();														
			}

			# обновление записи в БД			
			if ($pub['id_article'])
			{
				# список обновляемых полей
				$clause = '';
				foreach ($pub as $att => $val)
				{
					# идентификатор идет в другую часть запроса
					if (!strcmp($att, 'id_article'))
						continue;
							
					if (!empty($clause))
						$clause .= ", {$att} = \"{$val}\"";
					else
						$clause = "{$att} = \"{$val}\"";
				}		
				$query = "UPDATE publications SET {$clause} WHERE id_article={$pub['id_article']};";
				$result = mysql_query($query);		
				if ($result)
				{
					# Изменения были успешно сохранены
					header("Location: ./publications.php"); 
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
		if (isset($_GET['article']))
		{
			$pub_id = $_GET['article'];	
			
			$result = mysql_query("SELECT * FROM publications WHERE id_article = {$pub_id};");		

			if ($result) 
				$pub = mysql_fetch_assoc($result);
			else
				$error_message = 'Ошибка при выполнении SQL-запроса: ' . mysql_error();		
		}
		else
		{
			$pub = empty_pub();
			echo '<h4>Добавление новой публикации</р4><hr></hr>';
		}
	}

	if (strlen($error_message))
		echo '<p class="error-box">' . $error_message . '</p>';
		
# ========================================================
# ФОРМА
# ========================================================		
	echo '<form id="pub_edit_form" action="' . $_SERVER['PHP_SELF'] . '?page=2" method="POST" enctype="multipart/form-data">';
		# идентификатор публикации в базе	
		echo '<input type="hidden" name="id_article" value="' . $pub['id_article'] . '">';			
		# имя файла на сервере
		echo '<input type="hidden" name="token" value="' . $pub['token'] . '">';						

		echo '<table class="admin-form">';	
# --------------------------------------------			
# Название	
			echo '<tr class="oddrow">';
				echo '<th><label for="title">Название:</label></th>';
				echo '<td><textarea cols="50" name="title">' . $pub['title'] . '</textarea></td>';	
			echo '</tr>';
# --------------------------------------------
# кл. слова
			echo '<tr>';
				echo '<th><label for="keywords">Ключевые слова (через точку с запятой):</label></th>';
				echo '<td><textarea cols="50" name="keywords">' . $pub['keywords'] . '</textarea></td>';	
			echo '</tr>';
# --------------------------------------------
# автор
			echo '<tr class="oddrow">';
				echo '<th><label for="author">Автор:</label></th>';
				echo '<td><input type="text" size="50" maxlength="50" name="author" value="' . $pub['author'] . '"/></td>';			
			echo '</tr>';
# --------------------------------------------
# текст
			echo '<tr>';
				echo '<th><label for="body">Текст статьи (можно вставлять из Word, но картинки отображаться не будут):</label></th>';
				echo '<td>';
				echo '<div id="article_body"></div>';
				echo '</td>';			
			echo '</tr>';			
# --------------------------------------------
# кнопки
			echo '<tr class="oddrow">';
				echo '<th></th>';
				echo '<td>';
					echo '<input type="submit" name="update" value="Сохранить" onclick="onUploadArticle(\'' . $pub['token'] . '\');"/>';
/*
					echo '<input type="submit" name="erase" value="Удалить картину" onclick=show_message("Подтверждаете удаление?");/>';
					echo '<input type="submit" name="reset" value="Добавить ЕЩЕ ОДНУ картину (не эту)"/>';
*/					
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	
	echo '</form>';
?>
