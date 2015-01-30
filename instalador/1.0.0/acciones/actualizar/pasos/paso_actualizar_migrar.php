<?php

class paso_actualizar_migrar extends paso
{
	protected $parametros;

	protected $path_temp_toba;
	protected $path_temp_apl;
	protected $path_toba;
	protected $path_apl;

	function conf()
	{
		$this->nombre = 'Migración';
	}


	function procesar()
	{
		if (! empty($_POST)) {
			$this->resetear_errores();
			$this->parametros = $_POST;

			//-- Loguea los parametros
			$datos_debug = $this->parametros;
			$datos_debug['clave'] = '******';
			inst::logger()->debug('Parametros: '.var_export($datos_debug, true));

			$this->verificar_parametros();
			//-- Verifica conexion superusuario
			if (!$this->tiene_errores()) {
				$this->verificar_conexion();
			}
			//-- Crea la base y el usuario
			if (!$this->tiene_errores()) {
				try {
					$this->procesar_actualizacion();
				} catch (Exception  $e) {
					$this->set_error('carga', $e->getMessage());
					inst::logger()->error($e);
				}
			}
			
			if (!$this->tiene_errores()) {
				$this->procesar_desactivacion_web_services();		
			}			
			
			if (!$this->tiene_errores()) {
				$this->set_completo();
			}						
		}
	}

	function procesar_actualizacion()
	{
		$datos_actuales = inst::configuracion()->get_info_instalacion();;
		$extra = $datos_actuales['sistema']['version_actual'].'.backup';
		$conexion = $this->get_conexion();
		$this->path_toba = $_SESSION['path_instalacion'].'/toba';
		$this->path_temp_toba = $this->path_toba.".$extra";
		$this->path_apl = $_SESSION['path_instalacion'].'/aplicacion';
		$this->path_temp_apl  = $this->path_apl.".$extra";
		$_SESSION['backup_toba'] = $this->path_temp_toba;
		$_SESSION['backup_aplicacion'] = $this->path_temp_apl;


		try {
			//-- Revisa que los lenguajes sql esten creados
			inst::db_manager()->abrir_transaccion($conexion);
			$lenguajes = inst::configuracion()->get_lista('base', 'languages');
			if (! empty($lenguajes)) {
				foreach ($lenguajes as $lenguaje) {
					inst::db_manager()->crear_lenguaje($conexion, $lenguaje);
				}
			}
			$this->eliminar_temporales();
			$this->cambiar_archivos();
			$this->actualizar_modo_mantenimiento();
			$this->actualizar_base_toba($conexion);
			$this->actualizar_base_negocio($conexion);
			inst::db_manager()->cerrar_transaccion($conexion);

			//-- Ventana de migración interna del proyecto
			$manejador_negocio = inst::get_manejador_negocio($conexion);
			$version = inst::configuracion()->get('proyecto', 'version');
			$manejador_negocio->migrar_codigo($version, $this->path_temp_apl, $this->path_apl);
			$manejador_negocio->post_actualizacion($version,$this->path_apl);			
		} catch (Exception $e) {
			inst::db_manager()->abortar_transaccion($conexion);
			$this->restaurar_temporales();
			throw $e;
		}
	}

	function procesar_desactivacion_web_services()
	{
		$conexion = $this->get_conexion();
		$parametros = $this->get_parametros_conexion();
		$schema = $parametros['schema_toba'];
		inst::db_manager()->set_schema($conexion, $schema);
		
		//Desactivo los servicios web que hayan quedado en el sistema para forzar configuracion y de paso genero el script del entorno de consola
		try {
			$toba_lib = new toba_lib($conexion);
			$toba_lib->generar_consola_administrativa();	
			if (method_exists($toba_lib, 'desactivar_servicios_web')) {
				$proyecto = inst::configuracion()->get('proyecto', 'id');
				$_SESSION['servicios_web_desactivados'] = $toba_lib->desactivar_servicios_web($proyecto);
			}
		} catch (Exception $e) {
			$this->set_error('Problema desactivando nuevos WS', $e->getMessage());
		}
	}
	
	//----------------------------------------------------------------------------
	//------	ARCHIVOS
	//----------------------------------------------------------------------------

