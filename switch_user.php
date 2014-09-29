<?php
	# чтобы сбросить кукисы, необходимо установить им уже прошедшее время
	$cookietime = time() - 72*3600;
	
	setcookie('username', '', $cookietime);
	setcookie('access_level', '', $cookietime);
	header('Location: ./authorization.php'); 
?>
