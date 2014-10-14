<?php 
	require("./catalogue_routines.php");	

	if (isset($_POST['action']) and isset($_POST['order']))
	{
		$a = $_POST['action'];
		$id = $_POST['order'];
		if (!strcmp($a, 'discard'))
		{
			$query = 'DROP TABLE IF EXISTS `order_' . $id . '`;';
			$res1 = mysql_query($query);
			
			$query = 'DELETE FROM `shop_orders` WHERE `id` = ' . $id . ';';
			$res2 = mysql_query($query);
			
			if (!$res1 or !$res2)
				die("Неверный запрос: " . mysql_error());									
		}
		elseif (!strcmp($a, 'accept'))
		{
			$query = 'UPDATE LOW_PRIORITY `shop_orders` SET `status` = 2 WHERE `id` = ' . $id . ';';
			mysql_query($query)
				or die("Неверный запрос: " . mysql_error());									
		}
	}
	else
	{
		echo 'error';
	}
?>