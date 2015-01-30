<?php

/**
 *	Clase vacia de manejo de negocio
 */
class manejador_negocio
{
	protected $conexion;
	protected $logger;
	protected $definicion_base;
	protected $path_proyecto;

	function __construct($conexion, $logger, $definicion_base, $path_proyecto)
	{
		$this->conexion = $conexion;
		$this->logger = $logger;
		$this->definicion_base = $definicion_base;
		$this->path_proyecto = $path_proyecto;
	}

	/**
	 * Crear la base/s de negocio durante la instalaci�n del sistema 
	 * @param $version Versi�n del sistema que se est� instalando
	 * @param $grupo_datos Grupo de datos seleccionado durante la instalaci�n
	 */
	function crear_negocio($version, $grupo_datos)
	{
	}	
	
	/**
	 * Migrar la bases/s de negocio. Puede ser tanto durante la instalaci�n inicial (ya sea con una base nueva o existente) como la actualizaci�n
	 * @param $version Versi�n del sistema que se est� instalando
	 * @param $es_base_nueva Indica si la migraci�n es sobre una base de datos existente o sobre una nueva
	 */
	function migrar_negocio($version, $es_base_nueva)
	{
	}

	/**
	 * Ventana para migrar detalles particulares dentro del proyecto
	 *
	 * @param $version Versi�n del sistema que se est� instalando
	 * @param string $desde Path a la carpeta de la version vieja del sistema
	 * @param string $hacia Path a la nueva carpeta del sistema
	 */
	function migrar_codigo($version, $desde, $hacia)
	{
	}

	
	/**
	 * Se ejecuta luego de crear/actualizar la base de negocios y crear la base de toba
	 */
	function post_instalacion($es_base_nueva)
	{
		
	}

	/**
	 * Ventana para aplicar ajustes post actualizaci�n de un proyecto
	 */
	function post_actualizacion($version, $path_aplicacion)
	{
		
	}
	
}

?>
