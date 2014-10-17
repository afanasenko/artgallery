<?php 
	require_once("./admin_routines.php");		
	require("./header.php");
	
	$is_english = !strcmp(current_lang(), 'en');	
	if ($is_english)	
		insert_editable_block('editable', './html/en_services.html');
	else
		insert_editable_block('editable', './html/ru_services.html');	
	
	require("./footer.php"); 
?>