<?php

class paso_instalar_destino extends paso
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
			return "C:\\$carpeta";
		} else {
			return "/usr/local/$carpeta";
		}
	}
	
	function get_path()
	{
		if (isset($this->path)) {
			return $this->path;
		} else {
			return $this->get_path_predeterminado();
		}
	}
	
	function procesar()
	{
		if(isset($_POST['carpeta'])) {
			$this->path = trim($_POST['carpeta']).INST_SEP_CARP.inst::configuracion()->get('instalador','carpeta_sufijo');			
			$this->resetear_errores();
			
			$this->verificar_carpeta_destino($this->path);
			if (! $this->tiene_errores()) {				
				$this->crear_carpeta();
			}
			if (! $this->tiene_errores()) {
				$this->copiar_archivos();
			}
			if (! $this->tiene_errores()) {
				$this->set_completo();
			}
		}
	}	
	
	protected function crear_carpeta()
	{
		$path = $this->path;		
		$timestamp = date('d_m_Y-H_i');
		inst::logger()->debug("Usando destino '$path'");
		
		$this->resetear_errores();
		if ($this->path == '') {
			$this->set_error('no_destino', 'Debe especificar la ruta del Directorio');
			return;
		}
		if (file_exists($path) && (! is_dir($path) || !inst::archivos()->directorio_vacio($path))) {
			$this->set_error('existe_destino', 'El Directorio Destino ya existe. Verifique que el mismo esté vacio');
			return;
		} 
		$path_instalacion = $path;
		if (! file_exists($path) && ! @mkdir($path_instalacion, 0755, true)) {
			$this->set_error('creacion_error', $this->get_mensaje_error_creacion($path_instalacion));
			return;
		}
		//-- Publicar el path de instalacion
		$path_anterior_logs = inst::configuracion()->get_path_logs();
		$_SESSION['path_instalacion'] = $path;
		
		//-- Mueve los archivos de logs a la nueva carpeta del instalador
		$path_instalacion = inst::configuracion()->get_path_conf_instalador();
		if (!file_exists($path_instalacion) && !@mkdir($path_instalacion, 0755, true)) {
			$this->set_error('creacion_error',$this->get_mensaje_error_creacion($path_instalacion));
			unset($_SESSION['path_instalacion']);
			return;
		}
		$path_nuevo_logs = inst::configuracion()->get_path_logs() . "($timestamp)";
		$excepciones = array(inst::configuracion()->get_path_archivo_diagnostico());
		inst::archivos()->copiar_directorio($path_anterior_logs, $path_nuevo_logs, $excepciones); 
		
		//-- Notifica al logger la ubicacion del nuevo directorio de logs
		inst::logger()->set_carpeta_logs($path_nuevo_logs);
	}
	
	function get_mensaje_error_creacion($path_instalacion)
	{
		if (! INST_ES_WINDOWS) {
			$usuario = "'".inst::archivos()->apache_get_usuario()."'";
		} else {
			$usuario = '';
		}
		$mensaje = "Problemas creando el directorio <pre>$path_instalacion</pre>
		 Aseg&uacute;rese que el usuario $usuario tenga permisos para crear esta carpeta y vuelva a intentarlo.";
		return $mensaje;
	}
	
	function copiar_archivos()
	{
		inst::archivos()->copiar_directorio(inst::configuracion()->get_dir_inst_toba(), $_SESSION['path_instalacion'].'/toba');
		inst::archivos()->copiar_directorio(inst::configuracion()->get_dir_inst_aplicacion(), $_SESSION['path_instalacion'].'/aplicacion');
	}
	
	function verificar_carpeta_destino($carpeta)
	{
		$docroot = $_SERVER['DOCUMENT_ROOT'];			//Obtengo el dir raiz  de apache
		$carpeta_final = realpath(dirname($carpeta));			//En el peor de los casos retorna el docroot
		
		if (stripos($carpeta_final, INST_DIR) !== FALSE) {
			 $this->set_error('destino_instalador', 'El Directorio Destino se encuentra en el directorio donde se esta ejecutando el Instalador. Escoja otro directorio y vuelva a intentarlo.');
			 return;
		}		
		
		if (stripos($carpeta_final, $docroot) !== FALSE) {
			 $this->set_error('destino_document_root', 'El Directorio Destino se encuentra en el document root de Apache. Escoja otro directorio y vuelva a intentarlo.');			
			 return;
		}		
	}	
}
?>