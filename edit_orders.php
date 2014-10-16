<script>
	function discard_order(order_id) {

		$.post("./shop_request.php", { "order" : order_id, "action" : "discard" }, function(response){
			//console.log(response);
			location.reload();
		});			
	}		
	
	function accept_order(order_id) {
		$.post("./shop_request.php", { "order" : order_id, "action" : "accept" }, function(response){
			//console.log(response);
			location.reload();
		});			
	}	
</script>

<?php 
	require_once("./catalogue_routines.php");	
	require_once("./admin_routines.php");	
	
	if (!is_admin())
		die("This page may be accessed only by administrator");
	
	function get_status_str($s)
	{
		$query = 'SELECT `status_' . current_lang() . '` FROM `order_status_codes` WHERE `id` = ' . $s . ';';
		$res = mysql_query($query);
		if (!$res or mysql_num_rows($res) != 1)
			return 'invalid';
		else
		{
			$row = mysql_fetch_row($res);
			return $row[0];
		}
	}
	
/*	
	$create_stmt = 'CREATE TABLE IF NOT EXISTS `order_status_codes` (`id` int primary key auto_increment, `status_ru` varchar(32), `status_en` varchar(32)); INSERT INTO `order_status_codes` VALUES ( 1,  "Создан", "Сreated" ) , ( 2,  "Принят",  "Accepted" ) , ( 3,  "Выполнен",  "Completed" ) , ( 4,  "Удален",  "Discarded" );';

	$create_stmt = 'CREATE TABLE IF NOT EXISTS `shop_orders` (`id` int primary key auto_increment, `created` timestamp, `status` int, `buyer_user_id` int, `buyer_name` varchar(128), `buyer_phone` varchar(32), `buyer_email` varchar(128));';
*/	

	echo '<h4>' . tr('Orders') . '</h4>';

	# ---------------------------------------------------------------------
	# Заполняем список 
	$result = mysql_query('SELECT * FROM `shop_orders`');			
	if (!$result) {
		die('<p>Неверный запрос: ' . mysql_error() . '</p>');
	}		
	
	echo '<table width="100%" class="dataedit">';
	echo '<tr><td>' . tr('#') . '</td><td>' . tr('Created') . '</td><td>' . tr('Status') . '</td><td>' . tr('Buyer') . '</td><td>' . tr('E-mail') . '</td><td>' . tr('Action') . '</td></tr>';
	$rn = 0;

	
	if (mysql_num_rows($result))
	{
		while ($row = mysql_fetch_assoc($result))
		{
			echo $rn % 2 ? '<tr>' : '<tr class="oddrow">';
			
			echo '<td><a href="./basket.php?order=order_' . $row['id'] . '">' . $row['id'] . '</a></td>';
			echo '<td>' . date('j\.m Y', strtotime($row['created'])) . '</td>';			
			echo '<td>' . get_status_str($row['status']) . '</td>';			
			echo '<td>' . $row['buyer_name'] . '</td>';			
			echo '<td><a href="mailto:' . $row['buyer_email'] . '?Subject=' . tr('BLANK_SUBJECT') . '" target="_top">' . $row['buyer_email'] . '</a></td>';	

			
			echo '<td>';
			if ($row['status'] == 1)
				echo '<button onclick="accept_order(' . $row['id'] . ');">' . tr('Accept') . '</button>';
			
			echo '<button onclick="discard_order(' . $row['id'] . ');">' . tr('Discard') . '</button></td>';
			
			echo '</tr>';
			$rn++;
		}
		
	}
	
	echo '</table>';

?>
