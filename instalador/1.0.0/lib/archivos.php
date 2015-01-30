<?php

class archivos
{
	function salto_linea()
	//Salto de linea dependiente de la plataforma
	{
		if (self::es_windows()){
			return "\r\n";
		}else{
			return "\n";
		}	
	}	
	
	
	/**
	*	Copia el contenido de un directorio a otro.
	*	No copia las carpetas SVN
	*/
	function copiar_directorio( $origen, $destino, $excepciones=array(), $manejador_interface = null )
	{
		$origen = $this->path_a_unix($origen);
		$destino = $this->path_a_unix($destino);
		if( ! is_dir( $origen ) ) {
			throw new inst_error("COPIAR DIRECTORIO: El directorio de origen '$origen' es INVALIDO");
		} 
		if( ! is_dir( $destino ) ) {
			mkdir( $destino );
		} 
		//Busco los archivos del directorio
		$lista_archivos = array();
		$dir = opendir($origen);
		if ($dir !== false ) {
			while (false !== ($a = readdir($dir))) {
				if ( $a != '.' && $a != '..' && $a != '.svn' ) {
					$lista_archivos[] = $a;
				}
			}
			closedir( $dir );
		}
		//Copio los archivos
		foreach ( $lista_archivos as $archivo ) {
			$x_origen = $origen . '/' . $archivo;
			$x_destino = $destino . '/' . $archivo;
			//Evito excepciones			
			if (! in_array($x_origen, $excepciones)) {			
				if ( is_dir( $x_origen )) {
					if (isset($manejador_interface)) {
						$manejador_interface->progreso_avanzar();
					}
					self::copiar_directorio( $x_origen, $x_destino, $excepciones, $manejador_interface );
				} else {
					if (! copy( $x_origen, $x_destino )) {
						throw new inst_error("No fue posible copiar el archivo '$x_origen' hacia '$x_destino'");	
					}
				}
			}
		}
	}	
	
	function ejecutar($cmd, &$stdout, &$stderr)
	{
	    $outfile = tempnam(".", "cmd");
	    $errfile = tempnam(".", "cmd");
	    $descriptorspec = array(
	        0 => array("pipe", "r"),
	        1 => array("file", $outfile, "w"),
	        2 => array("file", $errfile, "w")
	    );
	    $proc = proc_open($cmd, $descriptorspec, $pipes);
	   
	    if (!is_resource($proc)) return 255;
	
	    fclose($pipes[0]);
	
	    $exit = proc_close($proc);
	    $stdout = file($outfile);
	    $stderr = file($errfile);
		$stdout = implode("\n", $stdout);
		$stderr = implode("\n", $stderr);
	    unlink($outfile);
	    unlink($errfile);
	    return $exit;
	}	
	
	function get_subdirectorios( $directorio )
	{
		$dirs = array();
		if( ! is_dir( $directorio ) ) {
			return array();
		} 
		$dir = opendir($directorio);
		if ($dir !== false) {	
		   while (false	!==	( $archivo = readdir( $dir ) ) )	{ 
				if( ( $archivo != '.' ) && ( $archivo != '..' ) && ( $archivo != '.svn' ) ) {
					$path = $directorio . INST_SEP_CARP . $archivo;
					if ( is_dir( $path ) ) {
						$dirs[] = $path;
					}
				}
		   } 
		   closedir( $dir );
		}
		return $dirs;
	}
	
	function get_archivos_directorio( $directorio, $patron = null, $recursivo_subdir = false )
	{
		$archivos_ok = array();
		if( ! is_dir( $directorio ) ) {
			return array();
		}
		if ( ! $recursivo_subdir ) {
			 $dir = opendir($directorio);
			if ($dir !== false) {
				while (false	!==	($archivo = readdir($dir)))	{
			   		if(  $archivo != ".svn" &&  $archivo != "." && $archivo != ".." ) {
						$archivos_ok[] = $directorio . '/' . $archivo;
			   		}
				}
			   closedir($dir); 
			}
		} else {
			$archivos_ok = $this->buscar_archivos_directorio_recursivo( $directorio );
		}
		//Si existe un patron activado, filtro los archivos
		if( isset( $patron ) ){
			$temp = array();
			foreach( $archivos_ok as $archivo ) {
				if( preg_match( $patron, $archivo )){
					$temp[] = $archivo;
				}
			}
			$archivos_ok = $temp;
		}
		return $archivos_ok;
	}	
	
