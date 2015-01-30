<?php

class logger
{
	const limite_mensaje = 2000;
	protected $archivo;
	protected $cant_archivos = 100;
	protected $comprimir=false;
	
	function __construct()
	{
		$inicial = false;
		if (! isset($_SESSION['archivo_logs'])) {
			$path = inst::configuracion()->get_path_logs();
			$archivo = 'instalador.log';
			$numero = $this->ciclar_archivos_logs($path, $archivo);
			$_SESSION['archivo_logs'] = $path .'/'.$numero.'.'.$archivo;
			if (! file_exists($path)) {
				mkdir($path, 0700, true);
			}
			$inicial = true;
		}
		$this->archivo = $_SESSION['archivo_logs'];

		if ($inicial) {
			$texto = "Version-PHP: ". phpversion().INST_SALTO_LINEA;
			if (isset($_SERVER['SERVER_NAME'])) {
				$texto .= "Servidor: ".$_SERVER['SERVER_NAME'].INST_SALTO_LINEA;
			}
			if (isset($_SERVER['REQUEST_URI'])) {
				$texto .= "URI: ".$_SERVER['REQUEST_URI'].INST_SALTO_LINEA;	
			}		
			if (isset($_SERVER["HTTP_REFERER"])) {
				$texto .= "Referrer: ".$_SERVER["HTTP_REFERER"].INST_SALTO_LINEA;
			}
			if (isset($_SERVER["REMOTE_ADDR"])) {
				$texto .= "Host: ".$_SERVER["REMOTE_ADDR"].INST_SALTO_LINEA;			
			}   
			$this->agregar($texto); 		
		}

		$texto = INST_SALTO_LINEA."Fecha: ".date("d-m-Y H:i:s").INST_SALTO_LINEA."---------------------------".INST_SALTO_LINEA;
		$this->agregar($texto);
	}
	
	function set_carpeta_logs($carpeta)
	{
		$actual = basename($this->archivo);
		$_SESSION['archivo_logs'] = $carpeta.'/'.$actual;
		$this->archivo = $_SESSION['archivo_logs'];
	}
	
	function get_archivo()
	{
		return $this->archivo;
	}
	
	protected function agregar($mensaje)
	{
		if (strlen($mensaje) > self::limite_mensaje) {
			$mensaje = substr($mensaje, 0, self::limite_mensaje).INST_SALTO_LINEA." (...continua...)".INST_SALTO_LINEA.INST_SALTO_LINEA;
		}		
		if (@file_put_contents($this->archivo, $mensaje, FILE_APPEND) == false) {
			//-- Como ultimo intento se trata de usar el path original de logs del instalador
			$this->set_carpeta_logs(inst::configuracion()->get_path_logs(true));		
			if (@file_put_contents($this->archivo, $mensaje, FILE_APPEND) == false) {
				if (!INST_ES_WINDOWS) { 
					$usuario = '('.inst::archivos()->apache_get_usuario().')';
				}
				$carpeta = dirname($this->archivo);
				$mensaje = "El usuario que corre el servidor web $usuario debe tener permisos de escritura sobre la carpeta '$carpeta'";			
				throw new Exception($mensaje);
			}
		}
	}
	
	function error($mensaje)
	{
		$mensaje = $this->extraer_mensaje($mensaje);
		$this->agregar('[ERROR] '. $mensaje. INST_SALTO_LINEA);
	}
	
	function debug($mensaje)
	{
		$mensaje = $this->extraer_mensaje($mensaje);
		$this->agregar('[DEBUG] '. $mensaje. INST_SALTO_LINEA);
	}
	
	function grabar($mensaje)
	{
		$this->debug($mensaje);
	}
	
	protected function extraer_mensaje($mensaje)
	{
		if (is_object($mensaje)) {
			if ($mensaje instanceof Exception) {
				$res = get_class($mensaje).": ".$mensaje->getMessage()."\n";
				$res .= "\n[TRAZA]<div style='display:none'>".$mensaje->__toString()."</div>";
				return $res;
			} else if (method_exists($mensaje, 'getMessage')) {
				return $mensaje->getMessage();
			} else if (method_exists($mensaje, 'tostring')) {
				return $mensaje->toString();
			} else if (method_exists($mensaje, '__tostring')) {
				return (string)$mensaje;
			} else {
				return var_export($mensaje, true);
			}
		} else if (is_array($mensaje)) {
			return var_export($mensaje, true);
		} else {
			return $mensaje;	
		}
	}	
	
	protected function ciclar_archivos_logs($path, $archivo)
	{
		//Encuentra los archivos
		$patron = "/([0-9]+)\.$archivo/";
		$archivos = inst::archivos()->get_archivos_directorio($path);

		//¿Cual es el numero de cada uno?
		$ultimo = 0;
		$arch_ordenados = array();
		foreach ($archivos as $arch_actual) {
			$version = array();
			preg_match($patron, $arch_actual, $version);
			if (! empty($version) && count($version) > 1) {
				$pos = $version[1];
				$arch_ordenados[$pos] = $arch_actual;
				if ($pos > $ultimo) {
					$ultimo = $pos;
				}
			}
		}
		//Se determina el siguiente numero
		$sig = $ultimo + 1;
		
		//¿Hay que purgar algunos?
		$cantidad_archivos = count($arch_ordenados);
		$puede_purgar = ($this->cant_archivos != -1);
		$debe_purgar = ($cantidad_archivos >= ($this->cant_archivos - 1));
		if ($puede_purgar && $debe_purgar) {
			ksort($arch_ordenados);
			reset($arch_ordenados);
			//Se dejan solo N-1 archivos			
			$a_purgar = $cantidad_archivos - ($this->cant_archivos - 1);
			while ($a_purgar > 0) {
				unlink(current($arch_ordenados));
				$a_purgar--;
				next($arch_ordenados);
			}
		}
		return $sig;
	}	
	

	//--------------------------------------------------------
	//------------	MANEJADOR DE INTERFACE -------------------
	//--------------------------------------------------------
	
	
}
?>