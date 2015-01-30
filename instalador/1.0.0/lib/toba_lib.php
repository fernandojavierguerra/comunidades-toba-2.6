<?php

class toba
{
	function logger()
	{
		return inst::logger();
	}
}

class base_intermedia
{
	protected $conexion;
	
	function __construct($conexion)
	{
		$this->conexion = $conexion;
	}
	
	function __call($funcion, $args) {
		$args = array_merge(array($this->conexion), $args);
		return call_user_func_array(array(inst::db_manager(), $funcion), $args);
    }
	
}


class toba_lib
{
	protected $conexion;
	
	/**
	 * @var toba_instalador
	 */
	protected $entorno_toba;
	protected $path_toba;
	protected $nombre_instancia;
	
	function __construct($conexion=null, $path_toba=null)
	{
		$this->conexion = $conexion;
		if (isset($path_toba)) {
			$this->path_toba = $path_toba;		
		} else {
			$this->path_toba = $_SESSION['path_instalacion'].'/toba';
		}
	}
	
	protected function cargar_entorno_toba()
	{
		if (! isset($this->entorno_toba)) {
			$this->parchear_toba();
			$path_php = $this->path_toba.'/php';
			inst::archivos()->agregar_path_php($path_php);
			require_once($path_php."/instalacion/toba_instalador.php");
			$_SERVER['TOBA_INSTALACION_DIR'] = $_SESSION['path_instalacion'].'/instalacion';
			$this->entorno_toba = new toba_instalador();
			if (isset($this->conexion)) {
				$base_intermedia = new base_intermedia($this->conexion);		
				$this->entorno_toba->set_conexion($base_intermedia);
			}
			$this->nombre_instancia = inst::configuracion()->get_nombre_instancia();
		}		
	}	
	
	protected function parchear_toba()
	{
		$version_actual = trim(file_get_contents($_SESSION['path_instalacion'].'/toba/VERSION'));
		if ($version_actual == '1.2.4') {
			//-- Se mejora el esquema de exportacion/carga de metadatos
			$archivos = array(
				'/php/modelo/toba_modelo_instalacion.php',
				'/php/instalacion/toba_instalador.php'
			);
			foreach ($archivos as $archivo) {
				copy(inst::configuracion()->get_dir_inst_toba().$archivo, $_SESSION['path_instalacion'].'/toba/'.$archivo);
			}
		}
	}
	
	
	function crear_usuario($id, $clave, $nombre, $email)
	{
		$this->cargar_entorno_toba();		
		$this->verificar_clave_usuario($clave);		
		$this->entorno_toba->get_instancia($this->nombre_instancia)->agregar_usuario($id, $nombre, $clave, $email);
	}
	
	function verificar_clave_usuario($clave)
	{
		$this->cargar_entorno_toba();			
		//Busco el tamaño minimo de la clave
		//if (inst::configuracion()->es_instalacion_produccion()) {
		$largo_clave = inst::configuracion()->get('proyecto', 'pwd_largo_minimo', '8', false);		
		//} 
		//Verifico que la clave cumpla ciertos requisitos basicos
		if (method_exists('toba_usuario','verificar_composicion_clave' )) {
			toba_usuario::verificar_composicion_clave($clave, $largo_clave);
		}
	}	
	
	function vincular_usuario($usuario, $proyecto, $perfiles_func, $perfil_datos)
	{
		$this->cargar_entorno_toba();
		$proyecto = $this->entorno_toba->get_instancia($this->nombre_instancia)->get_proyecto($proyecto);
		if (! isset($perfiles_func)) {
			$perfiles_func = array($proyecto->get_grupo_acceso_admin());
		}
		if (! isset($perfil_datos) || trim($perfil_datos) == '') {
			$perfil_datos = 'NULL';
		} else {
			$perfil_datos = "'$perfil_datos'";
		}
		foreach ($perfiles_func as $perfil) {
			$proyecto->vincular_usuario($usuario, $perfil, $perfil_datos);			
		}
	}
	
	function cargar_instancia()
	{
		inst::logger()->debug('Cargando metadatos');
		$this->cargar_entorno_toba();
		$errores = $this->entorno_toba->get_instancia($this->nombre_instancia)->cargar_autonomo();
		return $errores;
	}
	
	function exportar_instancia()
	{
		inst::logger()->debug('Exportando metadatos locales');
		$this->cargar_entorno_toba();
		$this->entorno_toba->get_instancia($this->nombre_instancia)->exportar_local();
			
	}
	
