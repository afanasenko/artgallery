				</div><!-- .content-->
			</div><!-- .container-->

		</div><!-- .middle -->

		<div class="footer">
<?php		
			#require_once ("./app_config.php");	
			require_once ("./admin_routines.php");				
			echo '<table width="100%" padding-left="10px"><tr>';
			
	
			echo '<td width="20%" align="left">';
			if (is_admin())
			{
				echo 'Версия PHP: ' . phpversion() . '</br>';
				echo '<a href="' . PMA_LINK . '" target="_blank"><img src="./img/phpmyadmin.ico" width="32px"></a>';
			}
			echo '</td>';
	
			echo '<td width="60%">Напишите нам:</br>';
			echo '<a href="mailto:' . SITE_MAIL . '?Subject=Укажите%20тему" target="_top">' . SITE_MAIL . '</a></td>';
			echo '<td width="20%" align="right">&copy 2014 ArtRuGallery</td>';
			echo '</tr></table>';
?>   		
        </div><!-- .footer -->
        
	</div><!-- .wrapper -->
</body>
</html>