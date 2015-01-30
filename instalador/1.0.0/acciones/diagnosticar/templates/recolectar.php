<h1>Servicios a Diagnosticar</h1>

<?php
if (!extension_loaded('zip')) {
	echo "<br>Para poder obtener información de diagnósito el sistema requiere que PHP tenga la librería ZIP para la escritura de archivos comprimidos.";	
	if (INST_ES_WINDOWS) {
		echo  " Puede habilitarla ejecutando nuevamente el instalador .msi de php, seleccionar la extension ZIP";							
	} else {
		echo " Para sistemas debian ejecutar como root <pre>apt-get install php5-zip</pre>";	
	}	
	echo ' y reiniciar luego el servidor web.';
	?> 
	<div class="go">
			<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_actual();?>">Chequear Nuevamente</a></span>
	</div>
	<?php
	return;
}
?>
<br>
Para poder hacer un diagnóstico más exacto el asistente necesita conocer la ubicación de las configuraciones de los distintos servicios.
En caso de no querer enviar configuraciones o logs de algún servicio por favor deje el campo vacío.
<?php
$datos = inst::paso()->get_directorios();
?>
<form method="post" action="<?php echo inst::accion()->get_url();?>" onsubmit='return esperar_operacion(this)'>
<br>
<table>
<tr>
	<td class="label"><label for='path_instalador'>Logs del Instalador</label>
	</td>
	<td colspan=2><input id="path_instalador" name="path_instalador" type="text" size="50" value="<?php echo $datos['path_instalador']; ?>"></td>
</tr>
<tr>
	<td class="label"><label for='path_conf_toba'>Configuración y Logs de Toba</label>
	<div class='aclaracion'>Carpeta instalacion de toba, contiene los archivos instalacion.ini y bases.ini</div></td>
	<td colspan=2><input id="path_conf_toba" name="path_conf_toba" type="text" size="50" value="<?php echo $datos['path_conf_toba']; ?>"></td>
</tr>

<tr>
	<td class="label"><label for='path_conf_php'>Configuración de PHP</label>
		<div class='aclaracion'>Carpeta con el archivo php.ini</div></td>
	<td colspan=2><input id="path_conf_php" name="path_conf_php" type="text" size="50" value="<?php echo $datos['path_conf_php']; ?>"></td>
</tr>
<tr>
	<td class="label"><label for='path_conf_apache'>Configuración de Apache</label>
		<div class='aclaracion'>Carpeta con el archivo httpd.conf</div></td>
	<td colspan=2><input id="path_conf_apache" name="path_conf_apache" type="text" size="50" value="<?php echo $datos['path_conf_apache']; ?>"></td>
</tr>
<tr>
	<td class="label"><label for='path_logs_apache'>Logs de Apache</label>
		<div class='aclaracion'>Carpeta con archivos error.log y access.log</div></td>
	<td colspan=2><input id="path_logs_apache" name="path_logs_apache" type="text" size="50" value="<?php echo $datos['path_logs_apache']; ?>"></td>
</tr>

<tr>
	<td class="label"><label for='path_conf_postgres'>Configuración de Postgresql</label>
		<div class='aclaracion'>Carpeta con archivos pg_hba.conf y postgresql.conf</div></td>
	<td colspan=2><input id="path_conf_postgres" name="path_conf_postgres" type="text" size="50" value="<?php echo $datos['path_conf_postgres']; ?>"></td>
</tr>
<tr>
	<td class="label"><label for='path_logs_postgres'>Logs de Postgresql</label>
		<div class='aclaracion'></div></td>
	<td colspan=2><input id="path_logs_postgres" name="path_logs_postgres" type="text" size="50" value="<?php echo $datos['path_logs_postgres']; ?>"></td>
</tr>

</table>

<div class="go">
	<span class="goToNext">
		<a href="javascript:if(document.forms[0].onsubmit()) document.forms[0].submit()">Recolectar Información&raquo;</a>
	</span>
</div>
