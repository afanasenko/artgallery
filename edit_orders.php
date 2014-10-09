<?php 
	require_once("./catalogue_routines.php");	
	require_once("./admin_routines.php");	
/*	
	$create_stmt = 'CREATE TABLE IF NOT EXISTS `order_status_codes` (`id` int primary key auto_increment, `status_ru` varchar(32), `status_en` varchar(32)); INSERT INTO `order_status_codes` VALUES ( 1,  "Сreated",  "Создан" ) , ( 2,  "Принят",  "Accepted" ) , ( 3,  "Выполнен",  "Completed" ) , ( 4,  "Удален",  "Discarded" );';

	$create_stmt = 'CREATE TABLE IF NOT EXISTS `shop_orders` (`id` int primary key auto_increment, `created` timestamp, `status` int, `buyer_user_id` int, `buyer_name` varchar(128), `byuer_phone` varchar(32), `buyer_email` varchar(128));';
*/	

	echo '<h4>' . tr('Orders') . '</h4>';

	# ---------------------------------------------------------------------
	# Заполняем список 
	$result = mysql_query('SELECT * FROM shop_orders');			
	if (!$result) {
		die('<p>Неверный запрос: ' . mysql_error() . '</p>');
	}		
	
	echo '<table width="100%" class="dataedit">';
	echo '<tr><td>' . tr('#') . '</td><td>' . tr('Created') . '</td><td>' . tr('Status') . '</td><td>' . tr('Action') . '</td></tr>';
	$row = 0;

	
	if (mysql_num_rows($result))
	{
		while ($row = mysql_fetch_row($result))
		{
			echo $row % 2 ? '<tr>' : '<tr class="oddrow">';
			
			foreach ($row as $val)
				echo '<td>' . $val . '</td>';
			
			echo '<td></td>';
			
			echo '</tr>';
			$row++;
		}
		
	}
	
	echo '</table>';

?>
