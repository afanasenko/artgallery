<?php
	require('./header.php'); 
	
	$is_english = !strcmp(current_lang(), 'en');
?>

<script>
	function buy_painting(int) {
	
		var url = "./basket_add.php";		
		$.post(url, { "painting" : int, "r" : Math.random() }, function(response){
			console.log(response);
			$("#basket_label").html('<?php echo tr('Chart'); ?> ('+response+')');
		});			
	}	
</script>

<?php
	if (!isset($_GET['painting']))
	{
		die('Внутренняя ошибка');
	}
	
	$pic_id = $_GET['painting'];
	
	echo '<table class="pic-nav"><tr><td align="left">';
	
	$res = mysql_query('SELECT id_painting FROM paintings WHERE id_painting < ' . $pic_id . ' ORDER BY id_painting DESC LIMIT 1;');
	if ($result && mysql_num_rows($res)) 
	{
		$row = mysql_fetch_row($res);		
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?painting=' . $row[0] . '" onclick="click_painting(' . $row[0] . ');">&#8592;' . tr('Previous') . '</a>';		
	}
	
	echo '</td><td align="right">';
	
	$res = mysql_query('SELECT id_painting FROM paintings WHERE id_painting > ' . $pic_id . ' ORDER BY id_painting LIMIT 1;');
	if ($result && mysql_num_rows($res)) 
	{
		$row = mysql_fetch_row($res);		
		echo '<a href="' . $_SERVER['PHP_SELF'] . '?painting=' . $row[0] . '" onclick="click_painting(' . $row[0] . ');">' . tr('Next') . '&#8594;</a>';			
	}

	echo '</td></tr></table>';
	
	$result = mysql_query('SELECT * from paintings WHERE id_painting = '. $pic_id . ';');
	
	if ($result) 
	{
		$pic = mysql_fetch_assoc($result);
		
		$painter = get_artist_name_short($pic['id_artist']);
		$picname = $is_english ? $pic['name_painting_en'] : $pic['name_painting'];
		
		echo "<h4>{$painter}. {$picname}</h4>";	
		echo '<table class="picpage"><tr><td>' . picture_description($pic_id) . '</td>';

		# для администраторов даем ссылку на редактирование
		if (is_admin())
			echo '<td><a href="./management.php?page=1&painting=' . $pic_id . '">' . tr('Edit') . '</a></td>';		
		
		echo '</tr>';
		
		if ($pic['on_sale'])
		{
			if ($pic['sale_status'] == 0 or $pic['sale_status'] == 1)
				$btn_text = tr('Buy');
			else
				$btn_text = tr('Order a copy');
				
			if (isset($pic['price']) and $pic['price'] > 0)
				$price_str = $pic['price'] . '$';
			else
				$price_str = tr('Call');
				
			echo '<tr><td>' . tr('Price: ') . $price_str . '</td><td><input type="button" id="add-to-chart" name="buy" value="' . $btn_text . '" onclick="buy_painting(' . $pic_id . ');"/></td></tr>';
		}
		
		echo '</table>';
				
		$filename = PICTURE_DIR . $pic['token'];
			
		if (file_exists($filename))		
		{
			echo '<div class="img_medium">';		
			echo '<a target="_blank" href="' . $filename . '"><img src="' . $filename . '" width="60%"></a></br>';
			echo '</div>';		
		}
		else
			echo '<p>Файл ' . $filename . ' не найден на сервере</p>';		
		
	}	
	else
		echo 'Ошибка при выполнении SQL-запроса: ' . mysql_error();	
		
	$result = mysql_query('SELECT * FROM paintings_stat WHERE id_painting = ' . $pic_id . ';');
	if ($result)
	{
		$picstat = mysql_fetch_assoc($result);
		if (!$picstat)
			$picstat = array();

		if (!isset($picstat['num_clicks']))
			$picstat['num_clicks'] = 0;

		if (!isset($picstat['num_rates']))
			$picstat['num_rates'] = 0;
			
		if (!isset($picstat['total_rate']))
			$picstat['total_rate'] = 0;			
		
		$rating = $picstat['num_rates'] > 0 ? (float)$picstat['total_rate']/$picstat['num_rates'] : 0;
		$rating = number_format($rating, 1, '.', '');
			
		echo '<input type="hidden" id="pic_id" value="' . $pic_id  . '">';						
			
		echo '<table class="picpage">';
		
		if (isset($pic['dt_added']))
		{
			$picdate = date('j\.m Y', strtotime($pic['dt_added']));
			echo '<tr><td>' . tr('Added to catalogue ') . $picdate . '</td></tr>';
		}
		
		echo '<tr><td>' . tr('Watched: ') . $picstat['num_clicks'] . '</td></tr>';		
		echo '<tr><td id="httpresp">' . tr('Average score: ') . $rating . ' (' . tr('votes: ') . $picstat['num_rates'] . ')</td>';
		echo '<td>' . tr('Your score: ');
		
		echo '<select name="rating" id="painting_rating">';
		$default_score = 3; # будет выбрана по умолчанию
		for ($i = 1; $i <= 5; $i++) 
		{
			echo $i;
			if ($i == $default_score)
				echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			else
				echo '<option value="' . $i . '">' . $i . '</option>';
		}		
		echo '</select>';
		echo '&nbsp;<button onclick="make_vote();">' . tr('Vote!') . '</button></td></tr>';
		
		# администратору надо дать кнопку сброса статистики

		echo '</table>';
	}

	require("./footer.php"); 	
?>