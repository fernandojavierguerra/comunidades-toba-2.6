<?php
	if (isset($_GET['phpinfo'])) {
		phpinfo();
		return;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en_US" xml:lang="en_US">

	<head>
  		<title>Diagnostico del sistema</title>
	    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    	<link rel="stylesheet" type="text/css" href="recursos/instalador.css"/>
	<?php 
		$css_proyecto = inst::configuracion()->get('proyecto', 'css',null, false);
		if (! is_null($css_proyecto)) {
			$css_proyecto =  inst::configuracion()->get_url_inst_aplicacion(). '/'.$css_proyecto;
			echo '<link rel="stylesheet" type="text/css" href="'.$css_proyecto.'"/>';
		}
	?>    	
 	 <script type="text/javascript" src='recursos/instalador.js'></script>
 	</head> 	
 	<body>
	    <table id="box" cellspacing="0">
      		<tr>
   			<td colspan="3">
      			    	<a href="<?php echo inst::configuracion()->get('instalador', 'logo_url'); ?>" title='Ir a la página'>
      			    	<img class='logo' src="<?php echo inst::configuracion()->get_url_logo_aplicacion(); ?>" border= "none" 
      			    			alt="<?php echo inst::configuracion()->get('proyecto', 'nombre'); ?>"/></a>
		      	</td>
		    </tr>

		    <tr>
			    <td bgcolor="#F1E5DD" height="1" colspan="3" align="left" valign="top"></td>
		    </tr>
		    
			<tr>
				<td id="navbar">
					<div>
						<table cellspacing="0">
		  					<tr>
		  						<td class="heading" colspan="2">Pasos a seguir</td>
		  					</tr>
		  					<?php
								inst::accion()->generar_html_pasos();
		  					?>
		  					<?php if(inst::paso()->tiene_ayuda()): ?>
							<tr class="help">
								<td><span class="helpBox">?</span></td>
								<td>
									<b><a href="<?php echo inst::paso()->get_link_ayuda(); ?>" target="_blank">Ayuda</a></b>
								</td></tr>
							<?php endif; ?>			
						</table>
      				</div>
      			</td>
      			
      			<td id="main" colspan="2">
					<?php inst::paso()->generar(); ?>
      			</td>      			
			</tr>
		</table>
		
		<div id="footer">
			<strong><?php echo inst::configuracion()->get('proyecto','nombre'); ?></strong>: <?php echo inst::configuracion()->get('proyecto','descripcion'); ?>    	
		</div>		
		
		<div id='capa_espera'>
			<div>
					<img src='<?php echo inst::configuracion()->get_url_logo_aplicacion(); ?>' />
					<p>Procesando. Por favor aguarde...</p>
					<img src='recursos/wait.gif'>
			</div>
		</div>		
		
 	</body>
</html>