	function ejecutar_ventana_migracion_instancia()
	{
		inst::logger()->debug('Ejecutando ventana de migracion de version de la instancia anterior');
		$this->cargar_entorno_toba();
		$instancia = $this->entorno_toba->get_instancia($this->nombre_instancia);
		if (method_exists($instancia, 'ejecutar_ventana_migracion_version')) {
			$instancia->ejecutar_ventana_migracion_version(false);
		}
	}
	
	function recompilar_perfiles() 
	{
		$this->cargar_entorno_toba();
		$toba_instancia = $this->entorno_toba->get_instancia($this->nombre_instancia);
		foreach($toba_instancia->get_lista_proyectos_vinculados() as $proyecto ) {
			if ($toba_instancia->get_proyecto_usar_perfiles_propios($proyecto)) {
				inst::logger()->debug('Recompilando metadatos de perfiles proyecto '.$proyecto);
				$toba_proyecto = $toba_instancia->get_proyecto($proyecto);
				$toba_proyecto->compilar_metadatos_generales_grupos_acceso(true);
			}
		}	
	}
	
	function generar_consola_administrativa()
	{
		$id_instancia = inst::configuracion()->get_nombre_instancia();
		$toba_dir = $this->path_toba;		
		$path_archivo = $toba_dir . '/bin';
		$instalacion_dir = (isset($_SERVER['TOBA_INSTALACION_DIR'])) ? $_SERVER['TOBA_INSTALACION_DIR'] : $_SESSION['path_instalacion'].'/instalacion';
		
		$version = new inst_version(file_get_contents($toba_dir."/VERSION"));
		$release = $version->get_release();		
		
		$version_limite = new inst_version('2.4.0');		
		if ($version->es_menor($version_limite)) {									//A partir de la version 2.4.0 las variables de entorno van en mayusculas
			$const_toba_dir = 'toba_dir';
			$const_toba_instancia = 'toba_instancia';
			$const_toba_instalacion = 'toba_instalacion_dir';
		} else {
			$const_toba_dir = 'TOBA_DIR';
			$const_toba_instancia = 'TOBA_INSTANCIA';
			$const_toba_instalacion = 'TOBA_INSTALACION_DIR';			
		}
		
		if (archivos::es_windows()) {			
			$bat = "@echo off\n";
			$bat .= "set $const_toba_dir=".$toba_dir."\n";
			$bat .= "set $const_toba_instancia=$id_instancia\n";
			$bat .= "set $const_toba_instalacion=$instalacion_dir\n";
			$bat .= "set PATH=%PATH%;%$const_toba_dir%/bin\n";
			$bat .= "echo Entorno cargado.\n";
			$bat .= "echo Ejecute 'toba' para ver la lista de comandos disponibles.\n";
			
			$path = archivos::path_a_plataforma( $path_archivo."\\entorno_toba_$release.bat");			
			file_put_contents($path, $bat);
		} else {
			$bat = "export $const_toba_dir=".$toba_dir."\n";
			$bat .= "export $const_toba_instancia=$id_instancia\n";
			$bat .= "export $const_toba_instalacion=$instalacion_dir\n";
			$bat .= 'export PATH="$'.$const_toba_dir.'/bin:$PATH"'."\n";
			$bat .= "echo \"Entorno cargado.\"\n";
			$bat .= "echo \"Ejecute 'toba' para ver la lista de comandos disponibles.\"\n";
						
			$path = archivos::path_a_plataforma($path_archivo . "/entorno_toba_$release.sh");						
			file_put_contents($path, $bat);
			chmod($path, 0755);
		}		
	}
	
	function desactivar_servicios_web($proyecto)
	{
		$this->cargar_entorno_toba();
		$proyecto = $this->entorno_toba->get_instancia($this->nombre_instancia)->get_proyecto($proyecto);
		if (method_exists($proyecto, 'desactivar_servicios_web')) {
			return $proyecto->desactivar_servicios_web();
		}
	}
	
	static function get_proyectos_disponibles()
	{
		$proyectos = array();
		$directorio_proyectos = inst::configuracion()->get_dir_inst_toba() . '/proyectos';
		$lista = inst::archivos()->get_archivos_directorio($directorio_proyectos, '/proyecto.ini/', true);
		foreach ($lista as $archivo) {
			$ini = new inst_ini($archivo);
			$pr = $ini->get_datos_entrada('proyecto');
			$proyectos[$archivo] = $pr['id'];
			unset($ini);
		}
		return $proyectos;
	}
	
}
?>