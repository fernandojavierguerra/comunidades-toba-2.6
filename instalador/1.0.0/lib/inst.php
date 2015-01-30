<?php


/**
 * Factory que permite acceder a distintos componentes del instalador
 *
 */
class inst
{
	static protected $objetos = array();
	static protected $clase_controles = 'controles';
	
	static protected function instanciar($clase)
	{
		if (! isset(self::$objetos[$clase])) {
			self::$objetos[$clase] = new $clase;
		}
		return self::$objetos[$clase];
	}

	static function iniciar()
	{
		header("Expires: Mon, 26 Jul 1987 05:00:00 GMT");					// Pone una fecha vieja
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");		// Siempre modificado
		header("Cache-Control: no-cache, must-revalidate");					// HTTP/1.1
		header("Pragma: no-cache");
		header('Content-Type: text/html; charset=iso-8859-1');
		        		
		require_once(INST_DIR.'/instalador_autoload.php');
		spl_autoload_register(array('inst', 'cargador_clases'));
				
		define('INST_ES_WINDOWS', inst::archivos()->es_windows());
		define('INST_SEP_CARP', INST_ES_WINDOWS ? '\\' : '/');
		define('INST_SALTO_LINEA', INST_ES_WINDOWS ? "\r\n" : "\n");		
	}
	
	static function cargador_clases($clase)
	{
		//Carga de las clases del nucleo
		if (instalador_autoload::existe_clase($clase)) {
			instalador_autoload::cargar($clase);
		}
	}

	/**
	 * @return db_manager
	 */
	static function db_manager()
	{
		return self::instanciar('db_manager');
	}
	
	/**
	 * @return configuracion
	 */
	static function configuracion()
	{
		return self::instanciar('configuracion');
	}
	
	/**
	 * @return controlador
	 */
	static function controlador()
	{
		return self::instanciar('controlador');
	}

	/**
	 * Retorna una referencia a la accion actualmente ejecutada
	 * @return accion
	 */	
	static function accion()
	{
		return self::controlador()->accion();
	}
	
	/**
	 * Retorna una referencia al paso actualmente ejecutado
	 * @return paso
	 */
	static function paso()
	{
		return self::accion()->paso();
	}
	
	/**
	 * @return sesion
	 */
	static function sesion()
	{
		return self::instanciar('sesion');
	}
	
	/**
	 * @return logger
	 */
	static function logger()
	{
		return self::instanciar('logger');
	}
	
	/**
	 * @return archivos
	 */
	static function archivos()
	{
		return self::instanciar('archivos');
	}
	
	static function controles()
	{
		return new self::$clase_controles();
	}
	
	static function set_clase_controles($clase)
	{
		self::$clase_controles = $clase;
	}
	
	static function scroll_fondo()
	{
		echo "<script type='text/javascript'>\n";
		echo "scroll_fondo();\n";	
		echo "</script>\n";		
	}
	
	static function get_manejador_negocio($conexion)
	{
		$manejador_propio = inst::configuracion()->get('base', 'manejador_negocio', null, false);
		$param_base = inst::configuracion()->get('base');
		$path_proyecto = inst::configuracion()->get_dir_inst_aplicacion();
		if (isset($manejador_propio)) {
			$path = inst::configuracion()->get_dir_inst_aplicacion().'/'.$manejador_propio;
			$clase = basename($manejador_propio, '.php');
			if (! file_exists($path)) {
				throw new inst_error("No se encuentra el archivo $path");
			}
			require_once($path);
			$manejador = new $clase($conexion, inst::logger(), $param_base, $path_proyecto);
		} else {
			//Se usa un manejador generico
			$manejador = new manejador_negocio($conexion, inst::logger(), $param_base, $path_proyecto);
		}
		return $manejador;		
	}
}
?>