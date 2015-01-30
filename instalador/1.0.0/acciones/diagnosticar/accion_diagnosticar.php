<?php

class accion_diagnosticar extends accion
{
	static function get_descripcion()
	{
		return 'Instalar la nueva versi�n';
	}
		
	function conf()
	{
		$this->accion = 'diagnosticar';
		$this->nombre = 'Diagnosticar Sistema';
		$this->orden = 
			array(
				'inicial',
				'recolectar',
				'descargar',
				'final'
			);
	}
}

?>