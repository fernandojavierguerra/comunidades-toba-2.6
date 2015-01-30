<h1>Actualizar Aplicación</h1>
<p>
	Por favor confirme los datos de la instalación actual e ingrese los par&aacute;metros de conexi&oacute;n de la base de datos que será actualizada durante el proceso. 
</p>
<?php 
$parametros = inst::paso()->get_parametros();
$datos = inst::configuracion()->get_info_instalacion(); 
?>
<form method="post" action="<?php echo inst::accion()->get_url();?>" onsubmit='return esperar_operacion(this)'>
<table width=500px>
<tr><td valign=top>
	<strong>Sistema</strong>
		<ul>
			<li>URL: <a href='<?php echo $datos['sistema']['url']; ?>'><?php echo $datos['sistema']['url']; ?></a> </li>
			<li>Versión actual: <?php echo $datos['sistema']['version_actual']; ?></li>
			<li>Versión a instalar: <?php echo inst::configuracion()->get('proyecto', 'version'); ?></li>
		</ul>

</td><td valign=top>
	<strong>Base de Datos</strong>
		<ul>
			<li>Servidor: <?php echo $datos['base']['profile']; ?> </li>
			<li>Puerto: <?php echo $datos['base']['puerto']; ?></li>
			<li>Usuario conexión: <?php echo $datos['base']['usuario']; ?></li>
			<li>Base de datos: <?php echo $datos['base']['base']; ?></li>
			<li>Esquema <?php echo inst::configuracion()->get('proyecto', 'id'); ?>: <?php echo $datos['base']['schema']; ?></li>			
			<li>Esquema toba: <?php echo $datos['base']['schema_toba']; ?></li>
		</ul>
</td>
</tr>
</table>
<h2>Parámetros Superusuario</h2>
<span class='aclaracion'>Este usuario será el que utilizará el instalador para la migración de datos, es necesario que sea un superusuario existente</span>
<table>
		<tr>
			<td class="label"><label class='ayuda' title='Superusuario que se necesitará para crear la base y cambiar permisos, sólo será utilizado para el proceso de instalación' for='usuario'>
				Usuario:</label></td>
			<td>
				<input id="usuario" name="usuario" type="text" size="20" value="<?php echo $parametros['usuario']; ?>">
			</td>
		</tr>
		<tr>
			<td class="label"><label for='clave'>Clave:</label></td>
			<td>
				<input id="clave" name="clave" type="password" size="20" value="<?php echo $parametros['clave']; ?>">
			</td>			
		</tr>

</table>

<?php if(inst::paso()->tiene_errores()) {
	inst::paso()->generar_html_errores();
	inst::scroll_fondo();
} ?>
</form>
<div class="go">
	<span class="goToNext">
		<a href="javascript:if(document.forms[0].onsubmit()) document.forms[0].submit()">Actualizar sistema</a>
	</span>
</div>

