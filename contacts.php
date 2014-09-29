<?php 
	#require_once("./authorization.php");	
	require_once ("./app_config.php");	
	require("./header.php"); 
?>

	<h4>Наш адрес:</h4>		
	<p>г. Санкт-Петербург, ул. Камская, д. 20, лит. "Л"</p>
	
	<h4>Телефон для связи:</h4>		
	<p>8(812) 958-10-36</p>	

	<h4>Электронная почта:</h4>		
<?php	
	echo '<p><a href="mailto:' . SITE_MAIL . '?Subject=Укажите%20тему" target="_top">' . SITE_MAIL . '</a></p>';
?>
	
	<h4>Карта проезда:</h4>	
	
	
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
	require("./altfooter.php"); 
?>