<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
  		<title><?php echo inst::configuracion()->get('proyecto','nombre');?></title>
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
      			<td id="main">
      				<div style='margin-left: 100px;margin-right:100px'>
Bienvenido al programa de instalaci&oacute;n del sistema <b><?php echo inst::configuracion()->get('proyecto','nombre').' '.inst::configuracion()->get('proyecto','version'); ?></b>.
Por favor seleccione la acción a realizar:<br><br>      				
      					<div style='margin-left: 120px;margin-right:120px'>
		      			<?php foreach(inst::configuracion()->get_lista('instalador', 'acciones') as $id_accion){ 
		      					$clase = 'accion_'.$id_accion;
		      				?>
							<div class="go">
								<span class="goToNext">
									<a href="?accion=<?php echo $id_accion; ?>"><?php echo call_user_func(array($clase, 'get_descripcion')); ?></a>
								</span>
							</div>										
						<?php }; ?>
						</div>
					</div>
      			</td>
      		</tr>  		    
		</table>
		
		<div id="footer">
			<strong><?php echo inst::configuracion()->get('proyecto','nombre'); ?></strong>: <?php echo inst::configuracion()->get('proyecto','descripcion'); ?>    	
		</div>		
 	</body>
</html>
