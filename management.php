<?php 
	require("./header.php"); 

	echo '<table class="small-menu"><tr>';
		
		echo '<td><a class="small_link" href="' . $_SERVER['PHP_SELF'] . '?page=1">Добавить картину</a></td>';		

		echo '<td><a class="small_link" href="' . $_SERVER['PHP_SELF'] . '?page=2">Добавить публикацию</a></td>';				
/*
		echo '<td><a class="small_link" href="' . $_SERVER['PHP_SELF'] . '?page=3">Добавить художника</a></td>';				
*/		
	echo '</tr></table>';
	
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
		
	if ($page == 1)
	{
		echo '<div>';
		include("./edit_painting.php");
		echo '</div>';
	}
	else if ($page == 2)
	{
		echo '<div>';
		include("./edit_publication.php");
		echo '</div>';	
	}
	else if ($page == 3)
	{
	}
	
	require("./altfooter.php"); 	
?>
