<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
  		<title><?php echo inst::accion()->get_nombre(); ?> de <?php echo inst::configuracion()->get('proyecto','nombre');?></title>
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
		<script type="text/javascript" src='recursos/jquery.js'></script>
 	</head> 	
 	<body>
	    <table id="box" cellspacing="0">
      		<tr>
   			<td colspan="3" class='logo'>
      			    	<a href="<?php echo inst::configuracion()->get('instalador', 'logo_url'); ?>" title='Ir a la página'>
      			    	<img src="<?php echo inst::configuracion()->get_url_logo_aplicacion(); ?>" border= "none" 
      			    			alt="<?php echo inst::configuracion()->get('proyecto', 'nombre'); ?>"/></a>
      			    			
      			    	<span class='logo-version'><?php echo inst::configuracion()->get('proyecto', 'version'); 
      			    			$fantasia = inst::configuracion()->get('proyecto', 'version_fantasia', null, false);
      			    			if (isset($fantasia)) {
      			    				echo '<span> - '.$fantasia.'</span>';
      			    			}
      			    	?>
      			    	
      			    	</span>
      			    	
		      	</td>
		    </tr>

		    <tr>
			    <td bgcolor="#F1E5DD" height="1" colspan="3" align="left" valign="top"></td>
		    </tr>
			<tr>
				<td id="navbar">
					<div>
						<table cellspacing="0">
		    			<?php if (inst::accion()->mostrar_pasos()) { ?>						
		  					<tr>
		  						<td class="heading" colspan="2">Pasos a seguir</td>
		  					</tr>
		  					<?php
								inst::accion()->generar_html_pasos();
		  					?>
		  					<?php } ?>
		  					<?php if(inst::paso()->tiene_ayuda()): ?>
							<tr class="help">
								<td><span class="helpBox">?</span></td>
								<td>
									<b><a href="<?php echo inst::paso()->get_link_ayuda(); ?>" target="_blank">Ayuda</a></b>
								</td></tr>
							<?php endif; ?>			
							<tr class="help">
								<td><span class="helpBox">@</span></td>
								<td>
									<b><a href="<?php echo inst::controlador()->get_link_diagnostico(); ?>" title="Permite generar un archivo de diagnostico listo para enviar al grupo de desarrollo" target="_blank">Diagnosticar Problemas</a></b>
								</td></tr>
		  					<tr>
		  						<td><span class="helpBox">&lt;</span></td>
		  						<td><a href="?reiniciar=1">Ir a página inicial</a></td>
		  					</tr>
						</table>
      				</div>
     				
      			</td>
     			
      			<td id="main" colspan="2">
					<?php inst::paso()->generar(); ?>
      			</td>      			
			</tr>

      		<tr>
      			<td id="bottom" colspan="3">
					<h3>Estado de la <?php echo inst::accion()->get_nombre(); ?></h3>
					<table id="statusTable" cellspacing="0">
	  					<tr>
	  						<?php $porcentaje = inst::accion()->get_porcentaje(); ?>
						    <?php if ($porcentaje != 0): ?>
						    	<td class="progressMade" style="width:<?=$porcentaje; ?>%"><h3>&nbsp;</h3></td>
						    <?php endif; ?>
					
						    <?php if ($porcentaje != 100): ?>
						    	<td class="progressToGo" style="width:<?php print (100 - $porcentaje) . '%' . (($porcentaje == 0) ? '' : ';border-left:none') ?>"><h3>&nbsp;</h3></td>
						    <?php endif; ?>

	  					</tr>
					</table>
					Completado el <strong><?php echo inst::accion()->get_porcentaje(); ?>%</strong> del proceso de <?php echo strtolower(inst::accion()->get_nombre()); ?>
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