	function directorio_vacio($directorio)
	{
		return count($this->get_archivos_directorio($directorio)) == 0;
	}

	/**
	*	Busca en profundidad los archivos existentes dentro de un directorio
	*/
	function buscar_archivos_directorio_recursivo( $directorio )
	{
		if( ! is_dir( $directorio ) || ! is_readable($directorio)) {
			return array();
		} 
		$archivos = array();
		$d = dir( $directorio );
		while($d !== false && $archivo = $d->read()) {
			if (  $archivo != ".svn" && $archivo != "." && $archivo != "..") {
				$path = $directorio.'/'.$archivo;
				if ( is_dir( $path ) ) {
					$archivos = array_merge( $this->buscar_archivos_directorio_recursivo( $path ), $archivos ) ;
				} else {
					$archivos[] = $path;
				}
			}
		}
		$d->close();
		return $archivos;
	}	
	
	function comprimir_archivo($src, $level = 5, $dst = false)
	{
		if( $dst == false){
			$dst = $src.".gz";
		}
		if (file_exists($src)) {
			$filesize = filesize($src);
			$src_handle = fopen($src, "r");
			if ($src_handle === false) {
				inst::logger()->error("Comprimir archivo: No se puede abrir $src");
				return false;
			}
			if (!file_exists($dst)){
				$dst_handle = gzopen($dst, "w$level");
				while(!feof($src_handle)){
					$chunk = fread($src_handle, 2048);
					gzwrite($dst_handle, $chunk);
				}
				fclose($src_handle);
				gzclose($dst_handle);
				return true;
			} else {
				inst::logger()->error("Comprimir archivo: $dst ya existe");
			}
		} else {
			inst::logger()->error("Comprimir archivo: $src no existe");	    	
		}
		return false;
	 }	
	
	function eliminar_directorio( $directorio )
	{
		if( ! is_dir( $directorio ) ) {
			if (! @unlink($directorio)) {
				@chmod($directorio, 0777);
				unlink($directorio);
			}
			return;
		} 
		$dir = opendir( $directorio );
		while ( $archivo = readdir( $dir ) ) {
			$path = $directorio.'/'.$archivo;
			if ( $archivo != "." && $archivo!=".." ) {
			   $this->eliminar_directorio( $path );
			}
		}
		closedir( $dir );
		rmdir( $directorio );
	}	

	static function es_windows()
	{
		return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
	}
	
	
	static function path_a_windows($nombre, $encomillar_espacios=true)
	{
		$nombre = str_replace('/', "\\", $nombre);	
		//Si algun segmento del PATH tiene espacios, hay que ponerlo entre comillas.
		if($encomillar_espacios && strpos($nombre,' ')){
			$segmentos = explode("\\",$nombre);
			for($a=0;$a<(count($segmentos));$a++){
				if(strpos($segmentos[$a],' ')){
					$segmentos[$a] = '"'.$segmentos[$a].'"';
				}
			}
			$nombre = implode("\\",$segmentos);
		}
		return $nombre;
	}

	static function path_a_unix($nombre)
	{
		return str_replace('\\', "/", $nombre);	
	}	
	
	/**
	 * Retorna un nombre de archivo valido
	 */
	static function path_a_plataforma($path)
	{
		if (self::es_windows()) {
			return self::path_a_windows($path);
		} else {
			return self::path_a_unix($path);		
		}
	}	
	
	function navegar($url)
	{
		if (self::es_windows()) {
			$shell = new COM('WScript.Shell');
			$cmd = 'cmd /c start "" "' . $url . '"';
			$shell->Run($cmd, 0, FALSE);
			unset($shell);
		} else {
			$sudo = inst::config()->get('comando_sudo');
			if ($sudo == 'gksudo') {
				$comando = "gnome-open '$url'";
			} else {
				$comando = "kfmclient exec '$url'";
			}
			exec($comando); 		
		}
	}
	
	function agregar_path_php($dir)
	{
		$separador = (substr(PHP_OS, 0, 3) == 'WIN') ? ';.;' : ':.:';
		ini_set('include_path', ini_get('include_path'). $separador . $dir);		
	}
	
