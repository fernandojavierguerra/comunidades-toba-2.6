<?php
if (isset($_SESSION['errores_perfiles']) && ! empty($_SESSION['errores_perfiles'])) {
?>
<h1>Cambios en Perfiles</h1>
<p>
Algunas propiedades de los perfiles personalizados en el sistema no pudieron ser aplicadas a la nueva versión. Generalmente esto
sucede porque alguna operación, pantalla o campo existente en la versión anterior no forman parte de la nueva por lo que se actualizó automáticamente 
el perfil para evitar inconsistencias. A continuación el detalle de las propiedades:
</p>
<script type="text/javascript">
$(document).ready(function()
{
  $(".cuerpo").hide();
  $(".asunto").click(function()
  {
    $(this).next(".cuerpo").slideToggle();
	if ($(this).children(".operacion").text() == "[+]") {
		$(this).children(".operacion").text("[-]");
	} else {
		$(this).children(".operacion").text("[+]");
	}
  });
});
</script>
<div class="mensajes">
<?php
 	foreach ($_SESSION['errores_perfiles'] as $error) {
 		$titulo = isset($error['extra']) ? $error['extra'] : $error['tabla'];
?>
		<p class="asunto"><?php echo $titulo; ?><span class='operacion' style='float:right;'>[+]</span></p>
		<div class="cuerpo">
			<ul>
				<li><b>SQL:</b> <?php echo $error['sql']; ?></li>
				<li><b>Mensaje:</b> <?php echo $error['msg_motor']; ?></li>
			</ul>
		</div>
<?php } ?>
</div>
<br /><br />
<?php
}

if (isset($_SESSION['servicios_web_desactivados']) && ! empty($_SESSION['servicios_web_desactivados'])) {
?>
<h1>Cambios en Servicios Web</h1>
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
<h1>Fin de la Actualizaci&oacute;n</h1>
<p>
	La actualizaci&oacute;n ha finalizado satisfactoriamente!. Dentro del sistema encontrar&aacute; documentaci&oacute;n sobre el uso apropiado del mismo.<br>
	<ul>
<?php
	foreach (inst::configuracion()->get('foro_contactos', null, array(), false) as $foro => $valor)	{
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



<?php if (inst::paso()->get_con_temporales()) { ?>
Durante la instalación se hicieron copias temporales de los archivos del sistema y datos de usuarios. Los mismos pueden ser de 
utilidad para analizar algún cambio local faltante durante la migración.
	<br>
</p>
	<div class="go">
		<span class="goToNext">
			<a href="<?php echo inst::accion()->get_url('eliminar_temporales=1');?>">Eliminar archivos y datos temporales (recomendado)</a>
		</span>
	</div>
<p>
<?php } ?>



Por motivos de seguridad es recomendable que una vez comprobado el correcto funcionamiento del sistema elimine el actual instalador, ya que de otra forma el mismo queda
a libre acceso de cualquier usuario. Esta tarea la puede hacer manualmente eliminando el directorio <?php echo INST_DIR; ?>  o a través del siguiente vínculo
	<br>
</p>
	<div class="go">
		<span class="goToNext">
			<a href="?eliminar=1" onclick="return eliminar_instalador()">Eliminar instalador (recomendado)</a>
		</span>
	</div>
<p>
	Muchas gracias por haber actualizado el sistema <?php echo inst::configuracion()->get('proyecto','nombre'); ?>. Que lo disfrute!
</p>