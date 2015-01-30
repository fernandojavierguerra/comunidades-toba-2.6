<h1>Resultado del Diagnóstico</h1>
<br>
<?php 
$datos = inst::paso()->get_datos_configuracion(); 
?>
<form method="post" action="<?php echo inst::accion()->get_url();?>" onsubmit='return esperar_operacion(this)'>
Se ha creado con éxito el archivo de diagnóstico, puede descargarlo a través del siguiente enlace.<br><br>

<div style='margin-left: auto: margin-right: auto; text-align:center;'>
<a href='<?php echo inst::paso()->get_url_archivo();?>'>
<img src='recursos/download_manager.png' border=0 /><br>
<span style='font-weight: bold; font-size:12px'>Descargar Diagnóstico</span><br>
<?php echo inst::paso()->get_tamanio_archivo(); ?>
</a>
</div>
<br>
Opcionalmente puede enviar el resultado directamente a la cuenta de soporte de <?php echo inst::configuracion()->get('proyecto', 'id'); ?> completando el siguiente formulario 
y presionando en <strong>Enviar Diagnóstico</strong><br><br>
<table id='configurar_smtp'>
<tr>
	<td class="label"><label for='smtp_from'>De:</label></td>
	<td colspan=3>
		<input id="smtp_from" name="smtp_from" type="text" size="30" value="<?php echo $datos['smtp_from']; ?>">
	</td>	
</tr>
<tr>
	<td class="label"><label for='smtp_to'>Para:</label></td>
	<td colspan=3>
		<input id="smtp_to" name="smtp_to" type="text" size="30" value="<?php echo $datos['smtp_to']; ?>">
	</td>	
</tr>
<tr>
	<td class="label"><label for='smtp_cc'>CC:</label></td>
	<td colspan=3>
		<input id="smtp_to" name="smtp_cc" type="text" size="30" value="<?php echo $datos['smtp_cc']; ?>">
	</td>	
</tr>
<tr>
	<td class="label"><label for='smtp_to'>Cuerpo:</label></td>
	<td colspan=3>
		<TEXTAREA NAME="smtp_body" cols=45 rows=6><?php echo $datos['smtp_body']; ?></TEXTAREA>
	</td>
</tr>
<tr>
	<td class="label"><label class='ayuda' title='Dirección IP o nombre del servidor SMTP' for='smtp_host'>Servidor SMTP:</label></td>
	<td colspan=3>
		<input id="smtp_host" name="smtp_host" type="text" size="30" value="<?php echo $datos['smtp_host']; ?>">
	</td>	
	
</tr>
<tr>
	<td></td>
	<td colspan=3>
		<label> <input type='radio' value='' name='smtp_seguridad' <?php if ($datos['smtp_seguridad'] == '') echo 'checked'; ?>>Sin Encriptación</label>
		<label> <input type='radio' value='ssl' name='smtp_seguridad' <?php if ($datos['smtp_seguridad'] == 'ssl') echo 'checked'; ?>>SSL</label>
		<label> <input type='radio' value='tls' name='smtp_seguridad' <?php if ($datos['smtp_seguridad'] == 'tls') echo 'checked'; ?>>TLS</label>
	</td>		
</tr>			
<tr>	
	<td class="label"><label for='smtp_auth'>Requiere autentificación:</label></td>		
	<td colspan=3>
		<input id="smtp_auth" name="smtp_auth" type="checkbox" onclick='cambio_smtp_auth(this.checked)' <?php if (isset($datos['smtp_auth']) && $datos['smtp_auth']) echo 'checked'; ?>>
	</td>
</tr>
<tr id='smtp_usuario_clave'>
	<td class="label"><label for='smtp_usuario'>Usuario</label></td>
	<td>
		<input id="smtp_usuario" name="smtp_usuario" type="text" size="15" value="<?php echo $datos['smtp_usuario']; ?>">
	</td>		
	<td class="label"><label for='smtp_clave'>Clave</label></td>
	<td>
		<input id="smtp_clave" name="smtp_clave" type="password" size="15" value="<?php echo $datos['smtp_clave']; ?>">
	</td>		
</tr>
</table>

<br>

<?php if(inst::paso()->tiene_errores()): 
	inst::paso()->generar_html_errores();
endif; ?>

<script type='text/javascript'>

function cambio_smtp_auth(auth)
{
	var display = auth ? '': 'none';
	document.getElementById('smtp_usuario_clave').style.display = display;		

}
document.getElementById("smtp_auth").onclick();
</script>

<div class="go">
	<span class="goToNext">
		<a href="javascript:if(document.forms[0].onsubmit()) document.forms[0].submit()">Enviar Diagnóstico</a>
	</span>
</div>
</form>