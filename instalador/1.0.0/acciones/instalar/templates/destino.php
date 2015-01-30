<h1>Directorio de Destino</h1>
<p>
	Por favor especifique la ruta completa del directorio donde quiere instalar el sistema y a continuación haga click en <b>Crear Directorio y Copiar Archivos</b>
</p>

<p>
	El usuario que corre el servidor web <?php if (!INST_ES_WINDOWS) echo '('.inst::archivos()->apache_get_usuario().')';?> tiene que ser capaz de escribir sobre este directorio.<br>
	Los sistemas de permisos var&iacute;an de equipo en equipo. Se puede definir una configuraci&oacute;n 
	muy relajada sobre este directorio para tener el sistema funcionando, pero es recomendable 
	que se contacte con el administrador de sus sistemas para definir la configuraci&oacute;n
	m&aacute;s segura posible.<br><br>
</p>
<br>
<form method="post" action="<?php echo inst::accion()->get_url();?>"  onsubmit='return esperar_operacion(this)'>
	<table>
		<tr>
			<td class="label">
				<?php print 'Directorio:' ?>
			</td>
			<td colspan=2 class="seleccion">
					<input id="carpeta" name="carpeta" type="text" size="10" value="<?php echo inst::paso()->get_path(); ?>"><?php echo INST_SEP_CARP.inst::configuracion()->get('instalador','carpeta_sufijo'); ?>
			</td>			
		</tr>
	</table>
		<?php if(inst::paso()->tiene_errores()) {
				inst::paso()->generar_html_errores();
			}
		?>
</form>

<div class="go">
	<span class="goToNext">
		<a href="javascript:if(document.forms[0].onsubmit()) document.forms[0].submit()"><?php print 'Crear Directorio y Copiar Archivos' ?></a>
	</span>
</div>
