<?php 
	require("./header.php"); 
	require_once("./translation.php");		
	$is_english = !strcmp(current_lang(), 'en');	
	
	if ($is_english)
	{
		echo('<h4>Location:</h4>');
		echo('<p>20 "L" Kamskaya st., Saint Petersburg, Russia</p>');

		echo('<h4>Phone number:</h4>');
		echo('<p>8(812) 958-10-36</p>');	
		echo('<h4>E-mail:</h4>');			
	}
	else
	{
		echo('<h4>Наш адрес:</h4>');
		echo('<p>г. Санкт-Петербург, ул. Камская, д. 20, лит. "Л"</p>');

		echo('<h4>Телефон для связи:</h4>');
		echo('<p>8(812) 958-10-36</p>');

		echo('<h4>Электронная почта:</h4>');
	}
	
	echo '<p><a href="mailto:' . SITE_MAIL . '?Subject=' . tr('BLANK_SUBJECT') . '" target="_top">' . SITE_MAIL . '</a></p>';
	
	echo('<h4>' . tr('Map:') . '</h4>');
?>
	
<script type="text/javascript" charset="utf-8" src="//api-maps.yandex.ru/services/constructor/1.0/js/?sid=OCgKoAUwLr_lmxtctnPhG6LbU9NP5mf5&width=600&height=450"></script>

<div id="map_canvas" style="width:300px; height:300px;">
 
<script type="text/javascript"> 
var map = null;// объект карты 
var center = new google.maps.LatLng( 55.201612,61.43839 );// точка-Центр карты 
var options = {// опции создаваемой карты 
  zoom: 10,// масштаб карты 
  center: center,// координата центра карты 
  mapTypeId: g.MapTypeId.ROADMAP// тип карты 
}; 
//----- Функция инициализации Карты ----- 
function initialize() { 
  map = new google.maps.Map(document.getElementById("map_canvas"), options);// Создаем объект карты 
}
</script>

</div>


<?php 
	require("./footer.php"); 
?>