	/**
	 * Mueve los archivos actuales de toba y la aplicacion y los reemplaza por la nueva versión de ambos
	 */
	function cambiar_archivos()
	{
		if (! rename($this->path_toba, $this->path_temp_toba)) {
			throw new inst_error("No fue posible renombrar la carpeta '{$this->path_toba}' hacia '{$this->path_temp_toba}'");
		}
		if (! rename($this->path_apl, $this->path_temp_apl)) {
			throw new inst_error("No fue posible renombrar la carpeta '{$this->path_apl}' hacia '{$this->path_temp_apl}'");
		}
		inst::archivos()->copiar_directorio(inst::configuracion()->get_dir_inst_toba(), $this->path_toba);
		inst::archivos()->copiar_directorio(inst::configuracion()->get_dir_inst_aplicacion(), $this->path_apl);
		
		
		//Restaura carpeta personalización
		if (file_exists($this->path_temp_apl.'/personalizacion')) {
			 inst::archivos()->copiar_directorio($this->path_temp_apl.'/personalizacion', $this->path_apl.'/personalizacion');
		}
	}

	function eliminar_temporales()
	{
		if (file_exists($this->path_temp_toba)) {
			inst::archivos()->eliminar_directorio($this->path_temp_toba);
		}
		if (file_exists($this->path_temp_apl)) {
			inst::archivos()->eliminar_directorio($this->path_temp_apl);
		}
	}

	function restaurar_temporales()
	{
		if (file_exists($this->path_toba)) {
			inst::archivos()->eliminar_directorio($this->path_toba);
		}
		if (file_exists($this->path_apl)) {
			inst::archivos()->eliminar_directorio($this->path_apl);
		}
		if (! rename($this->path_temp_toba, $this->path_toba)) {
			throw new inst_error("Imposible restaurar la carpeta '{$this->path_temp_toba}' hacia '{$this->path_toba}'");
		}
		if (! rename($this->path_temp_apl, $this->path_apl)) {
			throw new inst_error("Imposible restaurar la carpeta '{$this->path_temp_apl}' hacia '{$this->path_apl}'");
		}
	}

	function actualizar_modo_mantenimiento()
	{
		//Coloco el sistema nuevamente en modo mantenimiento ya que el cambiar_archivos copio el nuevo proyecto.ini
		if (file_exists($this->path_apl . '/proyecto.ini')) {
			$ini = new inst_ini($this->path_apl.'/proyecto.ini');
			$datos = $ini->get_datos_entrada('proyecto');
			if (!isset($datos['modo_mantenimiento']) || $datos['modo_mantenimiento'] == '0') {
				$datos['modo_mantenimiento'] = 1;
				$ini->set_datos_entrada('proyecto', $datos);
				$ini->guardar();
			}
		}
	}
	
	//----------------------------------------------------------------------------
	//------	BASE DE DATOS
	//----------------------------------------------------------------------------

	function actualizar_base_toba($conexion)
	{
		//-- Prepara la conexión
		$parametros = $this->get_parametros_conexion();
		$schema = $parametros['schema_toba'];
		inst::db_manager()->set_encoding($conexion, 'LATIN1');
		inst::db_manager()->set_schema($conexion, $schema);
		$datos_actuales = inst::configuracion()->get_info_instalacion();
		$schema_backup = $parametros['schema_toba'].'_'.$datos_actuales['sistema']['version_actual'];
		$_SESSION['backup_schema_toba'] = $schema_backup;
		$_SESSION['backup_schema_toba_conexion'] = $parametros;


		//Ejecuta una ventana de migracion de la instancia
		$toba_lib = new toba_lib($conexion, $this->path_toba);
		$toba_lib->ejecutar_ventana_migracion_instancia();		
		
		//Backupea el schema de toba
		if (inst::db_manager()->existe_schema($conexion, $schema_backup)) {
			inst::db_manager()->borrar_schema($conexion, $schema_backup);
		}
		inst::db_manager()->renombrar_schema($conexion, $schema, $schema_backup);

		//-- Crea un nuevo esquema
		inst::db_manager()->crear_schema($conexion, $schema);
		inst::db_manager()->set_schema($conexion, $schema);
		inst::db_manager()->retrazar_constraints($conexion);
		
		//-- Ejecuta la SQL de creación de la instancia de toba		
		$errores = $toba_lib->cargar_instancia();
		$_SESSION['errores_perfiles'] = $errores;

		//-- Recompila metadatos de perfiles si fueron personalizados
		$toba_lib->recompilar_perfiles();
		
		//-- Actualiza los permisos del usuario de la conexion
		$info = inst::configuracion()->get_info_instalacion();
		inst::db_manager()->grant_schema($conexion, $info['base']['usuario_toba'], $schema);
		inst::db_manager()->grant_tablas_schema($conexion, $info['base']['usuario_toba'], $schema);
		
		//Backward compatibility
		$schema_logs = 'toba_logs';
		if (inst::db_manager()->existe_schema($conexion, $schema_logs)) {
			inst::db_manager()->grant_schema($conexion,$info['base']['usuario_toba'], $schema_logs);
			inst::db_manager()->grant_tablas_schema($conexion, $info['base']['usuario_toba'], $schema_logs);	
			inst::db_manager()->grant_secuencias_schema($conexion, $info['base']['usuario_toba'], $schema_logs);
		}
		
		$schema_logs = $schema .'_logs';
		//Si existe el schema de logs, le asigno permisos para el usuario
		if (inst::db_manager()->existe_schema($conexion, $schema_logs)) {
			inst::db_manager()->grant_schema($conexion,$info['base']['usuario_toba'], $schema_logs);
			inst::db_manager()->grant_tablas_schema($conexion, $info['base']['usuario_toba'], $schema_logs);	
			inst::db_manager()->grant_secuencias_schema($conexion, $info['base']['usuario_toba'], $schema_logs);
		}
	}

