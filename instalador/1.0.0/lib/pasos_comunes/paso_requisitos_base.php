<?php

class paso_requisitos_base extends paso
{
	protected $resultados = array();
	
	function conf()
	{
		$this->nombre = 'Requisitos Previos';
	}
	
	function procesar()
	{
		
	}
	
	function get_controles()
	{
		$this->resetear_errores();
		$this->resultados = array();
		$controles = inst::controles();
		$habilitados = inst::configuracion()->get('controles');
		if (! isset($habilitados['zip'])) {
			// Agrega el chequeo zip para el mismo instalador
			$habilitados['zip'] = 'warning|El instalador utiliza la extensión "zip" para generar el archivo de diagnóstico de problemas.';
		}
		if (isset($habilitados['version_postgres'])) {
			//El chequeo de postgres se maneja en el paso de la base
			unset($habilitados['version_postgres']);
		}		
		$error = false;
		foreach ($habilitados as $id => $parametros) {
			if (! method_exists($controles, $id)) {
				throw new inst_error("La clase de controles necesita implementar el método $id");
			}
			$parametros = explode('|', $parametros);
			$severidad = array_shift($parametros);
			$res = call_user_func_array(array($controles, $id), $parametros);
			$res['severidad'] = $severidad;
			if ($res['error'] && $severidad == 'error') {
				$this->set_error($id, $res['mensaje']);
				$error = true;
			}
			if ($res['error']) {
				inst::logger()->debug("Fallo Control $id: {$res['mensaje']}");
			}
			$this->resultados[] = $res;
		}
		if (! $error) {
			$this->set_completo();
		}
		return $this->resultados;
		
	}
}
?>