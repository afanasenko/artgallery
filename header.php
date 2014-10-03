<?php
	require_once('./catalogue_routines.php');
	require_once('./admin_routines.php');	
	require_once('./translation.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo tr('SITE_TITLE'); ?></title>
	<link href="/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	<script type="text/javascript" src="js/common.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="./js/jquery_cookie.js" charset="utf-8"></script>
</head>

<body>

	<script>
		function SetLang(lang) {
			console.log(lang);
			
			if (lang=='ru')
				$.cookie('language', 'ru', {expires : 365});
			else
				$.cookie('language', 'en', {expires : 365});
			
			location.reload(true);
		}
	</script>

	<div class="wrapper">        
		<div class="header">

			<table class = "title_block">
			  <tr>
        	    <td width="128px"></td>
        	    <td width="70%"><h3><?php echo tr('PAGE_TITLE'); ?></h3></td>	
<?php
	echo '<td>';
	echo '<a href=#><img src="./img/flag_rus.jpg" width="32px" id="switch_russian" onclick="SetLang(\'ru\');"></img></a>';
	echo '<a href=#><img src="./img/flag_uk.jpg" width="32px" id="switch_english" onclick="SetLang(\'en\');"></img></a>';	
	echo '</td>';
	echo '<td style="background: white">';

	# для администраторов даем ссылку на редактирование
	if (isset($_COOKIE['username']))
	{
		echo $_COOKIE['username'];
		echo '<a class="small_link" href="./switch_user.php">' . tr('CMD_EXIT') . '</a></br>';
	}
	else
	{
		echo '<a class="small_link" href="./switch_user.php">' . tr('CMD_AUTHORIZATION') . '</a></br>';
	}
	
	$basket = basket_name();
	echo '<a class="small_link" href="./basket.php" id="basket_label">'. tr('Chart') . ' (' . count_elements($basket, '', '') . ')</a></br>';
	
	echo '</td>';
	echo '</tr>';
	echo '</table>';

    echo '<ul class="menu-1">';

	//FIXME: изучить регулярные выражения!
	$pieces = explode("/", $_SERVER['REQUEST_URI']);
	$pieces = explode("?", end($pieces));
	$pieces = explode(".", $pieces[0]);
	
	$sel[$pieces[0]] = 'class = "selected"';
	
	echo('<li><a href="./news.php"' . $sel["news"] . '>' . tr('CMD_HOME') . '</a></li>');
	echo('<li><a href="./catalogue.php"' . $sel["catalogue"] . '>' . tr('CMD_PAINTINGS') . '</a></li>');
	echo('<li><a href="./artists.php"' .  $sel["artists"] . '>' . tr('CMD_ARTISTS') . '</a></li>');
	# echo('<li><a href="./exhibitions.php"' . $sel["exhibitions"] . '>' . tr('Exhibitions') . '</a></li>');
	echo('<li><a href="./publications.php"' . $sel["publications"] . '>' . tr('Publications') . '</a></li>');	
	echo('<li><a href="./services.php"' . $sel["services"] . '>' . tr('CMD_SERVICES') . '</a></li>');		
	echo('<li><a href="./contacts.php"' . $sel["contacts"] . '>' . tr('CMD_ABOUT_US') . '</a></li>');
	
	if (is_admin())
		echo('<li><a href="./management.php"' . $sel['management'] . '>' . tr('MANAGE') . '</a></li>');
		
	echo('</ul>');
?>
		</div><!-- .header-->

		<div class="middle">
			<div class="container">
				<div class="content">