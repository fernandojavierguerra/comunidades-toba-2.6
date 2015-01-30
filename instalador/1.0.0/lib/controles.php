<?php

/**
 * Clase base con controles
 *
 */
class controles
{
	function version_php($minima='', $maxima='', $mensaje = '')
	{
		$res = array();
		$error = false;
		$titulo = 'Versión de PHP. ';
		if ($mensaje == '') {
			$mensaje = "Por favor contacte al administrador de su webserver para solicitarle que realice una actualizaci&oacute;n, la cual puede obtener
					 del <a target='_blank' href=\"http://php.net/\">sitio de PHP</a>. La versi&oacute;n que usted tiene instalada es la ";
		}
		$mensaje .= phpversion();
		if($minima != '') {
			$titulo .= "Mayor igual a $minima. ";
 			if (version_compare(phpversion(), $minima, '<')) {
 				$error = true;
 			}
		}
		if($maxima != '') {
			$titulo .= "Menor a $maxima. ";
 			if (version_compare(phpversion(), $maxima, '>')) {
 				$error = true;
 			}
		}
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}
	
	function version_postgres($minima='', $maxima='', $mensaje = '', $conexion=null)
	{
		$res = array();
		$error = false;
		$titulo = 'Versión de PostgreSQL. (requiere ';
		if ($mensaje == '') {
			$mensaje = "Por favor contacte al administrador de postgres para solicitarle una actualizaci&oacute;n. La versi&oacute;n que usted tiene instalada es la ";
		}
		if (! isset($conexion)) {
			$datos = inst::configuracion()->get_info_instalacion(); 
			$conexion = inst::db_manager()->conectar($datos['base']);
		}
		$version = db_manager::get_version_motor_db($conexion);
		$mensaje .= $version;
		if ($minima != '') {
			$titulo .= "mayor o igual a $minima ";
 			if (version_compare($version, $minima, '<')) {
 				$error = true;
 			}
		}
		if ($maxima != '') {
			$titulo .= "menor a $maxima";
 			if (version_compare($version, $maxima, '>')) {
 				$error = true;
 			}
		}
		$titulo .= ')';
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}	

