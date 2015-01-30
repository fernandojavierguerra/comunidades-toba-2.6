<?php
class ci_personas extends toba_ci
{
	protected $s__datos_filtro;


	function get_relacion()
	{
		return $this->dependencia('datos');
	}

	function get_editor()
	{
		return $this->dependencia('editor');
	}

	function conf__edicion()
	{
		if (! $this->get_relacion()->esta_cargada()) {
			//$this->pantalla()->eliminar_evento('eliminar');
		}
		$hay_cambios = $this->get_relacion()->hay_cambios();
		toba::menu()->set_modo_confirmacion('Esta a punto de abandonar la edición de la persona sin grabar, ¿Desea continuar?', $hay_cambios);
	}
	
	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}
	
	function evt__eliminar()
	{
		$this->get_relacion()->eliminar();
		$this->set_pantalla('pant_seleccion');
	}
	
	function evt__cancelar()
	{
		$this->get_editor()->disparar_limpieza_memoria();
		$this->get_relacion()->resetear();
		$this->set_pantalla('pant_seleccion');
	}
	
	function evt__procesar()
	{
		$this->dependencia('editor')->disparar_limpieza_memoria();
		$this->get_relacion()->sincronizar();
		$this->get_relacion()->resetear();
		$this->set_pantalla('pant_seleccion');
	}
	
	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__datos_filtro)) {
			$cuadro->set_datos($this->dep('datos')->tabla('personas')->get_listado($this->s__datos_filtro));
		}
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
		$this->set_pantalla('pant_edicion');
	}

	//---- Formulario -------------------------------------------------------------------

	/*
	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('personas')->get());
		} else {
			$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('personas')->set($datos);
	}

	function resetear()
	{
		$this->dep('datos')->resetear();
		$this->set_pantalla('pant_seleccion');
	}
	*/

	//---- EVENTOS CI -------------------------------------------------------------------

	/*
	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__volver()
	{
		$this->resetear();
	}

	function evt__eliminar()
	{
		$this->dep('datos')->eliminar_todo();
		$this->resetear();
	}

	function evt__guardar()
	{
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}
	*/

}
?>