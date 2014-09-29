function show_message(msg) {
	window.alert(msg);
}

function getWidth() {
	if (self.innerWidth) {
	   return self.innerWidth;
	}
	else if (document.documentElement && document.documentElement.clientHeight){
		return document.documentElement.clientWidth;
	}
	else if (document.body) {
		return document.body.clientWidth;
	}
	return 0;
}	

function click_painting(int) {
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// в конец запроса добавляется случайный изменяющийся параметр, иначе вернет закешированный результат
	xmlhttp.open("GET", "./update_stat.php?painting="+int+"&click=1&r="+Math.random(), false);
	xmlhttp.send(null);
}

function buy_painting(int) {
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	//window.alert("./basket_add.php?painting="+int+"&r="+Math.random());
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			var res = xmlhttp.responseText;
			document.getElementById("basket_label").innerHTML='Корзина ('+res+')';
		}
	}	
	
	// в конец запроса добавляется случайный изменяющийся параметр, иначе вернет закешированный результат
	xmlhttp.open("GET", "./basket_add.php?painting="+int+"&r="+Math.random(), true);
	xmlhttp.send(null);
}

function make_vote() {

	var elem = document.getElementById("painting_rating");
	var rating = elem.options[elem.selectedIndex].value;
	
	elem = document.getElementById("pic_id");	
	var pic_id = elem.value;	
	
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		
			var res = xmlhttp.responseText.split(' ');
			res[0] = parseInt(res[0]);
			res[1] = parseInt(res[1]);
			
			var rating = res[0] > 0 ? res[1] / res[0] : 0;
			
			document.getElementById("httpresp").innerHTML='Средняя оценка: '+rating.toFixed(1)+' (голосов: '+res[0]+')';
		}
	}

	// в конец запроса добавляется случайный изменяющийся параметр, иначе вернет закешированный результат
	xmlhttp.open("GET", "./update_stat.php?vote="+rating+"&painting="+pic_id+"&r="+Math.random(), true);
	xmlhttp.send(null);
}