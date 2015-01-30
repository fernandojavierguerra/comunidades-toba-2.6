<?php

abstract class paso
{
	protected $ultimo = false;
	protected $opcional = false;
	protected $completo = false;
	protected $accion;
	protected $numero;
	protected $nombre;
	protected $errores = array();
	
	function __construct($numero, $accion)
	{
		$this->numero = $numero;
		$this->accion = $accion;
		$this->conf();
	}
	
	abstract function conf();
	
	function set_es_ultimo()
	{
		$this->ultimo = true;
	}
	
	function esta_completo()
	{
		return $this->completo;
	}
	
	function get_numero()
	{
		return $this->numero;
	}
	
	function get_nombre()
	{
		return $this->nombre;
	}
	
	function get_id()
	{
		$partes = explode('_', get_class($this));
		return $partes[2];
	}
	
	function tiene_errores()
	{
		return ! empty($this->errores);
	}
	
	function get_errores()
	{
		return $this->errores;
	}
	
	function tiene_ayuda()
	{
		$path = $this->accion->get_dir_ayudas().'/'.$this->get_id().'.html';
		return file_exists($path);
	}
	
	function get_link_ayuda()
	{
		if ($this->tiene_ayuda()) {
			return $this->accion->get_url_ayudas().'/'.$this->get_id().'.html';
		}
	}
	
	function es_opcional()
	{
		return $this->opcional;
	}

	function set_completo()
	{
		inst::logger()->debug('PASO COMPLETO');
		$this->completo = true;
	}
	
	function resetear_errores()
	{
		$this->errores = array();
	}
	
	function set_error($error, $extra = '')
	{
		inst::logger()->error("$error: $extra");
		$this->errores[$error] = $extra;
		$this->completo = false;
	}	
	
	
	
	//-----------------------------------------
		
	function procesar()
	{
		
	}
	
	function generar()
	{
		$dir_templates = $this->accion->get_dir_templates();
		$archivo = $this->accion->get_dir_templates().'/'.$this->get_id().'.php';
		if (file_exists($archivo)) {
			include($archivo);
		} else {
			echo "<h3>Debe definir el template en el archivo $archivo</h3>";
		}
	}
	
	function generar_html_errores($errores = null)
	{
		if (! isset($errores)) {
			$errores = $this->get_errores();
		}
		echo "<div class='errores'><img src='recursos/error.gif' /> Se han encontrado los siguientes errores: ";
		foreach ($errores as $error => $mensaje) {
			echo "<div>";
			echo $mensaje;
			echo "</div>";
		}
		if (inst::accion()->get_id() != 'diagnosticar') {
			$link_diagnostico = inst::controlador()->get_link_diagnostico();
			echo "<div style='text-align: right'>";
			echo "<a href='$link_diagnostico' title='Permite generar un archivo de diagnostico listo para enviar al grupo de desarrollo' target='_blank'>Diagnosticar Problemas</a>";
			echo "</div>";
		}
		echo "</div>";
	}	
	
	
}
?>