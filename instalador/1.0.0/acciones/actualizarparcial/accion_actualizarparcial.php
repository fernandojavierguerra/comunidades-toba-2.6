<?php

class accion_actualizarparcial extends accion
{
	
	static function get_descripcion()
	{
		return 'Aplicar una actualización menor';
	}	
	
	function conf()
	{
		$this->accion = 'actualizarparcial';
		$this->nombre = 'Actualización menor';
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