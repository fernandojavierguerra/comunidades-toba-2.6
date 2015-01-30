<?php

class accion_instalar extends accion
{
	static function get_descripcion()
	{
		return 'Instalar';
	}
	
	function conf()
	{
		$this->accion = 'instalar';
		$this->nombre = 'Instalaci�n';
		$this->orden = 
			array(
				'inicial',
				'requisitos',				
				'destino',
				'configuracion',			
				'bases',
				'publicacion',
				'final'
			);
	}
}
?>