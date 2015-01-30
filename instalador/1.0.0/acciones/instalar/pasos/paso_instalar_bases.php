<?php

class paso_instalar_bases extends paso
{
	protected $datos_servidor;
	protected $schema_reemplazar;	//Borra y crea el schema de negocio actual?
	protected $es_base_nueva; //Se trata de una migración de aplicacion activa?
		
	function conf()
	{
		$this->nombre = 'Base de datos';
	}
	
	//-----------------------------------------------------------------------
	//--------------------	PROCESAMIENTOS ----------------------------------
	//-----------------------------------------------------------------------

	
	function procesar()
	{
		if (! empty($_POST)) {
			$this->resetear_errores();
			$this->datos_servidor = $_POST;
			
			//-- Loguea los parametros
			$datos_debug = $this->datos_servidor;
			$datos_debug['clave'] = '******';
			$datos_debug['clave_aplicacion'] = '******';
			inst::logger()->debug('Parametros: '.var_export($datos_debug, true));
			
			
			$this->schema_reemplazar = null;
			if (isset($_POST['reemplazar_si'])) {
				$this->schema_reemplazar = true;
			}
			if (isset($_POST['reemplazar_no'])) {
				$this->schema_reemplazar = false;
			}			
			$this->verificar_parametros();
			//-- Verifica conexion superusuario			
			if (!$this->tiene_errores()) {
				$this->verificar_conexion($this->get_parametros_conexion_superusuario());
			}
			//-- Crea la base y el usuario	
			if (!$this->tiene_errores()) {
				try {
					$this->procesar_bases_datos();
				} catch (Exception  $e) {
					$this->set_error('carga', $e->getMessage());
					inst::logger()->error($e);
				}
			}			
			if (!$this->tiene_errores()) {
				$this->set_completo();
			}
		}
	}	
	
	function procesar_bases_datos()
	{
		$this->es_base_nueva = true;
		$this->crear_ini();
				
		//-- BASE DE DATOS
		if (!isset($this->datos_servidor['existe']) || !$this->datos_servidor['existe']) {
			//-- Es una nueva base de negocios, verificar si la base fisica existe
			if (! $this->verificar_existe_base($this->datos_servidor)) {
				$this->crear_base();
			}
		}
		
		//-- USUARIO. Necesita crear/dar permisos a un usuario especifico para la aplicacion o usa el superusuario?
		if (!isset($this->datos_servidor['usuario_aplicacion_admin']) || !$this->datos_servidor['usuario_aplicacion_admin']) {
			$this->crear_usuario_rol();
			if ($this->tiene_errores()) {		
				return;
			}			
		} else {
			//-- El usuario de la aplicacion es el superusuario
			$this->datos_servidor['usuario_aplicacion'] = $this->datos_servidor['usuario']; 
			$this->datos_servidor['clave_aplicacion'] = $this->datos_servidor['clave'];
		}		
		
		
		//-- NEGOCIO
		if (!isset($this->datos_servidor['existe']) || !$this->datos_servidor['existe']) {
			//-- El negocio no existia previamente, crearlo
			$this->crear_negocio();	
			if ($this->tiene_errores()) {
				return;
			}
		} else {
			//-- Supuestamente es una base de negocios existente, si no existe es un error
			if ($this->verificar_existe_base($this->datos_servidor, true)) {
				$this->actualizar_negocio();
			}
		}
	
		//-- TOBA. Crea el esquema y carga los datos de toba
		$this->crear_instancia_toba();
		
		
		//-- Post Instalacion
		$conexion = $this->get_conexion($this->get_parametros_conexion_regular());
		$manejador_negocio = inst::get_manejador_negocio($conexion);
		$manejador_negocio->post_instalacion($this->es_base_nueva);		
	}
	
