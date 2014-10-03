<?php

	function current_lang()
	{
		if (isset($_COOKIE['language']))
			$page_lang = strcmp($_COOKIE['language'], 'ru') ? 'en' : 'ru';
		else
			$page_lang = 'ru';	
			
		return $page_lang;
	}

	function tr($str)
	{
		static $TRANSLATIONS = array(
			'SITE_TITLE' => array(
				'ru' => 'ArtRuGallery: Современные русские художники', 
				'en' => 'ArtRuGallery: Contemporary Russain artists'),
				
			'PAGE_TITLE' => array(
				'ru' => 'Галерея современного русского искусства ArtRuGallery', 
				'en' => 'ArtRuGallery: Modern Russian art gallery'),
				
			'CMD_EXIT' => array(
				'ru' => 'Выйти', 
				'en' => 'Sign Out'),
				
			'CMD_AUTHORIZATION' => array(
				'ru' => 'Авторизоваться', 
				'en' => 'Sign In'),

			'Chart' => array('ru' => 'Корзина'), 

			'CMD_HOME' => array(
				'ru' => 'Главная', 
				'en' => 'Home'),		
			
			'CMD_PAINTINGS' => array(
				'ru' => 'Картины', 
				'en' => 'Paintings'),			

			'CMD_ARTISTS' => array(
				'ru' => 'Художники', 
				'en' => 'Artists'),	

			'CMD_SERVICES' => array(
				'ru' => 'Услуги', 
				'en' => 'Services'),		
				
			'CMD_ABOUT_US' => array(
				'ru' => 'О&nbsp;нас', 
				'en' => 'About&nbsp;us'),				
				
			'EMAIL_US' => array(
				'ru' => 'Напишите нам:', 
				'en' => 'E-mail&nbsp;us'),					
				
			'BLANK_SUBJECT' => array(
				'ru' => 'Укажите%20тему', 
				'en' => 'Blank%20subject'),			
				
			'MANAGE' => array(
				'ru' => 'Управление', 
				'en' => 'Manage'),
				
			'Frequently watched' => array('ru' => 'Часто просматриваемые'), 
			'Recently added' => array('ru' => 'Недавно добавленные'), 
			'Works' => array('ru' => 'Работы'), 
			'Exhibitions' => array('ru' => 'Выставки'), 
			'Publications' => array('ru' => 'Публикации'), 
			'No works' => array('ru' => 'Работ не найдено'),
			'All artists' => array('ru' => 'Все художники'),
			'Vote' => array('ru' => 'Голосовать!'),
			'Average score: ' => array('ru' => 'Средняя оценка: '),
			'votes: ' => array('ru' => 'голосов: '),
			'Watched: ' => array('ru' => 'Количество просмотров: '),
			'Your score: ' => array('ru' => 'Ваша оценка: '),
			'Added to catalogue ' => array('ru' => 'Добавлена в каталог '),
			'Buy' => array('ru' => 'Купить'),
			'Order a copy' => array('ru' => 'Заказать копию'),
			'Сall' => array('ru' => 'Уточняйте'),
			'Edit' => array('ru' => 'Редактировать'),
			'Price: ' => array('ru' => 'Цена: '),
			'Previous' => array('ru' => 'Предыдущая'),
			'Next' => array('ru' => 'Следующая'),
			'All works' => array('ru' => 'Все работы галереи'),
			'To page top' => array('ru' => 'В начало страницы'),
			'All artist\'s works' => array('ru' => 'Все работы художника')
		);

		if (isset($_COOKIE['language']))
			$page_lang = strcmp($_COOKIE['language'], 'ru') ? 'en' : 'ru';
		else
			$page_lang = 'ru';	

		if (isset($TRANSLATIONS[$str]))
			if (isset($TRANSLATIONS[$str][$page_lang]))
				return $TRANSLATIONS[$str][$page_lang];
			else
				return $str;
		else
			return $str;
	}

?>
