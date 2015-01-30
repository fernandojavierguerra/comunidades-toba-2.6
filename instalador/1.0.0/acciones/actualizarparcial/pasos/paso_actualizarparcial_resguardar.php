<?php

class paso_actualizarparcial_resguardar extends paso
{
	protected $parametros;
	protected $info_instalacion;
	
	function conf()
	{
		$this->nombre = 'Resguardo';
	}
	
	function procesar()
	{
		$this->controlar_version_postgres();
		if (!$this->tiene_errores()) {
			$this->set_completo();
		}
	}		

	function controlar_version_postgres()
	{
		$this->resetear_errores();
		$controles = inst::controles();
		$habilitados = inst::configuracion()->get('controles');
		$id = 'version_postgres';
		if (isset($habilitados[$id])) {
			$parametros = explode('|', $habilitados[$id]);
			$severidad = array_shift($parametros);
			$res = call_user_func_array(array($controles, $id), $parametros);
			$res['severidad'] = $severidad;
			if ($res['error'] && $severidad == 'error') {
				$this->set_error($id, $res['titulo'].': '.$res['mensaje']);
			}
			if ($res['error']) {
				inst::logger()->debug("Fallo Control $id: {$res['mensaje']}");
			}			
		} 
	}	
	
}

?>