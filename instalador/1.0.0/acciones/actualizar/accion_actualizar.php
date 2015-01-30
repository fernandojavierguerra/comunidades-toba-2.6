<?php

class accion_actualizar extends accion
{
	
	static function get_descripcion()
	{
		return 'Actualizar una instalación existente';
	}	
	
	function conf()
	{
		$this->accion = 'actualizar';
		$this->nombre = 'Actualización';
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