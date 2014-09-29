<?php 
	require_once("./admin_routines.php");		
	require("./header.php");
	
	//FIXME: при ошибках не нарушать скелет страницы!!!
	require_once("./db_connect.php");

	insert_editable_block('editable', './html/services.html');
	
	require("./altfooter.php"); 
?>