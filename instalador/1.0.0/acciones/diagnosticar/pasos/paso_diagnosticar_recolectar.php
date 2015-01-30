<?php

class paso_diagnosticar_recolectar extends paso
{
	protected $directorios;
	
	function conf()
	{
		$this->nombre = 'Seleccionar Servicios';
	}
	
	function procesar()
	{
		$this->resetear_errores();
		if (! empty($_POST)) {
			$this->directorios = $_POST;
			$archivo = $this->generar_archivo_diagnostico();
			if (! $this->tiene_errores()) {
				$this->set_completo();
			}
		}		
	}
	
	
	function generar_archivo_diagnostico()
	{
		$zip = new ZipArchive();
		$archivo = inst::configuracion()->get_path_archivo_diagnostico();
		if (file_exists($archivo)) {
			unlink($archivo);
		}
		$zip->open($archivo, ZIPARCHIVE::CREATE);
		
		//----------------- APACHE ---------------------
		$path_conf_apache = trim($this->directorios['path_conf_apache']);
		if ($path_conf_apache != '') {
			inst::archivos()->zip_agregar_path($zip, $path_conf_apache);
		}
		$path_logs_apache = trim($this->directorios['path_logs_apache']);
		if ($path_logs_apache != '') {
			foreach (glob ($path_logs_apache.'/error.log*') as $f) {
			    $zip->addFile(realpath($f));
			}			
		}		
		
		//----------------- PHP ---------------------
		$path_conf_php = trim($this->directorios['path_conf_php']);
		if ($path_conf_php != '') {
			if (INST_ES_WINDOWS) {
				$php_ini = $path_conf_php.'/php.ini';
				if (! file_exists($php_ini)) {
					$php_ini_alternativo = 'c:/windows/php.ini';
					if (file_exists($php_ini_alternativo)) {
						$php_ini = $php_ini_alternativo;
					} 
				}
				if (file_exists($php_ini)) {
					$zip->addFile($php_ini);
				}
			} else {
				//-- Debian
				inst::archivos()->zip_agregar_path($zip, $path_conf_php);
			}
		}
		
		//----------------- PostgreSQL ---------------------
		$path_conf_postgres = trim($this->directorios['path_conf_postgres']);
		if ($path_conf_postgres != '') {
			$zip->addFile($path_conf_postgres.'/pg_hba.conf');
			$zip->addFile($path_conf_postgres.'/postgresql.conf');
		}
		$path_logs_postgres = trim($this->directorios['path_logs_postgres']);
		if ($path_logs_postgres != '') {
			inst::archivos()->zip_agregar_path($zip, $path_logs_postgres);
		}
	
		//------------------ Toba -----------------
		$path_conf_toba = trim($this->directorios['path_conf_toba']);
		if ($path_conf_toba != '') {
			inst::archivos()->zip_agregar_path($zip, $path_conf_toba);
		}		
		
		//------------------ Instalador -----------------
		$path_instalador = trim($this->directorios['path_instalador']);
		if ($path_instalador != '') {
			inst::archivos()->zip_agregar_path($zip, $path_instalador);
			inst::archivos()->zip_agregar_path($zip, INST_DIR.'/instalador.ini');
			$zip->addFile(inst::configuracion()->get_dir_inst_aplicacion().'/proyecto.ini');
		}		
		
		$zip->close();
		$_SESSION['archivo_diagnostico'] = $archivo;
	}
	
	
	function get_directorios()
	{
		if (! isset($this->directorios)) {
			$directorios = $this->get_directorios_defecto();
			foreach ($directorios as $clave => $valor) {
				if (! file_exists($valor)) {
					//Si no existe el archivo, no sugerirlo
					$directorios[$clave] = '';
				}
			}
			return $directorios;
		} else {
			return $this->directorios;
		}
	}
	
	function get_directorios_defecto()
	{
		$directorios =  array();
		if (! INST_ES_WINDOWS) { 
			$directorios['path_conf_apache']	= '/etc/apache2';
			$directorios['path_logs_apache']	= '/var/log/apache2';
			$directorios['path_conf_php'] 		= '/etc/php5';
			$directorios['path_conf_postgres'] 	= $this->get_ultimo_directorio('/etc/postgresql').'/main';
			$directorios['path_logs_postgres'] 	= '/var/log/postgresql';
		} else {
			$archivos_programas = getenv('ProgramFiles');
			if ($archivos_programas === false) {
				$archivos_programas = 'C:\Archivos de Programa';
			}
			
			$directorios['path_conf_apache']	= $this->get_ultimo_directorio($archivos_programas.'\Apache Software Foundation').'\conf';
			$directorios['path_logs_apache']	= $this->get_ultimo_directorio($archivos_programas.'\Apache Software Foundation').'\logs';
			$directorios['path_conf_php'] 		= $archivos_programas.'\PHP';
			$directorios['path_conf_postgres'] 	= $this->get_ultimo_directorio($archivos_programas.'\PostgreSQL').'\data';
			$directorios['path_logs_postgres'] 	= $this->get_ultimo_directorio($archivos_programas.'\PostgreSQL').'\data\pg_log';
		}
		$directorios['path_instalador'] = inst::configuracion()->get_path_logs();
		
		if (isset($_SESSION['path_instalacion'])) {
			$directorios['path_conf_toba'] = $_SESSION['path_instalacion'].'/instalacion';	
		} else {
			$carpeta = inst::configuracion()->get('instalador', 'carpeta_sufijo');
			$carpeta = inst::archivos()->path_a_plataforma($carpeta);
			if (INST_ES_WINDOWS) {
				$carpeta = "C:\\$carpeta";
			} else {
				$carpeta = "/usr/local/$carpeta";
			}			
			$directorios['path_conf_toba'] = $carpeta.'/instalacion';
		}
		return $directorios;
	}
	
	function get_ultimo_directorio($carpeta)
	{
		if (file_exists($carpeta) && is_dir($carpeta)) {
			$subdirectorios = inst::archivos()->get_subdirectorios($carpeta);
			if (! empty($subdirectorios)) {
				return end($subdirectorios);			
			}
		}
	}
}
?>