	function agregar_shortcut_escritorio($nombre, $comando, $argumentos, $descripcion='', $iniciar_en='', $icono='')
	{
		$shell = new COM('WScript.Shell');
		$link = $shell->CreateShortcut($shell->SpecialFolders("Desktop").'\\'.$nombre.'.lnk');
		$link->Description = $descripcion;
		$link->TargetPath = $comando;
		$link->Arguments = $argumentos;
		$link->Hotkey = "CTRL+SHIFT+T";
		if ($iniciar_en != '') {
			$link->WorkingDirectory = $iniciar_en;
		}
		if ($icono != '') {
			$link->IconLocation = $icono;
		}
		$link->save();
	}

	function apache_get_usuario()
	{
		return trim(@`whoami`);
	}
	
	function apache_agregar_conf($path_conf, $nombre_conf)
	{
		//--- Actualiza la configuración de apache
		$path_apache = inst::config()->get('path_conf_apache');
		if (self::es_windows()) {
			$path_apache .= '/httpd.conf';
			$include = "\nInclude \"".$this->path_a_windows($path_conf)."\"";
			file_put_contents($path_apache, $include, FILE_APPEND);
		} else {
			//--- Asume una distribución DEBIAN
			$path_conf = $this->path_a_unix($path_conf);
			$cmd = "cp -sf $path_conf $path_apache/sites-enabled/$nombre_conf";
			$this->ejecutar_como_root($cmd, "Actualización configuración Apache");
		}
		return "";
	}
	
	function apache_eliminar_conf($path_conf, $nombre_conf)
	{
		$path_apache = inst::config()->get('path_conf_apache');
		if (self::es_windows()) {
			$path_apache .= '/httpd.conf';
			$include = "Include \"".$this->path_a_windows($path_conf)."\"";
			$conf = file_get_contents($path_apache);
			$conf = str_replace($include, '#'.$include, $conf);
			file_put_contents($path_apache, $conf);
		} else {
			//--- Asume una distribución DEBIAN
			$path_conf = $this->path_a_unix($path_conf);
			$cmd = "rm $path_apache/sites-enabled/$nombre_conf";
			$this->ejecutar_como_root($cmd, "Actualización configuración Apache");
		}
	}
	
	function apache_es_path_conf($path)
	{
		//--- Actualiza la configuración de apache
		if (self::es_windows()) {
			$path .= '/httpd.conf';
			if (! file_exists($path)) {
				return false;
			}
		}
		return true;
	}
	
	function apache_reiniciar()
	{
		if (self::es_windows()) {
			$path_apache = dirname(inst::config()->get('path_conf_apache'));
			//--- Apache 2.2
			$cmd = "\"$path_apache\\bin\\httpd\" -k restart -n Apache2";
			$fallo = 0;
			$salida = $error = null;
			$fallo = inst::archivos()->ejecutar($cmd, $salida, $error);
			$salida .= $error;
			if ($fallo) {
				//--- Apache 2.0
				$cmd = "\"$path_apache\\bin\\\Apache.exe\" -k restart -n Apache2";
				$fallo = 0;
				$fallo = inst::archivos()->ejecutar($cmd, $salida, $error);
				$salida .= $error;
				if ($fallo) {
					return "Error ejecutando $cmd.\n".$salida;
				}
			}
			return $salida;
		} else {
			$cmd = "apache2ctl -k restart";
			$this->ejecutar_como_root($cmd, "Reinicio de Apache");
		}
	}

	function zip_agregar_path($zip, $path)
	{
		if (file_exists($path) && is_dir($path)) {
			
			$archivos = self::buscar_archivos_directorio_recursivo($path);
			foreach($archivos as $archivo) {
				$path = realpath($archivo);
				if ($path === false) {
					$path = $archivo;
				}
				 $zip->addFile($path, $archivo);
			}
		} else {
			inst::logger()->error("No se pudo agrega al zip el path '$path'");
		}
	}
	
	function generar_password($long){
		$str = "ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789";
		for($cad="",$i=0;$i<$long;$i++) {
			$cad .= substr($str,rand(0,(strlen($str)-1)),1);
		}
		return $cad;
	}	


}



?>