	function php_consola($mensaje = '')
	{
		$titulo = 'Soporte de Consola';

		$error = (exec ('php -v') == NULL) ? true : false;
		if ($error === true) {
			if ($mensaje == '') {
				$mensaje = 'El sistema requiere el modulo de consola de PHP.';
				$mensaje .= ' Verifique la variable de entorno PATH ';
			}

			if (INST_ES_WINDOWS) {
				$mensaje .= 'o habilitela ejecutando el instalador .msi de php y seleccionando la extensión CLI';
			} else {
				$mensaje .= 'o en distribuciones DEBIAN ejecute como superusuario <pre>apt-get install php5-cli</pre>';
			}		
			$mensaje .= ', luego reinicie el servidor web.';
		}			
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);	
	}	
	
	//------------- EXTENSIONES

	function pdo_pgsql($mensaje = '')
	{
		$titulo = "Soporte para Postgres";

		if ($mensaje == '') {
			$mensaje = "El sistema requiere que PHP tenga la librer&iacute;a PDO Postgres (pdo_pgsql) para la comunicaci&oacute;n con la base de datos.";
		}
		if (INST_ES_WINDOWS) {
			$mensaje .= "Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension PDO/PostgreSQL";
		} else {
			$mensaje .= " Para sistemas debian ejecutar como root <pre>apt-get install php5-pgsql</pre> ";
		}
		$mensaje .= ' y reiniciar luego el servidor web.';
		$error = !extension_loaded('pdo_pgsql');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function gd($mensaje='')
	{
		$titulo = "Soporte para GD (edición de imagenes)";
		if ($mensaje == '') {
			$mensaje = "El sistema requiere que PHP tenga la librer&iacute;a GD (gd) para la edición de imágenes.";
		}
		if (INST_ES_WINDOWS) {
			$mensaje .= " Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension GD2";
		} else {
			$mensaje .= " Para sistemas debian ejecutar como root <pre>apt-get install php5-gd</pre>";
		}
		$mensaje .= ' y reiniciar luego el servidor web.';
		$error = !extension_loaded('gd');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function zip($mensaje='')
	{
		$titulo = "Soporte ZIP (lectura/escritura archivos comprimidos)";
		if ($mensaje == '') {
			$mensaje = "El sistema requiere que PHP tenga la librer&iacute;a ZIP (zip) para la lectura y escritura de archivos comprimidos.";
		}
		if (INST_ES_WINDOWS) {
			$mensaje .= " Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension ZIP";
		} else {
			$mensaje .= " Para sistemas debian ejecutar como root <pre>apt-get install php5-zip</pre>";
		}
		$mensaje .= ' y reiniciar luego el servidor web.';
		$error = !extension_loaded('zip');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function xsl($mensaje='')
	{
		$titulo = "Soporte XSL";
		if ($mensaje == '') {
			$mensaje = "El sistema requiere que PHP tenga la librer&iacute;a XSL (xsl) de transformaciones";
		}
		if (INST_ES_WINDOWS) {
			$mensaje .= " Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension XSL";
		} else {
			$mensaje .= " Para sistemas debian ejecutar como root <pre>apt-get install php5-xsl</pre>";
		}
		$mensaje .= ' y reiniciar luego el servidor web.';
		$error = !extension_loaded('xsl');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function curl($mensaje='')
	{
		$titulo = "Soporte CURL";
		if ($mensaje == '') {
			$mensaje = "El sistema requiere que PHP tenga habilitada la librer&iacute;a CURL (curl) para transferencia de datos";
		}
		if (INST_ES_WINDOWS) {
			$mensaje .= " Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension CURL";
		} else {
			$mensaje .= " Para sistemas debian ejecutar como root <pre>apt-get install php5-curl</pre>";
		}
		$mensaje .= ' y reiniciar luego el servidor web.';
		$error = !extension_loaded('curl');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function dbase($mensaje = '')
	{
		$titulo = "Soporte para dBASE";

		if ($mensaje == '') {
			$mensaje = "El sistema utiliza la librer&iacute;a dBASE de PHP para la comunicaci&oacute;n con los archivos .dbf.";
		}
		if (INST_ES_WINDOWS) {
			$mensaje .= "Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension dbase";
		} else {
			$mensaje .= " Para sistemas debian recompilar php con la directiva <pre>--enable-dbase</pre> ";
		}
		$mensaje .= ' y reiniciar luego el servidor web.';
		$error = !extension_loaded('dbase');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function mbstring($mensaje='')
	{
	    $titulo = "Soporte para Strings Multibyte";
	    if ($mensaje == '') {
		$mensaje = "El sistema requiere que PHP tenga la librer&iacute;a para Strings Multibyte (mbstring)";
	    }
	    if (INST_ES_WINDOWS) {
		$mensaje .= " Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension MBSTRING";
	    }
	    $mensaje .= ' y reiniciar luego el servidor web.';
	    $error = !extension_loaded('mbstring');
	    return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function web_services($mensaje='')
	{
	    $titulo = "Soporte para Web Services usando WSF";
	    if ($mensaje == '') {
		$mensaje = "El sistema requiere que PHP tenga la librer&iacute;a para Web Services (wsf)";
	    }
	    if (INST_ES_WINDOWS) {
		$mensaje .= " Puede descargarla y seguir los pasos de instalacion desde la pagina de WSO2 ";
	    }
	    $mensaje .= ' y reiniciar luego el servidor web.';
	    $error = !extension_loaded('wsf');
	    return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}
	
	//------------- DIRECTIVAS
	function magic_quotes($mensaje = '')
	{
		$titulo = "Directivas magic_quotes";
		if ($mensaje == '') {
			$mensaje = "El sistema necesita que las directivas magic_quotes_gpc y magic_quotes_runtime  est&eacute;n desactivadas en el servidor web.
						 Puede deshabilitarlas editando el archivo php.ini, cambiando las siguientes directivas:<br>
						 	<pre>magic_quotes_gpc = Off;\nmagic_quotes_runtime = Off;</pre>
						 y reiniciando luego el servidor web
						 ";
		}
		$error = get_magic_quotes_gpc() || get_magic_quotes_runtime();
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function register_globals($mensaje = '')
	{
		$titulo = "Directiva register_globals";
		if ($mensaje == '') {
			$mensaje = "Para prevenir problemas de seguridad, el sistema requiere un cambio en la configuración PHP.
				Necesita cambiar la siguiente directiva editando el archivo php.ini:
				<pre>register_globals = Off</pre>
				y reiniciar luego el servidor web
				";
		}
		$error = ini_get('register_globals');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}
	
	function memory_limit($minimo, $mensaje = '')
	{
		$titulo = "Tamaño máximo de memoria a usar";
		$actual = ini_get('memory_limit');
		if ($mensaje == '') {
			$mensaje = "El sistema recomienda aumentar el l&iacute;mite máximo de memoria actual de $actual al menos a {$minimo}M.
				Puede cambiar esta directiva editando el archivo php.ini, cambiando la siguiente directiva
				<pre>memory_limit = {$minimo}M</pre>
				y reiniciando luego el servidor web
				";
		}
		$error = $minimo > (int) $actual;
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function display_errors($mensaje = '')
	{
		$titulo = "Ocultar errores PHP al usuario";
			if ($mensaje == '') {
			$mensaje = "Para aumentar la seguridad en producción, el sistema recomienda desactivar la impresión de errores por pantalla.
				Puede cambiar esta directiva editando el archivo php.ini, cambiando las siguientes directivas:
				<pre>display_errors = Off</pre>
				<pre>log_errors = On</pre>
				y reiniciando luego el servidor web
				";
		}
		$error = ini_get('display_errors') || ! ini_get('log_errors');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	function upload($minimo, $mensaje ='')
	{
		$titulo = "Tamaño máximo para subir archivos";
		$post = ini_get('post_max_size');
		$upload = ini_get('upload_max_filesize');
		if ($mensaje == '') {
			$mensaje = "El sistema recomienda aumentar el l&iacute;mite máximo de subida de archivos al servidor.
				Puede cambiar esta directiva editando el archivo php.ini, cambiando las siguientes directivas:
				<pre>post_max_size = {$minimo}M;\nupload_max_filesize = {$minimo}M;</pre>
				y reiniciando luego el servidor web
				";
		}
		$error = ($minimo > (int)$post || $minimo > (int)$upload);
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}
	
	function seguridad_cookies($mensaje = '')
	{
		$error_sid = $this->seguridad_sesion_sid();
		$error_sesion_cookies = $this->seguridad_sesion_cookies();
		$error_solo_http = $this->seguridad_sesion_solo_http();

		$titulo = "Seguridad de sesiones";
		$error = false;
		if ($mensaje == '') {
			$mensaje = "Para prevenir el robo de cookies en producción, el sistema necesita un cambio en la configuración PHP.
				Necesita cambiar las siguientes directivas editando el archivo php.ini:
			";
			if ($error_sid['error']) {
				$error = true;
				$mensaje .= "<pre>session.use_trans_sid = 0</pre>";
			}
			if ($error_sesion_cookies['error']) {
				$error = true;
				$mensaje .= "<pre>session.use_only_cookies = 1</pre>";
			}
			if ($error_solo_http['error']) {
				$error = true;
				$mensaje .= "<pre>session.cookie_httponly = 1</pre>";
			}
			$mensaje .= "y reiniciar luego el servidor web";
		}
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);		
	}

	function seguridad_sesion_sid($mensaje='')
	{
		$titulo = "Seguridad de sesiones";
		if ($mensaje == '') {
			$mensaje = "Para prevenir el robo de cookies en producción, el sistema necesita un cambio en la configuración PHP.
				Necesita cambiar las siguientes directivas editando el archivo php.ini:
				<pre>session.use_trans_sid = 0</pre>
				y reiniciar luego el servidor web
				";
		}
		$error = ini_get('session.use_trans_sid');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);		
	}
	
	function seguridad_sesion_cookies($mensaje= '')
	{
		$titulo = "Seguridad de sesiones";
		if ($mensaje == '') {
			$mensaje = "Para prevenir el robo de cookies en producción, el sistema necesita un cambio en la configuración PHP.
				Necesita cambiar las siguientes directivas editando el archivo php.ini:
				<pre>session.use_only_cookies = 1</pre>
				y reiniciar luego el servidor web
				";
		}
		$error = (! ini_get('session.use_only_cookies'));
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);		
	}

	function seguridad_sesion_solo_http($mensaje='')
	{
		$titulo = "Seguridad de sesiones";
		if ($mensaje == '') {
			$mensaje = "Para prevenir el robo de cookies en producción, el sistema necesita un cambio en la configuración PHP.
				Necesita cambiar las siguientes directivas editando el archivo php.ini:
				<pre>session.cookie_httponly = 1</pre>
				y reiniciar luego el servidor web
				";
		}
		$error = (! ini_get('session.cookie_httponly'));
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);				
	}

	function seguridad_inclusiones_remotas($mensaje = '')
	{
		$titulo = "Seguridad de inclusiones remotas";
		if ($mensaje == '') {
			$mensaje = "Para prevenir la inclusión remota de archivos, el sistema necesita un cambio en la configuración PHP.
				Necesita cambiar las siguientes directivas editando el archivo php.ini:
				<pre>allow_url_include = Off</pre>
				<pre>allow_url_fopen = Off</pre>
				y reiniciar luego el servidor web
				";
		}
		$error = ini_get('allow_url_include') || ini_get('allow_url_fopen');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);		
	}

	function seguridad_expose($mensaje = '')
	{
		$titulo = "Ocultar detalles de versiones Apache-Php";
		if ($mensaje == '') {
			$mensaje = "Para evitar brindar número de versiones y detalles de la instalación que pueden ser útiles para un ataque a la seguridad del sistema,
				el sistema recomienda cambios en la configuración PHP y Apache.
				Se recomienda cambiar las siguiente directivas editando el archivo php.ini:
				<pre>expose_php = Off</pre>
				También se recomienda editar el archivo de configuración de Apache (típicamente httpd.conf) y cambiar las siguientes directivas:
				<pre>ServerSignature Off</pre>
				<pre>ServerTokens Prod</pre>
				y reiniciar luego el servidor web
				";
		}
		$ok = true;
		if (isset($_SERVER['SERVER_SIGNATURE']) && $_SERVER['SERVER_SIGNATURE'] != '') {
			$ok = false;
		}
		if (isset($_SERVER['SERVER_SOFTWARE']) && strtolower($_SERVER['SERVER_SOFTWARE']) != 'apache') {
			$ok = false;
		}
		$ok = $ok && !ini_get('expose_php');
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => ! $ok);
		//expose_php=off
	}

	function suhosin_patch($mensaje = '')
	{
		$titulo = 'Reconfigurar Suhosin para extender restricciones al request';
		if ($mensaje =='') {
			$mensaje = 'Es recomendable que se cambien los siguientes parametros en el parche suhosin. <br/>'.
			'<pre>suhosin.post.max_name_length = 96</pre><br/>'.
			//'<pre>suhosin.request.max_total_name_lenght</pre><br/>'.			
			'<pre>suhosin.request.max_varname_length = 128</pre><br/>
			y reiniciar luego el servidor web.';
		}
		
		$ok = true;
		if ($this->suhosin_activo())  {		
			//Obtengo los valores de php.ini para ver si fueron cambiados
			$max_name = ini_get('suhosin.post.max_name_length');
			$max_varname = ini_get('suhosin.request.max_varname_length');
			$ok = $ok &&	((int) $max_name > '95') &&
			//			(ini_get('suhosin.request.max_total_name_lenght') > '255') &&		//Por ahora este no parece ser una restriccion
						((int) $max_varname > '127');	
		}
		return array('titulo' =>$titulo, 'mensaje' => $mensaje, 'error' => !$ok);
	}
	
	function max_input_vars($maximo, $mensaje = '')
	{
		$titulo = "Cantidad m&aacute;xima de variables que se pueden pasar por par&aacute;metro";
		if ($mensaje == '') {
			$mensaje = "La cantidad de variables que se pueden pasar por POST o REQUEST est&aacute; definida por el par&aacute;metro max_input_vars de php.</br>
			Para ciertas operaciones, el sistema necesita un valor mayor al que est&aacute; definido.</br>
			Para evitar inconvenientes debe definir el valor de: </br>";
		}
		
		$ok = true;
		
		// Chequeo que este cargado el max_input_vars
		$cant_input_vars = ini_get('max_input_vars');
		if ((trim($cant_input_vars) != '') && ($cant_input_vars < $maximo)) {					
			$mensaje .= "- el par&aacute;metro max_input_vars en el archivo de configuraci&oacute;n de php (php.ini) a un valor mayor o igual que ". $maximo . "</br>";
			$ok = false;
		}

		//Chequeo contra el patch suhosin si esta cargado y activo 
		if ($this->suhosin_activo()) {
			if (ini_get('suhosin.post.max_vars') < $maximo) {
				$mensaje .= "- el par&aacute;metro suhosin.post.max_vars en el archivo de configuraci&oacute;n de suhosin (conf.d/suhosin.ini) a un valor mayor o igual que ". $maximo . "</br>";
				$ok = false;
			}
			if (ini_get('suhosin.request.max_vars') < $maximo) {
				$mensaje .= "- el par&aacute;metro suhosin.request.max_vars en el archivo de configuraci&oacute;n de suhosin (conf.d/suhosin.ini) a un valor mayor o igual que ". $maximo . "</br>";
				$ok = false;
			}			
		}		
		return array('titulo' =>$titulo, 'mensaje' => $mensaje, 'error' => !$ok);
	}
	
	function suhosin($mensaje = '')
	{
		$titulo = 'El modulo Suhosin se encuentra activado';
		$mensaje = 'El modulo Suhosin se encuentra activado y puede llegar a generar comportamientos inesperados en el sistema. El sistema no desalienta la utilización de este modulo, pero si invita a chequear su correcta configuración.';
		$error = $this->suhosin_activo();
		return array('titulo' =>$titulo, 'mensaje' => $mensaje, 'error' => $error);
	}
	
	
	function com_dotnet($mensaje = '') 
	{
		$error = false;
		$titulo = "Soporte para COM_DOTNET (Ejecución desde Consola)";
		if (INST_ES_WINDOWS) {
			$mensaje .= " Puede habilitarla ejecutando nuevamente el instalador .msi de php y seleccionando la extension COM_DOTNET";
			$mensaje .= ' y reiniciar luego el servidor web.';
			$error = !extension_loaded('com_dotnet');
		}
		return array('titulo' => $titulo, 'mensaje' => $mensaje, 'error' => $error);
	}

	//---------------------------------------------------------------------------------------------------------//
	//					Privates para reuso							  //
	//---------------------------------------------------------------------------------------------------------//
	private function suhosin_activo()
	{	//Hay que empezar a reusar
		return (extension_loaded('suhosin') && !ini_get('suhosin.simulation'));
	}
}
?>