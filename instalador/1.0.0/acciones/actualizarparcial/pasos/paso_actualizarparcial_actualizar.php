<?php

class paso_actualizarparcial_actualizar extends paso
{
	protected $parametros;

	protected $path_temp_toba;
	protected $path_temp_apl;
	protected $path_toba;
	protected $path_apl;	
	protected $datos_servidor;

	function conf()
	{
		$this->nombre = 'Actualizar';
	}


	function procesar()
	{
		if (! empty($_POST)) {
			$info_instalacion = inst::configuracion()->get_info_instalacion();
			
			//Se toma del post los datos del usuario y clave de conexión a la BD.
			$datos = $_POST;
			//Se carga la configuración de conexión a la bd utilizando el archivo bases.ini del dir de instalación.
			$datos['profile'] = $info_instalacion['base']['profile'];
			$datos['schema'] = $info_instalacion['base']['schema'];
			$datos['base'] = $info_instalacion['base']['base'];
			$datos['puerto'] = $info_instalacion['base']['puerto'];			
			
			$this->parametros = $datos;
			$this->datos_servidor = $datos;
			
			//-- Se aplican los scripts de actualización de la bd

			$conexion = $this->get_conexion($this->get_parametros_conexion_superusuario_base_proyecto());
			
			$manejador_negocio = inst::get_manejador_negocio($conexion);
			$version = inst::configuracion()->get('proyecto', 'version');

			$this->es_base_nueva = false;
			$manejador_negocio->migrar_negocio($version, $this->es_base_nueva);

			//Se aplica el reemplazo de los archivos.
			$this->procesar_actualizacion();
			
			//Desactivo los servicios web que hayan quedado en el sistema			
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

		$this->path_toba = $_SESSION['path_instalacion'].'/toba';
		$this->path_temp_toba = $this->path_toba.".$extra";
		$this->path_apl = $_SESSION['path_instalacion'].'/aplicacion';
		$this->path_temp_apl  = $this->path_apl.".$extra";
		$_SESSION['backup_toba'] = $this->path_temp_toba;
		$_SESSION['backup_aplicacion'] = $this->path_temp_apl;

		$this->cambiar_archivos();

	}

	function procesar_desactivacion_web_services()
	{		
		$parametros = $this->get_parametros_conexion();
		$conexion = $this->get_conexion($parametros);		
		$schema = $parametros['schema_toba'];
		inst::db_manager()->set_schema($conexion, $schema);
		
		//Desactivo los servicios web que hayan quedado en el sistema para forzar configuracion de paso actualizo el script del entorno
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
	 * Reemplaza los archivos por los de la actualización
	 */
	function cambiar_archivos()
	{
		include(inst::configuracion()->get_dir_inst_aplicacion() . '/archivos_instalador.php');			
		if(isset($archivos_procesar) && count($archivos_procesar) > 0)
		{
			foreach ( $archivos_procesar as $archivo ) {
				//Agregar o modificar
				if($archivo['action'] != 'D')
				{
					$x_origen = inst::configuracion()->get_dir_inst_aplicacion() .  $archivo['path'];
					$x_destino = $this->path_apl .  $archivo['path'];
					if ( is_dir( $x_origen )) {
						inst::archivos()->copiar_directorio( $x_origen, $x_destino);	
					} else {
						if (! copy( $x_origen, $x_destino )) {
							throw new inst_error("No fue posible copiar el archivo '$x_origen' hacia '$x_destino'");	
						}
					}	
				}else{
					//Eliminar
					$x_destino = $this->path_apl .  $archivo['path'];			
					inst::archivos()->eliminar_directorio($x_destino);		
				}				
			}			
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



	//-----------------------------------------------------------------------
	//--------------------	CONSULTAS --------------------------------------
	//-----------------------------------------------------------------------
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

	function get_conexion($parametros = null)
	{
		$conexion = inst::db_manager()->conectar($parametros);
		inst::db_manager()->set_encoding($conexion, inst::configuracion()->get('base', 'encoding'));
		return $conexion;
	}
	
	function get_parametros_conexion_superusuario_base_proyecto()
	{			
		$parametros = $this->datos_servidor;
		return $parametros;
	}		
	
	function get_parametros_conexion()
	{
		$info = inst::configuracion()->get_info_instalacion();
		return array_merge($info['base'], $this->get_parametros());
	}

}

?>