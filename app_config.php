<?php
	//Константы
	define("DBHOST", "localhost");	
	
	# development
/*
	define("DBNAME", "rusart_main");
	define("DBUSER", "root");
	define("DBPASSWORD", "1234");
	define("PMA_LINK", "http://localhost/phpmyadmin/");
*/
	# production
	define("DBNAME", "intergrots_artru");
	define("DBUSER", "intergrots_artru");
	define("DBPASSWORD", "rusart_main");	
	define("PMA_LINK", "http://vh56.spaceweb.ru/phpMyAdmin/index.php?db=intergrots_artru&token=eeb1e112cdc5e60809e728f199b75dc8");

	define('PICTURE_DIR', './pictures/');	
	define('DOCS_DIR', './html/');		
	#define('SITE_MAIL', 'mail@intergrot-spb.com');
	define('SITE_MAIL', 'serj.afanasenko@yandex.ru');

	define('LANG', 'en');
	define('SITE_TITLE', 'ArtRuGallery: Contemporary Russain artists');
	define('PAGE_TITLE', 'ArtRuGallery: Modern Russian art gallery');
	define('CMD_EXIT', 'Sign Out');
	define('CMD_AUTHORIZATION', 'Sign In');	
	define('CMD_BASKET', 'Chart');
	define('CMD_HOME', 'Home');	
	define('CMD_PAINTINGS', 'Paintings');
	define('CMD_ARTISTS', 'Artists');
	define('CMD_PUBLICATIONS', 'Publications');		
	define('CMD_EXHIBITIONS', 'Exhibitions');			
	define('CMD_SERVICES', 'Services');			
	define('CMD_ABOUT_US', 'About&nbsp;us');	

	define('STR_EMAIL_US', 'E-mail us:');
	define('STR_BLANK_SUBJECT', 'Blank%20subject');
	
	error_reporting(E_ALL);
?>