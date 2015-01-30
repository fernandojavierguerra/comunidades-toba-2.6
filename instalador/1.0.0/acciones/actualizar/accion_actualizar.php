<?php

class accion_actualizar extends accion
{
	
	static function get_descripcion()
	{
		return 'Actualizar una instalaci�n existente';
	}	
	
	function conf()
	{
		$this->accion = 'actualizar';
		$this->nombre = 'Actualizaci�n';
		$this->orden = 
			array(
				'inicial',
				'requisitos',
				'destino',
				'resguardar',
				'migrar',
				'final'
			);
	}
}
?>