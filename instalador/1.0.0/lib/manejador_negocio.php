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
	 * Crear la base/s de negocio durante la instalación del sistema 
	 * @param $version Versión del sistema que se está instalando
	 * @param $grupo_datos Grupo de datos seleccionado durante la instalación
	 */
	function crear_negocio($version, $grupo_datos)
	{
	}	
	
	/**
	 * Migrar la bases/s de negocio. Puede ser tanto durante la instalación inicial (ya sea con una base nueva o existente) como la actualización
	 * @param $version Versión del sistema que se está instalando
	 * @param $es_base_nueva Indica si la migración es sobre una base de datos existente o sobre una nueva
	 */
	function migrar_negocio($version, $es_base_nueva)
	{
	}

	/**
	 * Ventana para migrar detalles particulares dentro del proyecto
	 *
	 * @param $version Versión del sistema que se está instalando
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
	 * Ventana para aplicar ajustes post actualización de un proyecto
	 */
	function post_actualizacion($version, $path_aplicacion)
	{
		
	}
	
}

?>
