<?php

class configuracion
{
	protected $datos_ini;
	protected $archivo_ini;
	protected $info_instalacion;
	protected $ini_instalador;
	
	function __construct()
	{
		$archivo_ini = INST_DIR. '/instalador.ini';
		if (! file_exists($archivo_ini)) {
			throw new inst_error('El instalador debe contener un archivo de configuración instalador.ini, el mismo no se encuentra');
		}
		$this->ini_instalador = parse_ini_file($archivo_ini);
	}
	
	function set_proyecto($proyecto)
	{
		$this->archivo_ini = INST_DIR."/proyectos/$proyecto/aplicacion/proyecto.ini";
		if (! file_exists($this->archivo_ini)) {
			throw new inst_error("El proyecto '$proyecto' debe contener un archivo de configuración proyecto.ini ({$this->archivo_ini}), puede
									usar el archivo ejemplo.proyecto.ini que se encuentra en la raiz del instalador como base");
		}
		$this->datos_ini = parse_ini_file($this->archivo_ini,true);	
	}
	
	function es_debug()
	{
		return true;
	}
	
	//--- Informacion sobre el instalador
	
	function get_path_archivo_diagnostico()
	{
		$id_proyecto = $this->get('proyecto', 'id');
		return INST_DIR."/logs/diagnostico_$id_proyecto.zip"; 
	}
	
	function get_dir_inst_toba()
	{
		return INST_DIR.'/proyectos/'.$this->get('proyecto', 'id').'/toba';
	}

	function get_dir_inst_aplicacion()
	{
		return INST_DIR.'/proyectos/'.$this->get('proyecto', 'id').'/aplicacion';
	}
	
	function get_url_inst_toba()
	{
		return 'proyectos/'.$this->get('proyecto', 'id').'/toba/www';
	}

	function get_url_inst_aplicacion()
	{
		return 'proyectos/'.$this->get('proyecto', 'id').'/aplicacion/www';
	}	
	
	
	function get_url_logo_aplicacion()
	{
		return $this->get_url_inst_aplicacion().$this->get('instalador', 'logo', '/img/logo.gif',false);		
	}
	
	function get($seccion, $clave=null, $defecto=null, $obligatorio=true)
	{
		if (! isset($this->datos_ini)) {
			throw new inst_error("No se ha seleccionado aún el proyecto");
		}
		if(isset($clave) && isset($this->datos_ini[$seccion][$clave])) {
			return $this->datos_ini[$seccion][$clave];
		}
		if (! isset($clave) && isset($this->datos_ini[$seccion])) {
			return $this->datos_ini[$seccion];
		}
		if ($obligatorio) {
			throw new inst_error("No se encuentra definido el parámetro $clave de la sección $seccion en {$this->archivo_ini}");
		} else {
			return $defecto;
		}
	}
	
	function get_lista($seccion, $clave=null, $defecto=null, $obligatorio=true)
	{
		$dato = $this->get($seccion, $clave, $defecto, $obligatorio);
		if (isset($dato)) {
			$dato = array_map('trim', explode(',', $dato));
		}
		return $dato;
	}	
	
	//--- Informacion sobre lo instalado
	function get_path_logs($original=false)
	{
		if (!$original && isset($_SESSION['path_instalacion'])) {
			$base = $this->get_path_conf_instalador();
		} else {
			$base = INST_DIR;
		}
		return $base.'/logs';
	}
	
	function get_path_conf_instalador()
	{
		if (isset($_SESSION['path_instalacion'])) {
			return dirname($_SESSION['path_instalacion']).'/instalador';
		} else {
			return INST_DIR;
		}
	}
	
	
	function get_url_final_proyecto()
	{
		return $_SESSION['url_instalacion'];
	}	
	
	function get_url_final_proyecto_extra($id_proyecto)
	{
		return $_SESSION['url_instalacion']."_$id_proyecto";
	}	
	
	function get_id_base_final_toba()
	{
		return 'toba_'.$this->get('proyecto', 'id');
	}

	function get_proyectos_final_instancia()
	{
		$id_proyecto = $this->get('proyecto', 'id');
		$extra = $this->get_lista('empaquetado', 'proyectos_extra');
		$extra[] = $id_proyecto;
		return $extra; 
	}
	
	function es_instalacion_produccion()
	{
		return (! isset($this->ini_instalador['instalacion_produccion']) || ($this->ini_instalador['instalacion_produccion'] == 1));
	}
	
	function get_nombre_instancia()
	{
		return ($this->es_instalacion_produccion()) ? 'produccion' : 'desarrollo';
	}	
	
	/**
	 * Retorna información sobre la aplicacion y toba instalados previamente
	 */
	function get_info_instalacion()
	{
		if (! isset($this->info_instalacion)) {
			$datos = array();
			$nombre_instancia = $this->get_nombre_instancia();
			
			//-- Sistema
			$ini_instancia = new inst_ini($_SESSION['path_instalacion']."/instalacion/i__$nombre_instancia/instancia.ini");
			$ini_proyecto = new inst_ini($_SESSION['path_instalacion'].'/aplicacion/proyecto.ini');
			$datos_proyecto = $ini_proyecto->get_datos_entrada('proyecto');
			$datos['sistema'] = $ini_instancia->get_datos_entrada(inst::configuracion()->get('proyecto', 'id'));
			$datos['sistema']['version_actual'] = $datos_proyecto['version']; 
			
			//-- Base
			$path_ini = $_SESSION['path_instalacion'].'/instalacion/bases.ini';
			$ini_bases = new inst_ini($path_ini);
			$id_fuente = $nombre_instancia .' '. inst::configuracion()->get('proyecto', 'id').' '.inst::configuracion()->get('base', 'fuente');
			$datos['base'] = $ini_bases->get_datos_entrada($id_fuente);
			$datos_toba = $ini_bases->get_datos_entrada(inst::configuracion()->get_id_base_final_toba());
			$datos['base']['schema_toba'] = $datos_toba['schema'];
			$datos['base']['usuario_toba'] = $datos_toba['usuario'];
			$this->info_instalacion = $datos;
		}
		return $this->info_instalacion;
	}	
}

?>