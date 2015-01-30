<?php

class paso_instalar_configuracion extends paso
{
	protected $datos_configuracion;
	protected $path_instalacion;
	protected $path_instancia;
	
	function conf()
	{
		$this->nombre = 'Configuración';
	}
	
	
	function get_datos_configuracion()
	{
		if (! isset($this->datos_configuracion)) {
			return $this->get_datos_configuracion_defecto();
		} else {
			return $this->datos_configuracion;
		}
	}	
	
	function get_datos_configuracion_defecto()
	{
		return array(
			'url_prefijo' => inst::configuracion()->get('instalador', 'url_prefijo'),
			
			'usuario_nombre' => '',
			'usuario_id' => '',
			'usuario_clave' => '',
			'usuario_email' => '',
		
			'smtp_from' => '',
			'smtp_host' => '',
			'smtp_auth' => false,
			'smtp_usuario' => '',
			'smtp_clave' => '',
			'smtp_seguridad' => ''
		);
	}
		
	
	function procesar()
	{
		$this->resetear_errores();
		if (! empty($_POST)) {
			$this->datos_configuracion = $_POST;
			//-- Loguea los parametros
			$datos_debug = $this->datos_configuracion;
			$datos_debug['usuario_clave'] = '******';
			$datos_debug['smtp_clave'] = '******';
			inst::logger()->debug('Configuración utilizada: '.var_export($datos_debug, true));
			
			$this->validar_parametros();
			if (! $this->tiene_errores()) {
				if (isset($_POST['smtp_probar'])) {
					if (! $this->tiene_errores()) {
						@$this->probar_smtp();
					}
				} else {
					$this->crear_instalacion();
					if (! $this->tiene_errores()) {
						$this->set_completo();
					}
				}
			}
		} else {
			if (isset($this->datos_configuracion['smtp_ok'])) {
				unset($this->datos_configuracion['smtp_ok']);
			}			
		}
	}
	
	function validar_parametros()
	{
		if (trim($this->datos_configuracion['usuario_nombre']) == '') {
			$this->set_error('no_nombre', 'Debe indicar el nombre completo del usuario');
		}
		if (trim($this->datos_configuracion['usuario_id']) == '') {
			$this->set_error('no_id', 'Debe indicar el nombre del usuario');
		}
		if (strpos($this->datos_configuracion['usuario_id'], ' ') !== false) {
			$this->set_error('espacios_id', 'El identificador del usuario no puede contener espacios');
		}		
		if (trim($this->datos_configuracion['usuario_clave']) == '') {
			$this->set_error('no_id', 'Debe indicar la clave del usuario');
		}		
		//Mail valido
		if (trim($this->datos_configuracion['usuario_email']) != '') {
			if (! preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $this->datos_configuracion['usuario_email'])) {
				$this->set_error('email_invalido', 'La dirección de E-Mail no es válida');				
			}
		}		
		
