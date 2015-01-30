<h1>Resguardo</h1>
<p>
Antes de realizar cambio alguno en su sistema es altamente recomendable que realice una copia de seguridad de código, configuraciones y datos existentes. 
</p>
<?php 
$datos = inst::configuracion()->get_info_instalacion(); 
?>
<ul>
<li><strong>Datos</strong>: Se recomienda resguardar la base de datos <em><?php echo $datos['base']['base']; ?></em> del servidor 
			<em><?php echo $datos['base']['profile']; ?> </em>. Una forma sencilla de hacerlo es utilizando la utilidad pg_dump de postgres, 
			el siguiente comando deja una copia de seguridad en el archivo <?php echo $datos['base']['base']; ?>.backup: 
			
			<pre>
  pg_dump -f <?php echo $_SESSION['path_instalacion'].INST_SEP_CARP.'instalacion'.INST_SEP_CARP.$datos['base']['base']; ?>.backup -Ft -h <?php echo $datos['base']['profile']; ?> <?php echo $datos['base']['base']; ?>
			</pre>
</li>
<li><strong>Código y Configuraciones</strong>: Resguardar el contenido de la carpeta <em><?php echo $_SESSION['path_instalacion']; ?></em></li>
</ul>

<?php if(inst::paso()->tiene_errores()) {
	inst::paso()->generar_html_errores();
	inst::scroll_fondo();
} ?>
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_siguiente();?>">Ir al paso siguiente</a></span>
</div>
