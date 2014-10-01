<?php
	require_once('./app_config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?></title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js" charset="utf-8"></script>
</head>

<body>

	<div class="wrapper">        
		<div class="header">
			<table class = "title_block">
			  <tr>
        	    <td><img src="./img/header_left.png" height="64px"></td>
        	    <td width="70%"><h3><?php echo PAGE_TITLE; ?></h3></td>	
<?php
	echo '<td>';
	echo '<img src="./img/flag_rus.jpg" width="32px"><a href="' . $_SERVER['REQUEST_URI'] . '"></a>';
	echo '<img src="./img/flag_uk.jpg" width="32px"><a href="' . $_SERVER['REQUEST_URI'] . '"></a></br>';
	echo '</td>';
?>				
				<td>
<?php
	require_once('./catalogue_routines.php');
	require_once('./admin_routines.php');
	
	# для администраторов даем ссылку на редактирование
	if (isset($_COOKIE['username']))
	{
		echo $_COOKIE['username'];
		echo '<a class="small_link" href="./switch_user.php">' . CMD_EXIT . '</a></br>';
	}
	else
	{
		echo '<a class="small_link" href="./switch_user.php">' . CMD_AUTHORIZATION . '</a></br>';
	}
	
	$basket = basket_name();
	echo '<a class="small_link" href="./basket.php" id="basket_label">'. CMD_BASKET . ' (' . count_elements($basket, '', '') . ')</a></br>';
	
?>
				</td>
				<td><img src="./img/header_right.png" height="64px"></td>
			  </tr>
			</table>

        	<ul class="menu-1">
<?php
	//FIXME: изучить регулярные выражения!
	$pieces = explode("/", $_SERVER['REQUEST_URI']);
	$pieces = explode("?", end($pieces));
	$pieces = explode(".", $pieces[0]);
	
	$sel[$pieces[0]] = 'class = "selected"';
	
	echo('<li><a href="./news.php"' . $sel["news"] . '>' . CMD_HOME . '</a></li>');
	echo('<li><a href="./catalogue.php"' . $sel["catalogue"] . '>' . CMD_PAINTINGS . '</a></li>');
	echo('<li><a href="./artists.php"' .  $sel["artists"] . '>' . CMD_ARTISTS . '</a></li>');
	# echo('<li><a href="./exhibitions.php"' . $sel["exhibitions"] . '>' . CMD_EXHIBITIONS . '</a></li>');
	echo('<li><a href="./publications.php"' . $sel["publications"] . '>' . CMD_PUBLICATIONS . '</a></li>');	
	echo('<li><a href="./services.php"' . $sel["services"] . '>' . CMD_SERVICES . '</a></li>');		
	echo('<li><a href="./contacts.php"' . $sel["contacts"] . '>' . CMD_ABOUT_US . '</a></li>');
	
	if (is_admin() and !strcmp(LANG, 'ru'))
		echo('<li><a href="./management.php"' . $sel['management'] . '>Управление</a></li>');
?>
		</ul>
		</div><!-- .header-->

		<div class="middle">
			<div class="container">
				<div class="content">