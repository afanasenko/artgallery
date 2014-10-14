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
				'en' => 'Contact&nbsp;us'),					
				
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
			'All artist\'s works' => array('ru' => 'Все работы художника'),
			'Paintings:' => array('ru' => 'Живопись:'),
			'Other' => array('ru' => 'Другое'),
			'Visitors: ' => array('ru' => 'Посетители сайта: '),
			'Today: ' => array('ru' => 'За сегодня: '),
			'Total: ' => array('ru' => 'За все время: '),
			'Map:' => array('ru' => 'Карта проезда:'),
			'ERR_USER_CONTACT' => array('ru' => 'Укажите Ваше имя и хотя бы один способ связи.', 'en' => 'Please, specify your name and at least one way to contact you'),
			'Order information' => array('ru' => 'Информация о заказе'),
			'Description' => array('ru' => 'Описание'),
			'Price' => array('ru' => 'Цена'),
			'Delete' => array('ru' => 'Удалить'),
			'TOTAL_PRICE' => array('ru' => 'Итого', 'en' => 'Total'),
			'Refresh' => array('ru' => 'Обновить'),
			'EMPTY_BASKET_MSG' => array('ru' => 'Ваша корзина пуста. Добавьте в нее понравившиеся картины из каталога и оформите заказ', 'en' => 'Your chart is empty. Please add the paintings you like and make an order'),
			'Your name' => array('ru' => 'Ваше имя'),
			'Your phone number' => array('ru' => 'Телефон'),
			'Your e-mail' => array('ru' => 'Электронная почта'),
			'Additional info' => array('ru' => 'Дополнительная информация к заказ'),
			'Make an order' => array('ru' => 'Оформить заказ'),
			'Clear your chart' => array('ru' => 'Очистить корзину'),
			'Your chart is empty' => array('ru' => 'Ваша корзина пуста'),
			'Orders' => array('ru' => 'Заказы'),
			'Created' => array('ru' => 'Дата создания'),
			'Status' => array('ru' => 'Состояние'),
			'Buyer' => array('ru' => 'Покупатель'),
			'Action' => array('ru' => 'Действия'),
			'Vote!' => array('ru' => 'Голосовать'),
			'Accept' => array('ru' => 'Принять'),
			'Discard' => array('ru' => 'Отклонить')
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
