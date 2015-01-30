<?php

class paso_actualizarparcial_destino extends paso
{
	protected $path;
	
	function conf()
	{
		$this->nombre = 'Directorio Destino';
	}
	
	protected function get_path_predeterminado()
	{
		$carpeta = inst::configuracion()->get('instalador', 'carpeta_prefijo');
		$carpeta = inst::archivos()->path_a_plataforma($carpeta);
		if (INST_ES_WINDOWS) {
			$path = "C:\\$carpeta";
		} else {
			$path = "/usr/local/$carpeta";
		}
		$path_full = $path.INST_SEP_CARP.inst::configuracion()->get('instalador','carpeta_sufijo');
		if (file_exists($path_full) && is_dir($path_full)) {
			return $path;
		} else {
			return '';
		}
	}
	
	function get_path()
	{
		if (isset($this->path)) {
			return dirname($this->path);
		} else {
			return $this->get_path_predeterminado();
		}
	}
	
	function procesar()
	{
		if(isset($_POST['carpeta'])) {
			$this->resetear_errores();			
			$this->path = trim($_POST['carpeta']).INST_SEP_CARP.inst::configuracion()->get('instalador','carpeta_sufijo');
			
			$this->verificar_carpeta();
			
			if (! $this->tiene_errores()) {
				$this->exportar_metadatos_toba();
			}
			if (! $this->tiene_errores()) {
				$this->set_completo();
			}
		}
	}	
	
	function verificar_carpeta()
	{
		$this->verificar_carpeta_destino($this->path);
		if ($this->tiene_errores()) {
			return;
		}		
		
		$timestamp = date('d_m_Y-H_i');
		$path_nuevo_logs = inst::configuracion()->get_path_logs();
		$this->verificar_carpeta_writable($this->path, false);
		if ($this->tiene_errores()) {
			return;
		}

		//-- Rastros del instalador anterior
		$path_instalador = dirname($this->path).'/instalador';
		if (! file_exists($path_instalador) && ! @mkdir($path_instalador, 0755, true)) {
			$this->set_error('creacion_error', $this->get_mensaje_error_creacion($path_instalador));
			return;
		}

		$this->verificar_carpeta_writable($path_instalador);
		if ($this->tiene_errores()) {
			return;
		}	
				
		//-- Aplicacion existente
		$path_ini = $this->path.'/aplicacion/proyecto.ini';
		if (! file_exists($path_ini)) {
			$this->set_error('falta_ini', "No se encuentra el archivo '$path_ini'");
			$this->set_error('instalacion_anterior', "El directorio '{$this->path}'' no parece contener una instalación anterior de ".inst::configuracion()->get('proyecto', 'nombre'));
			return;			
		}
		
		//-- Publicar el path de instalacion
		$path_anterior_logs = inst::configuracion()->get_path_logs();
		$_SESSION['path_instalacion'] = $this->path;
		
		//-- Mueve los archivos de logs a la nueva carpeta del instalador
		$path_nuevo_logs = inst::configuracion()->get_path_logs() . "($timestamp)";	
		if ($path_anterior_logs != $path_nuevo_logs) {
			$excepciones = array(inst::configuracion()->get_path_archivo_diagnostico());
			if (file_exists($path_anterior_logs)) {
				inst::archivos()->copiar_directorio($path_anterior_logs, $path_nuevo_logs, $excepciones);
			} 
			//-- Notifica al logger la ubicacion del nuevo directorio de logs
			inst::logger()->set_carpeta_logs($path_nuevo_logs);
		}		

	}
 	
	function verificar_carpeta_destino($carpeta)
	{
		$docroot = $_SERVER['DOCUMENT_ROOT'];			//Obtengo el dir raiz  de apache
		$carpeta_final = realpath(dirname($carpeta));			//En el peor de los casos retorna el docroot
		
		if (stripos($carpeta_final, $docroot) !== FALSE) {
			 $this->set_error('destino_document_root', 'El Directorio Destino se encuentra en el document root de Apache. Escoja otro directorio y vuelva a intentarlo.');			
			 return;
		}				
		if (stripos($carpeta_final, INST_DIR) !== FALSE) {
			 $this->set_error('destino_instalador', 'El Directorio Destino se encuentra en el directorio donde se esta ejecutando el Instalador. Escoja otro directorio y vuelva a intentarlo.');
			 return;
		}		
	}
	
	function verificar_carpeta_writable($path, $extra=true)
	{
		if (! INST_ES_WINDOWS) {
			$usuario = "'".inst::archivos()->apache_get_usuario()."'";
		} else {
			$usuario = '';
		}			
		$ok = true;		
		//-- Existencia y permisos


		if ($ok && ! is_writable($path)) {
			$this->set_error('sin_permisos', "El usuario $usuario no posee permisos de escritura sobre la carpeta '{$path}'.");
			$ok = false;
		}		
	}
		
	function exportar_metadatos_toba()
	{
		//-- Exporta los metadatos locales actuales
		$toba_lib = new toba_lib();		
		$toba_lib->exportar_instancia();
	}
	
}

?>