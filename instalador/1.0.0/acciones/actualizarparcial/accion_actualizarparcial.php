<?php

class accion_actualizarparcial extends accion
{
	
	static function get_descripcion()
	{
		return 'Aplicar una actualizaci�n menor';
	}	
	
	function conf()
	{
		$this->accion = 'actualizarparcial';
		$this->nombre = 'Actualizaci�n menor';
		$this->orden = 
			array(
				'inicial',
				'requisitos',
				'destino',
				'resguardar',
				'actualizar',
				'final'
			);
	}
}
?>