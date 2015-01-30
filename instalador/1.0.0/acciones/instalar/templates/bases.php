<h1>Base de datos</h1>

<?php 
$locale_cluster = '';
$datos_servidor = inst::paso()->get_datos_servidor();
$locale_cluster = inst::configuracion()->get('base','locale_cluster', false, false);
if ($locale_cluster) {
?>
      <div class="go" align="center">
            <span class='goToNext'><?php echo $locale_cluster;?></span>
      </div>
<?php
} //if locale_cluster
?>
<p>
	Por favor especifique los par&aacute;metros de conexi&oacute;n a la base de datos en la que el sistema almacenará la informaci&oacute;n. 
</p>
<form method="post" action="<?php echo inst::accion()->get_url();?>"' onsubmit='return esperar_operacion(this)'>
	<table>
		<tr>
			<td class="label"><label class='ayuda' title='Dirección IP o nombre del servidor postgres' for='profile'>Servidor:</label></td>
			<td>
				<input id="profile" name="profile" type="text" size="20" value="<?php echo $datos_servidor['profile']; ?>">
			</td>			
			<td class="label"><label class='ayuda' title='Puerto en donde escucha el servidor'  for='puerto'>Puerto:</label></td>
			<td>
				<input id="puerto" name="puerto" type="text" size="20" value="<?php echo $datos_servidor['puerto']; ?>">
			</td>			
		</tr>
	</table>

<h2>Parámetros Superusuario</h2>
<span class='aclaracion'>Este usuario será el que utilizará el instalador para tareas administrativas, es necesario que sea un superusuario existente</span>
<table>
		<tr>
			<td class="label"><label class='ayuda' title='Superusuario que se necesitará para crear la base y cambiar permisos, sólo será utilizado para el proceso de instalación' for='usuario'>
				Usuario:</label></td>
			<td>
				<input id="usuario" name="usuario" type="text" size="20" value="<?php echo $datos_servidor['usuario']; ?>">
			</td>		
		</tr>
		<tr>
			<td class="label"><label for='clave'>Clave:</label></td>
			<td>
				<input id="clave" name="clave" type="password" size="20" value="<?php echo $datos_servidor['clave']; ?>">
			</td>			
		</tr>
</table>

<h2>Parámetros Usuario Aplicación</h2>
<span class='aclaracion'>Este usuario será el que utilizará la aplicación para conectarse a la base durante su ejecución, si no existe será creado con los permisos mínimos</span>
<table>
		<tr>
			<td class="label"><label class='ayuda' title='Usuario con el cual se conectará la aplicación en producción, de no existir será creado' for='usuario_aplicacion'>Usuario:</label></td>
			<td>
				<input id="usuario_aplicacion" name="usuario_aplicacion" type="text" size="20" value="<?php if (isset($datos_servidor['usuario_aplicacion'])) echo $datos_servidor['usuario_aplicacion']; ?>">
			</td>			
			<td colspan='2'>
				<input id="usuario_aplicacion_admin" name="usuario_aplicacion_admin" value="1" type="checkbox" <?php if (isset($datos_servidor['usuario_aplicacion_admin']) && $datos_servidor['usuario_aplicacion_admin']) echo 'checked'; ?> 
							onclick="usuario_admin(this.checked);">
				<label class='ayuda' title='Utilizar el superusuario para conectar desde la aplicación, no recomendado por seguridad' for='usuario_aplicacion_admin'>Usar superusuario (inseguro)</label>
			</td>					
		</tr>
		<tr>
			<td class="label"><label class='ayuda' title='Grupo al que pertenece el usuario, si no existe será creado' for='rol_aplicacion'>Rol / Grupo:</label></td>
			<td colspan=3>
				<input id="rol_aplicacion" name="rol_aplicacion" type="text" size="20" value="<?php if (isset($datos_servidor['rol_aplicacion'])) echo $datos_servidor['rol_aplicacion']; ?>">
			</td>		
	 	</tr>
		<tr>
			<td class="label"><label for='clave_aplicacion'>Clave:</label></td>
			<td colspan=3>
				<input id="clave_aplicacion" name="clave_aplicacion" type="text" size="20" value="<?php if (isset($datos_servidor['clave_aplicacion'])) echo $datos_servidor['clave_aplicacion']; ?>">
			</td>			
		</tr>	
</table>

<h2>Base de datos</h2>
<table>
		<tr>
			<td class="label"><label class='ayuda' title='Nombre de la base de datos a la cual se conectará la aplicación' for='base'>Nombre:</label></td>
			<td>
				<input id="base" name="base" type="text" size="20" value="<?php echo $datos_servidor['base']; ?>" >
			</td>			
			<td colspan='2'>
				<input id="existe" name="existe" type="checkbox" <?php if (isset($datos_servidor['existe']) && $datos_servidor['existe']) echo 'checked'; ?> 
							onclick="base_existe(this.checked);">
				<label class='ayuda' title='Se utilizará la base existente migrandola a la versión requerida por el sistema, sino se creará una completamente nueva' for='existe'>
					Es una base <?php echo inst::configuracion()->get('proyecto', 'id'); ?> existente, deseo migrarla a la nueva versión</label>
			</td>			
		</tr>		
			