	function actualizar_base_negocio($conexion)
	{
		$parametros = $this->get_parametros_conexion();
		inst::db_manager()->set_schema($conexion, $parametros['schema']);

		//---------
		$manejador_negocio = inst::get_manejador_negocio($conexion);
		$version = inst::configuracion()->get('proyecto', 'version');
		$manejador_negocio->migrar_negocio($version, false);
		//---------

		//-- Actualiza los permisos del usuario ordinario
		$info = inst::configuracion()->get_info_instalacion();
		
		//-- Da permisos a los schemas del proyecto
		inst::db_manager()->grant_proyecto($conexion, $info['base']['usuario'], $parametros['schema'], $this->parametros['usuario']);
	}

	//-----------------------------------------------------------------------
	//-----			VALIDACIONES
	//-----------------------------------------------------------------------

	protected function verificar_conexion()
	{
		//--- Puede conectarse?
		try {
			$parametros = $this->get_parametros_conexion();
			$base = $parametros['base'];
			$conexion = inst::db_manager()->conectar($parametros);
			inst::db_manager()->desconectar($conexion);

			//-- Existen los esquemas?
			if (! inst::db_manager()->existe_schema($conexion, $parametros['schema'])) {
				$this->set_error('falta_schema', "No existe el esquema '{$parametros['schema']}' en la base '$base'");
			}
			if (! inst::db_manager()->existe_schema($conexion, $parametros['schema_toba'])) {
				$this->set_error('falta_schema', "No existe el esquema '{$parametros['schema_toba']}' en la base '$base'");
			}

		} catch (Exception $e) {
			$usuario = $parametros['usuario'];
			$mensaje = "Problemas conect&aacute;ndose con el usuario '$usuario' a la base de datos '$base'. Por favor verifique los par&aacute;metros e int&eacute;ntelo nuevamente. A continuación el detalle del error:<br><pre> "
							.$e->getMessage();"</pre>";
			$this->set_error('conexion', $mensaje);
		}
	}

	protected function verificar_parametros()
	{
		if (trim($this->parametros['usuario']) == '') {
			$this->set_error('usuario_vacio', 'Debe especificar el nombre de usuario que usará para la conexión a la base de datos');
		}
	}

	//-----------------------------------------------------------------------
	//--------------------	CONSULTAS --------------------------------------
	//-----------------------------------------------------------------------


	function get_conexion()
	{
		$parametros = $this->get_parametros_conexion();
		$conexion = inst::db_manager()->conectar($parametros);
		inst::db_manager()->set_encoding($conexion, inst::configuracion()->get('base', 'encoding'));
		return $conexion;
	}

	function get_parametros_conexion()
	{
		$info = inst::configuracion()->get_info_instalacion();
		return array_merge($info['base'], $this->get_parametros());
	}

	function get_parametros()
	{
		if (isset($this->parametros)) {
			return $this->parametros;
		} else {
			return array(
				'usuario' => 'postgres',
				'clave'	  => ''
			);
		}
	}

}

?>
