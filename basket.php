<?php 
	require('./header.php'); 
	require_once('./catalogue_routines.php');		
	
	function check_table($tbl)
	{
		if(mysql_num_rows(mysql_query('SHOW TABLES LIKE \'' . $tbl . '\';'))==1) 
			return True;
		else
			return False;
	}

	$basket = basket_name();
	$cl_name = '';
	$cl_phone = '';
	$cl_mail = '';
	$extra_info = '';

	if (isset($_POST['buy']))
	{
		if (isset($_POST['client_name']))
			$cl_name = $_POST['client_name'];
			
		if (isset($_POST['client_phone']))
			$cl_phone = $_POST['client_phone'];			
			
		if (isset($_POST['client_mail']))
			$cl_mail = $_POST['client_mail'];			
			
		if (isset($_POST['extra_info']))
			$extra_info = $_POST['extra_info'];						
		
		/*
		if (empty($cl_phone)) echo 'True'; else echo 'False';
		if (empty($cl_mail)) echo 'True'; else echo 'False';
		if (empty($cl_name)) echo 'True'; else echo 'False';
		*/
		
		$flag1 = empty($cl_phone);
		$flag2 = empty($cl_mail);
		$flag3 = empty($cl_name);
		
		if ($flag3 or ($flag1 && $flag2))
		{
			echo '<p>' . tr('ERR_USER_CONTACT') . '</p>';
		}
		else
		{
			$mail_body = 'Заказ от: ' . $cl_name . '\n';
			if (!empty($cl_phone))
				$mail_body .= 'Телефон: ' . $cl_phone . '\n';
			if (!empty($cl_mail))
				$mail_body .= 'Электронная почта: ' . $cl_mail . '\n';
			
			$result = mysql_query('SELECT * FROM `' . $basket . '`;');
			if ($result)
			{
				$mail_body .= '\nЗаказ:\n';
				
				while ($row = mysql_fetch_assoc($result))
				{
					$mail_body .= $row['description'] . '\n';
				}
			}			
			
			$mail_body .= '\nДополнительная информация:\n' . $extra_info;
			
			$mail_body_html = str_replace('\n', '</br>', $mail_body);
			echo '<hr></hr><p>' . $mail_body_html . '</p><hr></hr>';			
			
			if (mail(SITE_MAIL, "Заказ на сайте ArtRuGallery", $mail_body))
			{
				if (!mysql_query('DROP TABLE IF EXISTS `' . $basket . '`;'))
					handle_error();
					
				echo '<p>' . $cl_name . ', Ваш заказ принят, мы свяжемся с Вами в ближайшее время.</p>';
			}
			else
			{
				echo '<p>Mail delivery error!</p>';
			}
			
			//$query = 'INSERT INTO `shop_orders`
		}
	}
	else
	{
		if (isset($_POST['cancel_buy']))
		{
			if (!mysql_query('DROP TABLE IF EXISTS `' . $basket . '`;'))
				handle_error();
				
			echo '<p>Заказ отменен, корзина очищена.</p>';
			echo '<script>document.getElementById("basket_label").innerHTML="Корзина (0)";</script>';
		}
		else
		{
			if (isset($_POST['refresh']))
			{
				foreach ($_POST as $name => $val)
				{
					if (!substr_compare($name, 'rm', 0, 2))
					{
						$pid = substr($name, 3, 2);
						if (!mysql_query('DELETE FROM `'. $basket . '` WHERE id_painting=' . $pid . ';'))
							handle_error();
					}
				}
			}
		}
	}
	
	if (check_table($basket))
	{
		echo '<h4>' . tr('Order information') . '</h4>';
	
		echo '<form id="change_order" action="' . $_SERVER['PHP_SELF'] . '" method="POST">';	
		echo '<table class="dataedit">';
		
		$result = mysql_query('SELECT * FROM `' . $basket . '`;');
		if (!$result)
			handle_error();
			
		if (mysql_num_rows($result))
		{
			echo '<tr class="oddrow"><th>' . tr('Description') . '</th><th>' . tr('Price') . ', $</th><th>' . tr('Delete') . '</th></tr>';
			
			$rn = 0;
			$total_price = 0;
			while ($row = mysql_fetch_assoc($result))
			{
				if ($rn % 2)
					echo '<tr class="oddrow">';
				else
					echo '<tr>';
					
				echo '<td>' . $row['description'] . '</td>';
				if (isset($row['price'])) //мало ли?
				{
					echo '<td>' . $row['price'] . '</td>';
					$total_price += $row['price'];
				}
				else
					echo '<td></td>';
				
				echo '<td><input type="checkbox" name="rm_' .  $row['id_painting']. '"></td>';
					
				echo '</tr>';
				$rn++;
			}
			
			echo '<tr class="oddrow"><td>' . tr('TOTAL_PRICE') . '</td><td>' . $total_price . '</td>';
			echo '<td><input type="submit" name="refresh" value="' . tr('Refresh') . '"/></td>';
			echo '</tr>';
		}
		else
		{
			echo '<h4>' . tr('EMPTY_BASKET_MSG') . '</h4>';
		}
		
		echo '</table>';
		echo '</form>';
	
		echo '<form id="make_order" action="' . $_SERVER['PHP_SELF'] . '" method="POST">';

		echo '<h4>' . tr('Your name') . '</h4>';
		echo '<div><input type="text" size="50" maxlength="32" name="client_name" value="' . $cl_name . '"/></div>';		
		echo '<h4>' . tr('Your phone number') . '</h4>';
		echo '<div><input type="text" size="50" maxlength="32" name="client_phone" value="' . $cl_phone . '"/></div>';		
		echo '<h4>' . tr('Your e-mail') . '</h4>';
		echo '<div><input type="text" size="50" maxlength="32" name="client_mail" value="' . $cl_mail . '"/></div>';				
		
		echo '<h4>' . tr('Additional info') . '</h4>';
		echo '<div><textarea rows="4" cols="50" name="extra_info">' . $extra_info . '</textarea></div></br>';			
			
		echo '<table>';
		echo '<tr><td><input type="submit" name="buy" value="' . tr('Make an order') . '"/></td>';
		echo '<td><input type="submit" name="cancel_buy" value="' . tr('Clear your chart') . '"/></td></tr>';
		echo '</table>';
		echo '</form>';
	}
	else
	{
		echo '<p>' . tr('Your chart is empty') . '</p>';
	}
	
	require("./altfooter.php"); 
?>