</table>

<div id='tr_grupos'>
<h2>Conjunto de Datos</h2>
<table>
		<tr>
			<td class="label" valign='top'></td>
			<td colspan='3'><div>			
<?php
$grupos = inst::paso()->get_grupos_datos();
$inicial = true;
foreach ($grupos as $id_grupo => $datos_grupo) {
	$actual = isset($datos_servidor['grupos_datos']) ? 	($datos_servidor['grupos_datos'] == $id_grupo) : $inicial;
?>
				<input id='radio_<?php echo $id_grupo; ?>' type="radio" name="grupos_datos" onclick='configurar_grupos()' value="<?php echo $id_grupo; ?>" <?php if ($actual) echo 'checked'; ?>>
				<label for=radio_<?php echo $id_grupo; ?>><?php echo $datos_grupo['nombre']; ?></label>
				<br>
<?php
	$inicial = false;
} //foreach
?>
		</div>
	
<?php		
foreach ($grupos as $id_grupo => $datos_grupo) {
	echo "<div style='padding-top: 10px' id='grupo_$id_grupo'>";
	foreach ($datos_grupo['archivos'] as $id_archivo => $datos_archivo) {
		$id = $id_grupo.'_'.$id_archivo;
		$actual = isset($datos_servidor[$id]);
?>
		<div>
			<input id="<?php echo $id; ?>" name="<?php echo $id; ?>" value='1' type="checkbox" onclick='configurar_archivo(this)' <?php if ($actual) echo 'checked'; ?>
							grupo='<?php echo $id_grupo; ?>' dependencias='<?php echo $datos_archivo['dependencias']; ?>'>
			<label for='<?php echo $id; ?>'><?php echo $datos_archivo['nombre']; ?></label>
		</div>
<?php
	} //foreach archivos
	echo "</div>";
} //foreach	grupos	
?>		
			</td>			
		</tr>
</table>
</div>


<?php if(inst::paso()->tiene_errores()) {
	$errores = inst::paso()->get_errores();
	foreach ($errores as $error => $mensaje) {
		if ($error == 'schema_existe') {
			$mensaje = "Ya existe el esquema '{$mensaje[1]}' en la base de datos '{$mensaje[0]}'. ¿Desea borrarlo?";
			$mensaje .= "<div style='text-align:center;padding-top:30px'>";
			$mensaje .= "<input type='submit' class='submit' name='reemplazar_si' value='SI, borrar los datos actuales'>";
			$mensaje .= " <input type='submit' class='submit' name='reemplazar_no' value='NO, continuar sin borrar los datos actuales'></div>";					
			$errores[$error] = $mensaje;
		}
	}
	inst::paso()->generar_html_errores($errores);
	inst::scroll_fondo();
} ?>
</form>

<script type='text/javascript'>
function base_existe(existe)
{
	var display = existe ? 'none': '';
	document.getElementById('tr_grupos').style.display = display; 
}

function usuario_admin(es_admin)
{
	document.getElementById('usuario_aplicacion').disabled = es_admin;
	document.getElementById('rol_aplicacion').disabled = es_admin;
	document.getElementById('clave_aplicacion').disabled = es_admin;
 }

function configurar_grupos()
{
	var elementos = document.getElementsByName('grupos_datos');
	for (var i=0; i < elementos.length ; i++) {
		mostrar_grupo(elementos[i].value, elementos[i].checked); 
	}
}

function mostrar_grupo(id, mostrar) 
{
	var display = mostrar ? '': 'none';
	document.getElementById('grupo_' + id).style.display = display;
}

function configurar_archivo(input)
{
	var grupo = input.getAttribute('grupo');
	var id = input.getAttribute('id').substr(grupo.length + 1);
	if (input.checked) {
		var dependencias = input.getAttribute('dependencias').trim().split(',');
		for (var i=0; i < dependencias.length; i++) {
			if (dependencias[i].trim() != '' ){
				var input_dep = document.getElementById(grupo + '_' + dependencias[i].trim());
				input_dep.checked = true;
				input_dep.onclick(); 
			}
		}
	} else {
		elementos = document.getElementsByTagName('input');
		for (var i=0; i < elementos.length; i++) {
			if (grupo == elementos[i].getAttribute('grupo')) {
				var dependencias = elementos[i].getAttribute('dependencias').trim().split(',');
				if (in_array(id, dependencias) && elementos[i].checked) {
					elementos[i].checked = false;
					elementos[i].onclick();
				}		
			}
		}
	}
}

document.getElementById('usuario_aplicacion_admin').onclick();
document.getElementById("existe").onclick();
configurar_grupos();
</script>
<br>

<div class="go">
	<span class="goToNext">
		<a href="javascript:if(document.forms[0].onsubmit()) document.forms[0].submit()">Instalar bases de datos</a>
	</span>
</div>

