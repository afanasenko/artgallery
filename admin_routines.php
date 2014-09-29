<?php 
	require_once("./db_connect.php");	
	
	function load_array($key_field, $val_field, $table_name)
	{
		# ---------------------------------------------------------------------
		# Заполняем список
		$result = mysql_query('SELECT ' . $key_field . ', ' . $val_field . ' FROM ' . $table_name . ';');			
		if (!$result) {
			die('<p>Неверный запрос: ' . mysql_error() . '</p>');
		}		

		$arr = null;	
		while ($row = mysql_fetch_row($result))
			$arr[$row[0]] = $row[1];
			
		return $arr;
	}
	
	# ---------------------------------------------------------------------
	function validate_image($image_src, $image_dst, &$error_message)
	{
		if (!isset($_FILES[$image_src]))
		{
			$error_message = 'Неизвестная ошибка';
			return False;
		}
	
		$err = $_FILES[$image_src]['error'];
		
		if ($err)
		{
			// Потенциальные PHP-ошибки отправки файла на сервер
			$php_file_errors = array(
			0 => 'Файл успешно загружен',
			1 => 'Превышен макс. размер файла, указанный в php.ini',
			2 => 'Превышен макс. размер файла, указанный в форме HTML',
			3 => 'Была отправлена только часть файла',
			4 => 'Файл для отправки не был выбран.');			
		
			$error_message = $php_file_errors[$err];
			return False;
		}
		
		if (!move_uploaded_file($_FILES[$image_src]['tmp_name'], $image_dst)) 
		{
			$error_message = 'Ошибка копирования файла';
			return False;		
		}

		return True;
	}

	# ---------------------------------------------------------------------
	function ToInt($s)
	{
		return (int)$s;
	}	
	
	# ---------------------------------------------------------------------
	function empty_pic()
	{
		return array (
		'id_painting' => 0,
		'name_painting' => '',
		'name_painting_en' => '',
		'id_artist' => 0,
		'id_genre' => '',
		'id_material' => 0,
		'width' => '',
		'height' => '',
		'created' => '',
		'on_sale' => 0,
		'price' => '',
		'sale_status' => '',
		'token' => ''
		);
	}
	
	# ---------------------------------------------------------------------
	function empty_pub()
	{
		return array (
		'id_article' => 0,
		'title' => '',
		'keywords' => '',
		'author' => '',
		'source' => '',
		'token' => 'article_' . time() . '.html'
		);
	}	
	
	# ---------------------------------------------------------------------
	# преобразование полей формы
	function validate_form_data(&$attribs, &$error_message)
	{
		if (empty($_POST['name_painting']))
		{
			$error_message = 'Укажите название картины';
			return False;
		}
		
		$attribs['name_painting'] 	= trim($_POST['name_painting']);
		$attribs['name_painting_en'] 	= trim($_POST['name_painting_en']);		
		
		if (isset($_POST['id_artist']))
			$attribs['id_artist']		= ToInt($_POST['id_artist']);
			
		if (isset($_POST['id_genre']))
			$attribs['id_genre']		= ToInt($_POST['id_genre']);
		
		if (strlen($_POST['width']))
			$attribs['width']		= ToInt($_POST['width']);

		if (strlen($_POST['height']))
			$attribs['height']		= ToInt($_POST['height']);
			
		if (strlen($_POST['created']))
			$attribs['created']		= ToInt($_POST['created']);

		if (strlen($_POST['price']))
			$attribs['price']		= ToInt($_POST['price']);
			
		if (strlen($_POST['on_sale']))
			$attribs['on_sale']		= ToInt($_POST['on_sale']);

		if (strlen($_POST['sale_status']))
			$attribs['sale_status']		= $_POST['sale_status'];
			
		if (strlen($_POST['id_material']))
			$attribs['id_material']		= ToInt($_POST['id_material']);

		if (strlen($_POST['token']))
			$attribs['token']		= $_POST['token'];
			
		$attribs['id_painting'] = ToInt($_POST['id_painting']);			
			
		return True;
	}
		
	# ---------------------------------------------------------------------
	# преобразование полей формы
	function validate_pub_data(&$attribs, &$error_message)
	{
		if (empty($_POST['title']))
		{
			$error_message = 'Укажите название публикации';
			return False;
		}
		
		$attribs['title'] 	= trim($_POST['title']);
			
		if (strlen($_POST['keywords']))
			$attribs['keywords']		= trim($_POST['keywords']);			
			
		if (strlen($_POST['author']))
			$attribs['author']		= trim($_POST['author']);			
			
		if (strlen($_POST['source']))
			$attribs['source']		= trim($_POST['source']);

		if (strlen($_POST['token']))
			$attribs['token']		= trim($_POST['token']);
			
		$attribs['id_article'] = ToInt($_POST['id_article']);			
			
		return True;
	}		
	
	function is_admin()
	{
		if (isset($_COOKIE['access_level']))
		{
			return $_COOKIE['access_level'] == 2;
		}
		else
			return false;	
	}

	//вставка внешнего документа с возможностью редактирования
	function insert_editable_block($id, $filename)
	{
		echo '<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>';
		echo '<script type="text/javascript" src="./js/jquery-1.10.2.min.js" charset="utf-8"></script>';
		echo '<script type="text/javascript" src="./js/docedit.js" charset="utf-8"></script>';
	
		echo '<div id="' . $id . '">';
		
			if (file_exists($filename))
				include($filename);
			else
				echo("<p>Файл {$filename} не найден на сервере.</p>");		
			
		echo '</div>';			
	
		if (is_admin())
		{
			echo '<table><tr>';
			
				echo '<td><button id="' . $id . '_edit" onclick="EditContent();" style="width:14em; display:block;">Редактировать текст</button></td>';			
				
				echo '<td><button id="' . $id . '_save" onclick="SaveContent(\'' . $filename . '\');" style="width:10em; display:none;">Сохранить</button></td>';
				
				echo '<td><button id="' . $id . '_discard" onclick="HideEditor();" style="width:10em; display:none;">Отмена</button></td>';		
			
			echo '</tr></table>';
		}
	}
?>