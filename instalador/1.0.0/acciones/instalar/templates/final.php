<?php
if (isset($_SESSION['servicios_web_desactivados']) && ! empty($_SESSION['servicios_web_desactivados'])) {
?>
<h1>Servicios Web</h1>
<p>
	Los siguientes Servicios Web han sido desactivados por defecto, por favor active aquellos que considere necesarios y configurelos apropiadamente.<br>
	<ul>
	<?php
		foreach($_SESSION['servicios_web_desactivados'] as $servicio) {
			echo "<li> {$servicio} </li>";
		}
	?>	
	</ul>	
</p>
<?php
}
?>
<h1>Fin de la Instalaci&oacute;n</h1>

<p>
	La instalaci&oacute;n ha finalizado satisfactoriamente!. Dentro del sistema encontrar&aacute; documentaci&oacute;n sobre el uso apropiado del mismo.<br>
	<ul>
<?php
	foreach (inst::configuracion()->get('foro_contactos', null, array(), false) as $foro => $valor)
	{
		$datos =  array_map('trim', explode(',', $valor));
		echo "<li>$datos[1]:<br><span class='goToNext'> <a target='_blank' href=$datos[0]>$datos[0]</a></span></li>\n";
	}
?>
	</ul>
	<ul>
<?php
foreach (inst::configuracion()->get('mails_contacto') as $mail => $texto) {
	echo "<li>$texto:<br> <a href='mailto:$mail'>$mail</a></li>\n";
}
?>
	</ul>
</p>
<?php
$datos = inst::configuracion()->get_info_instalacion(); 
?>
Si lo desea, puede presionar sobre el v&iacute;nculo que est&aacute; a continuaci&oacute;n para comenzar a utilizar el sistema.<br>
<?php if(! inst::paso()->tiene_errores()): ?>
	<div class="go">
		<span class="goToNext">
			<a target='_blank' href="<?php echo $datos['sistema']['url']; ?>">Navegar hacia <?php echo inst::configuracion()->get('proyecto','nombre'); ?> &raquo;</a></span>
	</div>
<?php endif; ?>

<p>
	<br>
Por motivos de seguridad es recomendable que una vez comprobado el correcto funcionamiento del sistema elimine el actual instalador, ya que de otra forma el mismo queda
a libre acceso de cualquier usuario. Esta tarea la puede hacer manualmente eliminando el directorio <pre><?php echo INST_DIR; ?></pre>  o a través del siguiente vínculo
	</br>
</p>
	<div class="go">
		<span class="goToNext">
			<a href="?eliminar=1" onclick="return eliminar_instalador()">Eliminar instalador (recomendado)</a>
		</span>
	</div>
<p>
	
	<b>Muchas gracias por haber instalado el sistema <?php echo inst::configuracion()->get('proyecto','nombre'); ?>. Que lo disfrute!</b>
</p>