	protected function crear_base()
	{
		$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario());
		$encoding = inst::configuracion()->get('base', 'encoding');
		//--Intenta crear la conexión con el encoding sugerido
		try {
			inst::db_manager()->crear_base($conexion, $this->datos_servidor['base'], $encoding);
		} catch (Exception $e) {
			inst::logger()->error("No fue posible crear la base con el encoding '$encoding', se utiliza el del cluster");
			inst::db_manager()->crear_base($conexion, $this->datos_servidor['base']);
		}
		inst::db_manager()->desconectar($conexion);
	}
	
	protected function crear_usuario_rol()
	{
		$parametros = $this->datos_servidor;
		$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario());
		inst::db_manager()->abrir_transaccion($conexion);
		$existe_usuario = inst::db_manager()->existe_rol($conexion, $this->datos_servidor['usuario_aplicacion']);
		if (! $existe_usuario) {
			//-- Crea el usuario sin privilegios
			inst::db_manager()->crear_usuario($conexion, $this->datos_servidor['usuario_aplicacion'], $this->datos_servidor['clave_aplicacion']);
		}
		
		$existe_rol = inst::db_manager()->existe_rol($conexion, $this->datos_servidor['rol_aplicacion']);
		if (! $existe_rol) {
			//-- Crea el rol 
			inst::db_manager()->crear_rol($conexion, $this->datos_servidor['rol_aplicacion']);
		}
		
		//-- Dar permisos sobre el rol al usuario
		inst::db_manager()->grant_rol($conexion, $this->datos_servidor['rol_aplicacion'], $this->datos_servidor['usuario_aplicacion']);
		
		//-- Dar permisos al usuario sobre la base
		inst::db_manager()->grant_base($conexion, $this->datos_servidor['base'], $this->datos_servidor['usuario_aplicacion']);
		
		//-- Cambiar el search_path del usuario
		$search_path = inst::configuracion()->get('base', 'schema').',public';
		inst::db_manager()->cambiar_search_path($conexion, $this->datos_servidor['usuario_aplicacion'], $search_path);
		
		
		inst::db_manager()->cerrar_transaccion($conexion);
		inst::db_manager()->desconectar($conexion);	
		
		//-- Chequear la conexión del usuario a la base
		$this->verificar_conexion($this->get_parametros_conexion_regular());
	}	
	
	protected function crear_instancia_toba()
	{
		$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario_base_proyecto());
		inst::db_manager()->set_encoding($conexion, 'LATIN1');
		inst::db_manager()->abrir_transaccion($conexion);
		if (inst::db_manager()->existe_schema($conexion, $this->datos_servidor['toba_schema'])) {
			inst::db_manager()->borrar_schema($conexion, $this->datos_servidor['toba_schema']);
		}
		inst::db_manager()->crear_schema($conexion, $this->datos_servidor['toba_schema']);
		inst::db_manager()->set_schema($conexion, $this->datos_servidor['toba_schema']);
		inst::db_manager()->retrazar_constraints($conexion);
		$toba_lib = new toba_lib($conexion);
		
		//-- Ejecuta la SQL de creación de la instancia de toba
		$proyectos = inst::configuracion()->get_proyectos_final_instancia();		
		$toba_lib->cargar_instancia();		
		
		//-- Crea el usuario señalado en la configuración y lo vincula a los proyectos
		if (! isset($_SESSION['datos_configuracion'])) {
			throw new inst_error('No se encontro la configuración del usuario toba a crear');
		}

		$usuario = $_SESSION['datos_configuracion'];
		$toba_lib->crear_usuario($usuario['usuario_id'], $usuario['usuario_clave'], $usuario['usuario_nombre'], $usuario['usuario_email']);
		foreach ($proyectos as $id_proyecto) {
			$perfiles = null;
			$perfil_datos = null;
			if ($id_proyecto == inst::configuracion()->get('proyecto', 'id')) {
				$perfiles = inst::configuracion()->get_lista('instalador', 'perfiles_funcionales');
				$perfil_datos = inst::configuracion()->get('instalador', 'perfil_datos', false, false);
			}
			$toba_lib->vincular_usuario($usuario['usuario_id'], $id_proyecto, $perfiles, $perfil_datos);		
		}
		
		//-- Actualiza los permisos del usuario de la conexion
		inst::db_manager()->grant_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $this->datos_servidor['toba_schema']);
		inst::db_manager()->grant_tablas_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $this->datos_servidor['toba_schema']);
		
		
		//Backward compatibility
		$schema_logs = 'toba_logs';
		if (inst::db_manager()->existe_schema($conexion, $schema_logs)) {
			inst::db_manager()->grant_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $schema_logs);
			inst::db_manager()->grant_tablas_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $schema_logs);	
			inst::db_manager()->grant_secuencias_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $schema_logs);
		}
		
		$schema_logs = $this->datos_servidor['toba_schema'] . '_logs';
		//Si existe el schema de logs, le asigno permisos para el usuario
		if (inst::db_manager()->existe_schema($conexion, $schema_logs)) {
			inst::db_manager()->grant_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $schema_logs);
			inst::db_manager()->grant_tablas_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $schema_logs);	
			inst::db_manager()->grant_secuencias_schema($conexion, $this->datos_servidor['usuario_aplicacion'], $schema_logs);
		}
		
		inst::db_manager()->cerrar_transaccion($conexion);
		inst::db_manager()->desconectar($conexion);
	}
	
	
	function crear_lenguajes_sql()
	{
		$lenguajes = inst::configuracion()->get_lista('base', 'languages');
		if (! empty($lenguajes)) {
			$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario_base_proyecto());
			foreach ($lenguajes as $lenguaje) {
				inst::db_manager()->crear_lenguaje($conexion, $lenguaje);
			}					
			inst::db_manager()->desconectar($conexion);
		}
	}

	
	protected function crear_negocio()
	{
		//-- Crea los lenguajes SQL, conectandose como superusuario		
		$this->crear_lenguajes_sql();
		
		//-- Inicia creación de schema, tablas y datos con usuario ordinario
		$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario_base_proyecto());
		$schema = inst::configuracion()->get('base', 'schema');
		inst::db_manager()->abrir_transaccion($conexion);		
		
		//Schema
		$existe_schema = inst::db_manager()->existe_schema($conexion,$schema);
		if ($existe_schema) {
			if (! isset($this->schema_reemplazar)) {
				$this->set_error('schema_existe', print_r(array($this->datos_servidor['base'], $schema), true));
				return;
			} elseif ($this->schema_reemplazar) {
				inst::db_manager()->borrar_schema($conexion, $schema);
			}
		}
		if (! inst::db_manager()->existe_schema($conexion,$schema)) {
			inst::db_manager()->crear_schema($conexion, $schema);
		}
		inst::db_manager()->set_schema($conexion, $schema);
		
		//-- Determina el grupo de datos a cargar
		$grupos = $this->get_grupos_datos();
		$id_grupo = null;
		if (! empty($grupos)) {
			if (! isset($this->datos_servidor['grupos_datos'])) {
				throw new inst_error('Falta definir un grupo de datos a cargar');
			} else {
				$id_grupo = $this->datos_servidor['grupos_datos'];
			}
			if (! isset($grupos[$id_grupo])) {
				throw new inst_error("No esta definido el grupo $id_grupo");
			} 
		}


		//-- Creación de la estructura		
		$archivos = inst::configuracion()->get_lista('base', 'estructura', null, false);
		if (isset($archivos)) {
			foreach ($archivos as $archivo) {
				$sql = file_get_contents(inst::configuracion()->get_dir_inst_aplicacion().'/'.$archivo);
				inst::db_manager()->ejecutar($conexion, $sql);
			}
		}

		//-- Retrasar constraints
		inst::db_manager()->retrazar_constraints($conexion);
				
		//-- Control al manejador de negocio
		$manejador_negocio = inst::get_manejador_negocio($conexion);
		$version = inst::configuracion()->get('proyecto', 'version');
		$manejador_negocio->crear_negocio($version, $id_grupo);
		//---
		
		if (! empty($grupos)) {
			//-- Datos basicos del grupo
			foreach($grupos[$id_grupo]['archivos_base'] as $archivo) {
				if ($archivo != '') {
					$sql = file_get_contents(inst::configuracion()->get_dir_inst_aplicacion().'/'.$archivo);
					inst::db_manager()->ejecutar($conexion, $sql);
				}
			}

			//-- Insertar datos
			foreach ($grupos[$id_grupo]['archivos'] as $id_archivo => $archivo) {
				if (isset($_POST[$id_grupo.'_'.$id_archivo])) {
					$sql = file_get_contents(inst::configuracion()->get_dir_inst_aplicacion().'/'.$archivo['path']);
					inst::db_manager()->ejecutar($conexion, $sql);
				}
			}
		}

		//-- Da permisos a los schemas del proyecto
		inst::db_manager()->grant_proyecto($conexion, $this->datos_servidor['usuario_aplicacion'], $schema, $this->datos_servidor['usuario']);
		
		inst::db_manager()->cerrar_transaccion($conexion);
		inst::db_manager()->desconectar($conexion);
	}

	protected function actualizar_negocio()
	{
		//-- Crea los lenguajes SQL, conectandose como superusuario		
		$this->crear_lenguajes_sql();		
		
		$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario_base_proyecto());
		inst::db_manager()->abrir_transaccion($conexion);		
		$schema = inst::configuracion()->get('base', 'schema');
		
		//----
		$manejador_negocio = inst::get_manejador_negocio($conexion);
		$version = inst::configuracion()->get('proyecto', 'version');
		$this->es_base_nueva = false;
		$manejador_negocio->migrar_negocio($version, $this->es_base_nueva);
		//----

		//-- Da permisos a los schemas del proyecto
		inst::db_manager()->grant_proyecto($conexion, $this->datos_servidor['usuario_aplicacion'], $schema, $this->datos_servidor['usuario']);
		
		inst::db_manager()->cerrar_transaccion($conexion);		
		inst::db_manager()->desconectar($conexion);
	}
	

	protected function crear_ini()
	{
		$path_ini = $_SESSION['path_instalacion'].'/instalacion/bases.ini';
		$id_proyecto = inst::configuracion()->get('proyecto', 'id');						//Fuerzo minusculas en el schema debido a que Postgres tiene case sensitive ecleptico.
		$nombre_instancia = inst::configuracion()->get_nombre_instancia();
		
		$this->datos_servidor['toba_schema'] = 'toba_'. strtolower($id_proyecto);
		$bases = new inst_ini();
		
		//--El usuario de la aplicacion es root?
		 
		if (isset($this->datos_servidor['usuario_aplicacion_admin']) && $this->datos_servidor['usuario_aplicacion_admin']) {
			$usuario = $this->datos_servidor['usuario'];
			$clave = $this->datos_servidor['clave'];
		} else {
			$usuario = $this->datos_servidor['usuario_aplicacion'];
			$clave = $this->datos_servidor['clave_aplicacion'];
		}
		
		//-- Instancia
		$datos_instancia = array();
		$datos_instancia['motor'] = 'postgres7';		
		$datos_instancia['profile'] = $this->datos_servidor['profile'];
		$datos_instancia['puerto'] = $this->datos_servidor['puerto'];
		$datos_instancia['usuario'] = $usuario;
		$datos_instancia['clave'] = $clave;
		$datos_instancia['base'] = $this->datos_servidor['base'];
		$datos_instancia['schema'] = $this->datos_servidor['toba_schema'];
		$encoding_original = 'LATIN1';		
		$datos_instancia['encoding'] = $encoding_original;
		$bases->agregar_entrada(inst::configuracion()->get_id_base_final_toba(), $datos_instancia);
		

		//--Proyecto
		$datos_proyecto = array();
		$datos_proyecto['motor'] = 'postgres7';		
		$datos_proyecto['profile'] = $this->datos_servidor['profile'];
		$datos_proyecto['puerto'] = $this->datos_servidor['puerto'];
		$datos_proyecto['usuario'] = $usuario;
		$datos_proyecto['clave'] = $clave;
		$datos_proyecto['base'] = $this->datos_servidor['base'];
		$datos_proyecto['schema'] = inst::configuracion()->get('base', 'schema');
		$encoding_original = inst::configuracion()->get('base', 'encoding');		
		$datos_proyecto['encoding'] = $encoding_original;
		$id_fuente = $nombre_instancia .' '.inst::configuracion()->get('proyecto', 'id').' '.inst::configuracion()->get('base', 'fuente');
		$bases->agregar_entrada($id_fuente, $datos_proyecto);		
		$bases->guardar($path_ini);
	}	

	//-----------------------------------------------------------------------
	//-----			VALIDACIONES
	//-----------------------------------------------------------------------
		
	protected function verificar_conexion($parametros)
	{
		//--- Puede conectarse?
		try {
			$conexion = inst::db_manager()->conectar($parametros);
			$this->controlar_version_postgres($conexion);			
			inst::db_manager()->desconectar($conexion);
		} catch (Exception $e) {
			$usuario = $parametros['usuario'];
			$base = $parametros['base'];
			$mensaje = "Problemas conect&aacute;ndose con el usuario '$usuario' a la base de datos '$base'. Por favor verifique los par&aacute;metros e int&eacute;ntelo nuevamente. A continuación el detalle del error:<br><pre> "
							.$e->getMessage();"</pre>";			
			$this->set_error('conexion', $mensaje);
		}

	}
	
	protected function verificar_existe_base($parametros, $obligatoria = false)
	{
		//---Existe ya la base?
		try {
			$conexion = inst::db_manager()->conectar($parametros);
			inst::db_manager()->desconectar($conexion);
			return true;
		} catch (Exception $e) {
			if ($obligatoria) {
				$this->set_error('base_no_existe', $parametros['base']);
			}
			return false;
		}		
	}

	protected function verificar_parametros()
	{
		if (trim($this->datos_servidor['profile']) == '') {
			$this->set_error('profile_vacio', 'Debe especificar la dirección IP o nombre del servidor donde se creará la base de datos');
		}
		if (trim($this->datos_servidor['puerto']) == '') {
			$this->set_error('puerto_vacio', 'Debe especificar el número de puerto donde esta ejecutandose el servidor postgres');
		} elseif (! is_numeric($this->datos_servidor['puerto'])) {
			$this->set_error('puerto_erroneo', 'Debe ser un número');			
		}
		if (trim($this->datos_servidor['base']) == '') {
			$this->set_error('base_vacio', 'Debe especificar el nombre que tomará la base de datos');
		}
		if (trim($this->datos_servidor['usuario']) == '') {
			$this->set_error('usuario_vacio', 'Debe especificar el nombre de usuario que usará para la conexión a la base de datos');
		}
	}	
	
	function controlar_version_postgres($conexion)
	{
		$controles = inst::controles();
		$habilitados = inst::configuracion()->get('controles');
		$id = 'version_postgres';
		if (isset($habilitados[$id])) {
			$parametros = explode('|', $habilitados[$id]);
			$severidad = array_shift($parametros);
			$param_faltantes = 3 - count($parametros);		//Se requieren al menos 3 parametros, no importa el valor
			for( $i = 0; $i < $param_faltantes; $i++) {
				$parametros[] = '';
			}
			//Luego de asegurarme que quedara en la posicion correcta, agrego la conexion
			$parametros[] = $conexion;			
			$res = call_user_func_array(array($controles, $id), $parametros);
			$res['severidad'] = $severidad;
			if ($res['error'] && $severidad == 'error') {
				$this->set_error($id, $res['titulo'].': '.$res['mensaje']);
			}
			if ($res['error']) {
				inst::logger()->debug("Fallo Control $id: {$res['mensaje']}");
			}			
		} 
	}		

	
	
	//-----------------------------------------------------------------------
	//--------------------	CONSULTAS --------------------------------------
	//-----------------------------------------------------------------------
	

	function get_conexion($parametros)
	{
		$conexion = inst::db_manager()->conectar($parametros);
		inst::db_manager()->set_encoding($conexion, inst::configuracion()->get('base', 'encoding'));
		return $conexion;
	}
	
	/**
	 * Retorna los parametros definidos para conectarse regularmente a la base (sin superusuario)
	 */
	function get_parametros_conexion_regular()
	{
		$parametros = $this->datos_servidor;
		if (!isset($this->datos_servidor['usuario_aplicacion_admin']) || !$this->datos_servidor['usuario_aplicacion_admin']) {
			$parametros['usuario'] = $parametros['usuario_aplicacion'];
			$parametros['clave'] = $parametros['clave_aplicacion'];
		}
		return $parametros;
	}
	
	function get_parametros_conexion_superusuario()
	{
		$parametros = $this->datos_servidor;
		$parametros['base'] = 'postgres';
		return $parametros;
	}	
	
	function get_parametros_conexion_superusuario_base_proyecto()
	{
		$parametros = $this->datos_servidor;
		return $parametros;
	}	
	
	function get_datos_servidor()
	{
		if (! isset($this->datos_servidor)) {
			return $this->get_datos_servidor_defecto();
		} else {
			return $this->datos_servidor;
		}
	}	
	
	function get_grupos_datos()
	{
		$grupos = array();
		$id_grupos =  inst::configuracion()->get('base', 'grupos_datos', null, false);
		if (! isset($id_grupos)) {
			return array();
		}
		$id_grupos = explode(',', $id_grupos);
		$id_grupos = array_map('trim', $id_grupos);
		foreach ($id_grupos as $id_grupo) {
			$datos_grupo = inst::configuracion()->get('grupo_'.$id_grupo);
			foreach ($datos_grupo as $id_dato => $dato) {
				if ($id_dato == 'nombre') {
					$grupos[$id_grupo]['nombre'] = $dato;
					$grupos[$id_grupo]['archivos'] = array();
				} elseif ($id_dato == 'archivos') {
					$grupos[$id_grupo]['archivos_base'] =  array_map('trim', explode(',', $dato));
				} else {
					$info = explode('|', $dato);
					$info = array_map('trim', $info);
					$grupos[$id_grupo]['archivos'][$id_dato]['path'] = $info[0];
					$grupos[$id_grupo]['archivos'][$id_dato]['nombre'] = $info[1];					
					$grupos[$id_grupo]['archivos'][$id_dato]['dependencias'] = $info[2];
				}
			}
		}
		return $grupos;
	}	
	
	function get_datos_servidor_defecto()
	{
		return array(
			'profile' => '127.0.0.1',
			'puerto' =>	'5432',
			'usuario' => 'postgres',
			'base'	=> inst::configuracion()->get('base', 'nombre'),
			'clave' => '',
			'usuario_aplicacion' => inst::configuracion()->get('base', 'usuario_postgres'),
			'rol_aplicacion' => inst::configuracion()->get('base', 'rol_postgres'),
			'clave_aplicacion' => inst::archivos()->generar_password(10)			
		);
	}
	

}

?>