			//Mail valido
		if (trim($this->datos_configuracion['smtp_from']) != '') {
			if (! preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $this->datos_configuracion['smtp_from'])) {
				$this->set_error('email_invalido', 'La dirección de E-Mail no es válida');				
			}
		}
		
		try{
			$toba_lib = new toba_lib();
			$toba_lib->verificar_clave_usuario($this->datos_configuracion['usuario_clave']);
		} catch (toba_error_pwd_conformacion_invalida $e) {			
			$this->set_error('pwd_invalido', $e->getMessage());
		}		
	}
	
	function probar_smtp()
	{
		$smtp = new SMTP();
		$hello = 'Prueba';
		$host = $this->datos_configuracion['smtp_host'];
		if ($this->datos_configuracion['smtp_seguridad'] == 'ssl') {
			if (! extension_loaded('openssl')) {
				$this->set_error('no_ssl', 'Para usar encriptación SSL es necesario activar la extensión "openssl" en el php.ini');
				return;				
			} else {
				$host = 'ssl://'.$host;
			}
		}
		$smtp->Connect($host);
		if (empty($smtp->error)) {
			$smtp->Hello($hello);
		}		
		if (empty($smtp->error)) {
			if ($this->datos_configuracion['smtp_seguridad'] == 'tls') {
				$smtp->StartTLS();
				$smtp->Hello($hello);
			}		
		}
		if (empty($smtp->error)) {
			if (isset($this->datos_configuracion['smtp_auth']) && $this->datos_configuracion['smtp_auth']) {
				$smtp->Authenticate($this->datos_configuracion['smtp_usuario'], $this->datos_configuracion['smtp_clave']);
			}
		}
		if (empty($smtp->error)) {
			$smtp->Mail($this->datos_configuracion['smtp_ok']);
		}
		if (! empty($smtp->error)) {
			$mensaje = '<ul>';
			if (isset($smtp->error['smtp_code'])) {
				$mensaje .= "<li>Código SMTP: {$smtp->error['smtp_code']}</li>";
			}
			if (isset($smtp->error['error'])) {
				$mensaje .= "<li>Error: {$smtp->error['error']}</li>";
			}
			if (isset($smtp->error['smtp_msg'])) {
				$mensaje .= "<li>Mensaje SMTP: {$smtp->error['smtp_msg']}</li>";
			}
			$mensaje .= '</ul>';				
			$this->set_error('error_smtp', $mensaje);
		} else {
			$smtp->Close();
			$this->datos_configuracion['smtp_ok'] = 1;
		}
		
	}
	
	function crear_instalacion()
	{
		//-- Arma la url final de la instalacion
		if (trim($this->datos_configuracion['url_prefijo']) != '') {
			$prefijo = '/'.trim($this->datos_configuracion['url_prefijo']);
		} else {
			$prefijo = '';
		}
		$_SESSION['url_instalacion'] = $prefijo.inst::configuracion()->get('instalador', 'url_sufijo');
		
		$id_proyecto_ppal = inst::configuracion()->get('proyecto', 'id');		
		$path_final = str_replace('\\', '\\\\', $_SESSION['path_instalacion']);
		$this->path_instalacion = $path_final.'/instalacion';
		if (! is_dir($this->path_instalacion) && ! mkdir($this->path_instalacion, 0770)) {
			$this->set_error('sin_instalacion', "No fue posible crear la carpeta '$this->path_instalacion'");
			return;
		}

		$es_produccion =  (inst::configuracion()->es_instalacion_produccion()) ? 1: 0;		
		//-- Instalacion.ini
		$instalacion = new inst_ini($this->path_instalacion.'/instalacion.ini');
		$instalacion->agregar_entrada('clave_querystring', md5(uniqid(rand(), true)));	
		$instalacion->agregar_entrada('clave_db', md5(uniqid(rand(), true)));	
		$instalacion->agregar_entrada('url', inst::configuracion()->get_url_final_proyecto_extra('toba'));
		$instalacion->agregar_entrada('es_produccion', $es_produccion);
		$instalacion->guardar();
		
		//-- SMTP.ini
		if (trim($this->datos_configuracion['smtp_host']) != '') {
			$smtp = new inst_ini($this->path_instalacion.'/smtp.ini');
			$datos_smtp = array();
			$datos_smtp['from'] = $this->datos_configuracion['smtp_from'];
			$datos_smtp['host'] = $this->datos_configuracion['smtp_host'];
			$datos_smtp['seguridad'] = $this->datos_configuracion['smtp_seguridad'];
			$con_auth = isset($this->datos_configuracion['smtp_auth']) && $this->datos_configuracion['smtp_auth'];
			$datos_smtp['auth'] = $con_auth ? 1 : 0;
			if ($con_auth) {
				$datos_smtp['usuario'] = $this->datos_configuracion['smtp_usuario'];
				$datos_smtp['clave'] = $this->datos_configuracion['smtp_clave'];
			}
			$smtp->agregar_entrada('instalacion', $datos_smtp);
			$smtp->guardar();			
		}
		
		//-- Instancia 
		$nombre_instancia = inst::configuracion()->get_nombre_instancia();
		$this->path_instancia = $this->path_instalacion . '/i__' . $nombre_instancia;
		if (! is_dir($this->path_instancia) && ! mkdir($this->path_instancia, 0770)) {
			$this->set_error('sin_instancia', "No fue posible crear la carpeta '$this->path_instancia'");
			return;
		}		
		$proyectos = inst::configuracion()->get_proyectos_final_instancia();
		if (! $es_produccion) {
			$proyectos = array_unique(array_merge($proyectos, toba_lib::get_proyectos_disponibles()));
		}
		$instancia = new inst_ini($this->path_instancia.'/instancia.ini');
		$instancia->agregar_entrada('base', inst::configuracion()->get_id_base_final_toba());
		$instancia->agregar_entrada('tipo', 'normal');		
		$instancia->agregar_entrada( 'proyectos', implode(', ', $proyectos));
		foreach ($proyectos as $id_proyecto) {
			$datos_proyecto = array();
			if ($id_proyecto == $id_proyecto_ppal) {
				$datos_proyecto['url'] = inst::configuracion()->get_url_final_proyecto();
				$datos_proyecto['path'] = $path_final.'/aplicacion';
			} else {
				$datos_proyecto['url'] = inst::configuracion()->get_url_final_proyecto_extra($id_proyecto);
			}
			$datos_proyecto['usar_perfiles_propios'] = 0;
			$instancia->agregar_entrada($id_proyecto, $datos_proyecto);
		}
		$instancia->guardar();
		
		//-- Proyectos
		$path_global = $this->path_instancia.'/global';
		if (! is_dir($path_global) && ! mkdir($path_global, 0770)) {
			$this->set_error('sin_instancia', "No fue posible crear la carpeta '$path_global'");
			return;
		}		
		foreach ($proyectos as $id_proyecto) {
			$path_proyecto = $this->path_instancia.'/p__'.$id_proyecto;
			if (! is_dir($path_proyecto) && ! mkdir($path_proyecto, 0770)) {
				$this->set_error('sin_instancia', "No fue posible crear la carpeta '$path_proyecto'");
				return;
			}
			$path_logs = $path_proyecto.'/logs';
			if (! is_dir($path_logs) && ! mkdir($path_logs, 0770)) {
				$this->set_error('sin_instancia', "No fue posible crear la carpeta '$path_logs'");
				return;
			}			
		}
		if (! $this->tiene_errores()) {
			$this->generar_conf_apache();
		}
		if (! $this->tiene_errores()) {
			$_SESSION['datos_configuracion'] = $this->datos_configuracion; 
		}
	}
	
	
	function generar_conf_apache()
	{
		$ini_instalacion = new inst_ini($this->path_instalacion.'/instalacion.ini');
		$ini_instancia = new inst_ini($this->path_instancia.'/instancia.ini');				
		$destino = $this->path_instalacion.'/toba.conf';

		//-- Nucleo
		$origen = INST_DIR.'/lib/var/toba.conf';
		if (! copy($origen, $destino)) {
			$this->set_error('sin_toba_conf', "No fue posible copiar el archivo '$origen' hacia '$destino'");
			return;			
		}

		$path_final = str_replace('\\', '\\\\', $_SESSION['path_instalacion']);
		$editor = new editor_archivos();
		$editor->agregar_sustitucion( '|__toba_dir__|', $path_final .'/toba');
		$editor->agregar_sustitucion( '|__toba_alias__|', $ini_instalacion->get_datos_entrada('url')); 
		$editor->procesar_archivo($destino);
		
		//-- Proyectos
		$proyectos = inst::configuracion()->get_proyectos_final_instancia();
		foreach ($proyectos as $id_proyecto) {
			$datos_proyecto = $ini_instancia->get_datos_entrada($id_proyecto);
			//--- Se agrega el proyecto al archivo
			$template = file_get_contents(INST_DIR.'/lib/var/proyecto.conf');
			$editor = new editor_texto();
			$editor->agregar_sustitucion( '|__toba_dir__|', $path_final .'/toba');
			if (isset($datos_proyecto['path'])) {		
				$path = $datos_proyecto['path'];
			} else {
				$path = $path_final ."/toba/proyectos/$id_proyecto";
			}
			$editor->agregar_sustitucion( '|__proyecto_dir__|', $path);
			$editor->agregar_sustitucion( '|__proyecto_alias__|', $datos_proyecto['url']); 
			$editor->agregar_sustitucion( '|__proyecto_id__|', $id_proyecto); 
			$editor->agregar_sustitucion( '|__instancia__|', inst::configuracion()->get_nombre_instancia());
			$editor->agregar_sustitucion( '|__instalacion_dir__|', $this->path_instalacion);
			$salida = $editor->procesar( $template );
			file_put_contents($destino, $salida, FILE_APPEND);			
		}
	}

}

?>