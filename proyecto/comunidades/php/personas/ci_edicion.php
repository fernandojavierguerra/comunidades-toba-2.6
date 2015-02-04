<?php
class ci_edicion extends comunidades_ci
{

	function get_relacion()    
	{
		return $this->controlador->get_relacion();
	}
	
	//-----------------------------------------------------------------------------------
	//---- pant_inicial -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->get_relacion()->esta_cargada()) {
			$form->set_datos($this->get_relacion()->tabla('personas')->get());
		} else {
			//$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	function evt__formulario__modificacion($datos)
	{
		$this->get_relacion()->tabla('personas')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_sacramentos -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_sacramentos(comunidades_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->get_relacion()->tabla('sacramentospersona')->get_filas(null, true));
	}

	function evt__form_sacramentos__modificacion($datos)
	{
		$this->get_relacion()->tabla('sacramentospersona')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_grupos ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_grupos(comunidades_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->get_relacion()->tabla('personagrupo')->get_filas(null, true));
	}

	function evt__form_grupos__modificacion($datos)
	{
		$this->get_relacion()->tabla('personagrupo')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_familiares --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_familiares(comunidades_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->get_relacion()->tabla('familiares')->get_filas(null, true));
	}

	function evt__form_familiares__modificacion($datos)
	{
		$this->get_relacion()->tabla('familiares')->procesar_filas($datos);
	}
	
	function get_persona_nombre($id)
	{
		return $this->get_relacion()->tabla('personas')->get_persona_nombre($id);
	}

}
?>