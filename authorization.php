<?php
	require_once "./db_connect.php";
	$_GET['error'] = 5;				
	// Если пользователь зарегистрировался, будет установлен cookie-файл username
	if (!isset($_COOKIE['username'])) 
	{
		$_GET['error'] = 1;				
		// Проверка отправки формы регистрации с username для входа в приложение
		if (isset($_POST['username'])) 
		{
			// Попытка зарегистрировать пользователя
			//$username = mysql_real_escape_string(trim($_REQUEST['username']));
			//$password = mysql_real_escape_string(trim($_REQUEST['password']));
			
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			
			// Поиск пользователя

			$query = 'SELECT * FROM users WHERE username = "' . $username . '";';
								
			$results = mysql_query($query);
			if (mysql_num_rows($results) == 1) 
			{
				$result = mysql_fetch_assoc($results);
				
				if (!strcmp($result['password'], $password))
				{
					$cookietime = time() + 72*3600;
					
					setcookie('username', $result['username'], $cookietime);
					setcookie('access_level', $result['access_level'], $cookietime);
					$_GET['error'] = 0;
				}
				else
				{
					$_GET['error'] = 2;				
				}
			} 
			else 
			{
				// Если пользователь не найден, выдача ошибки
				$_GET['error'] = 1;
			}			
		}
		
		if ($_GET['error'] != 0 or !isset($_POST['username']))
		{
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>ArtRuGallery: Современные русские художники</title>
	<link href="./css/style.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
	
	<script type="text/javascript" src="./js/jquery-1.10.2.min.js" charset="utf-8"></script>	
	<script>
		//---------------------------------------------------
		$( document ).ready(function() {
			document.getElementById("username").focus();
		});	
	</script>	
	
</head>

<body>
	<div id="content">
		<div class="auth-form">
		
			<table>
			  <tr>
				<td><img src="/img/logo.png" width="64px" height="64px"></td>
				<td><h3>Галерея современного русского искусства ArtRuGallery</h3></td>			  
			  </tr>
			</table>		
		
			<form id="signin_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
				<h4>Авторизация</h4>
				<div class="field">
					<label for="username">Имя пользователя:</label>
					<input type="text" name="username" id="username" size="20"/>
				</div>
				<div class="field">
					<label for="password">Пароль:</label>
					<input type="password" name="password" id="password" size="20"/>
				</div>

				<div class="field">
					<input type="submit" value="Войти" />
				</div>
				
				<?php 
					switch ($_GET['error'])
					{
						case 1:
							echo "Пользователь с таким именем не зарегистрирован";
							break;
						case 2:
							echo "Введен неверный пароль";
							break;
					}			
				?>				
			</form>
		</div>
	</div>

<?php
			//die('Authentication result: ' . $_GET['error']);
			die('');
		}
		else
			header('Location: ./news.php'); 
	}
	else 
	{
		// Обработка случая, когда зарегистрировавшийся пользователь перенаправляется на другую страницу,
		echo 'Wait...';
		header('Location: ./news.php'); 
	}
?>

</body>
</html>