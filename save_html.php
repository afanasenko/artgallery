<?php
require_once('./app_config.php');

/**
 * 
 * @param string $content HTML content
 * @param string $path File you want to write into
 * @return boolean TRUE on success
 */
function save_html_to_file($content, $path)
{
   return (bool) file_put_contents($path, $content);
}

if (isset($_POST['content']) and isset($_POST['token']))
{
	//echo $_POST['content'] . ' ' . $_POST['token'];

	if (save_html_to_file($_POST['content'], $_POST['token']))
	{
		die('0');
	} 
	else 
	{
		die('Server reports an error saving file');
	}
}
else
{
	die('Error in POST parameters');
}

?>