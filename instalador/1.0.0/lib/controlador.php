<?php

class controlador
{
	protected $paso;
	protected $accion;
	protected $proyecto;
	protected $nombre_accion_base = 'instalar';
		
	function procesar()
	{
		if (inst::configuracion()->es_debug()) {
			error_reporting(E_ALL);
		} else {
			error_reporting(0);
		}
		ini_set("max_execution_time", 0);
		ini_set("output_buffering", 0);		
		inst::sesion()->iniciar();
		
		
		if (isset($_GET['eliminar']) && $_GET['eliminar'] == 1) {
			inst::archivos()->eliminar_directorio(INST_DIR);
			if (!file_exists(INST_DIR)) {
				die('<h3>Instalador Eliminado</h3>');
			} else {
				die("<h3>No se pudo eliminar el directorio. Controle los permisos y refresque esta página nuevamente</h3>");
			}
		}
		
		$reiniciar = false;
		if (isset($_GET['reiniciar'])) {
			$reiniciar = true;
			$_SESSION = array();
		}
		$this->determinar_accion($reiniciar);
		if (isset($this->accion)) {
			//Se eligio una accion, se procesa		
			$this->accion->procesar();
			$this->accion->generar();
		} else {
			//--Elegir una acción
			include(INST_DIR.'/lib/templates_comunes/elegir_accion.php');
		}
		$this->destruir();
	}
	
	function destruir()
	{
		if (isset($this->accion)) {
			$this->accion->destruir();		
			$_SESSION[$this->accion->get_id()] = $this->accion;
		}
	}
	
	function accion()
	{
		return $this->accion;
	}
	

	function get_link_diagnostico()
	{
		return '?accion=diagnosticar';
	}
	
	protected function determinar_accion($reiniciar=false)
	{
		//-- Determino el proyecto a utilizar
		$carpeta = INST_DIR.'/proyectos';
		$proyectos = inst::archivos()->get_subdirectorios($carpeta);
		if (empty($proyectos)) {
			throw new inst_error("La carpeta '$carpeta' no contiene ningún proyecto a instalar");
		}
		if (count($proyectos) == 1) {
			inst::configuracion()->set_proyecto(basename($proyectos[0]));
		} else {
			throw new inst_error("Instalación multi-proyecto no soportada. La carpeta '$carpeta' contiene más de un proyecto.");
		}
		
		if (isset($_GET['accion'])) {
			$id_accion = $_GET['accion'];
		} else {
			//-- Si no se eligio una acción y hay una sola disponible, seleccionarla
			$acciones = inst::configuracion()->get_lista('instalador', 'acciones', '', false);
			if (count($acciones) == 1) {
				$id_accion = $acciones[0];
			} else {
				//No se puede determinar una acción, que lo haga visualmente el usuario
				return;
			}
		}
			
		$this->validar_accion($id_accion);
		if (isset($_SESSION[$id_accion]) && ! $reiniciar) {			
			//-- La acción ya esta inicializada
			$this->accion = $_SESSION[$id_accion];
		} else {
			$clase_accion = 'accion_' . $id_accion;
			$archivo_accion = $this->get_accion_archivo_alternativo($id_accion);
			if (! is_null($archivo_accion)) {
				$clase_accion = basename($archivo_accion, '.php');
			}
			$this->accion = new $clase_accion($reiniciar);
		}
		inst::logger()->debug('Accion: "'.$this->accion->get_nombre().'"');
	}
	
	protected function validar_accion($accion)
	{
		$es_valida = true;		
		
		// Verifico si la accion esta entre la lista de disponibles + diagnosticar
		$acc_disponibles = inst::configuracion()->get_lista('instalador', 'acciones');
		$acc_disponibles[] = 'diagnosticar';
		$es_valida = $es_valida && (in_array($accion, $acc_disponibles));
		
		//Busco si tiene un path alternativo
		$archivo_accion = $this->get_accion_archivo_alternativo($accion);
		if (! is_null($archivo_accion)) {
			$es_valida = $es_valida && (file_exists($archivo_accion));			
		} else {									//Default en el directorio de acciones del instalador
			$acciones = inst::archivos()->get_subdirectorios(INST_DIR.'/acciones');
			$acciones = array_map('basename', $acciones);
			$es_valida = $es_valida && (in_array($accion, $acciones));			
		}		
		
		if (! $es_valida) {
			throw new inst_error("La accion {$accion} no es válida");	
		}
	}
	
	private function get_accion_archivo_alternativo($id_accion)
	{
		$archivo_accion = null;
		$path_archivo = inst::configuracion()->get('instalador_clases_redefinidas','accion_' .$id_accion, null, false);
		if (! is_null($path_archivo)) {
			$archivo_accion =  inst::configuracion()->get_dir_inst_aplicacion().'/'. $path_archivo;
		}
		return $archivo_accion;
	}
}
?>