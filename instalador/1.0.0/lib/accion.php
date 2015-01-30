<?php

abstract class accion
{
	protected $accion;
	protected $nombre;
	protected $pasos;
	protected $orden = array();
	protected $paso_actual = 0;
	protected $paso_generado;
	protected $mostrar_pasos = true;
	protected $pasos_redefinidos = array();
	
	function __construct($reiniciar)
	{
		$this->conf();
		$this->iniciar($reiniciar);
	}

	//---------------------------------------------
	//-----	PROCESOS
	//---------------------------------------------
	
	abstract function conf();	

	static function get_descripcion(){}
	
	static  function incluir_archivos_alternativos($accion , $reiniciar)
	{	
		//Obtengo todas las entradas de configuracion para la accion
		$conf = inst::configuracion()->get('accion_'.$accion, null, null, false);
		$dir_proyecto = inst::configuracion()->get_dir_inst_aplicacion();
	
		//Cargo los archivos para los pasos segun el orden especificado en la accion
		if (! $reiniciar) {
			foreach($conf as $clase) {
				$nombre = basename($clase);
				$path_completo = $dir_proyecto . '/'. $clase;
				if (!file_exists($path_completo)) {
					throw new inst_error('No se encuentra el archivo para el paso '.$actual . ' en el sistema de archivos');
				}
				require_once($path_completo);				
			}
		}
	}
	
	function iniciar($reiniciar)
	{
		if (empty($this->orden)) {
			throw new inst_error('No se han determinado los pasos de la acción '.$this->accion);
		}
		
		$conf = inst::configuracion()->get('instalador_clases_redefinidas',null,  null, false);
		
		//-- Determino los pasos
		$this->pasos = array();
		$i = 0;
		foreach ($this->orden as $actual) {
			$clase_paso = 'paso_'.$this->accion."_$actual";
			if (isset($conf[$clase_paso])) {
				$clase_paso = basename($conf[$clase_paso], '.php');
			}			
			$paso = new $clase_paso($i, $this);
			$this->pasos[$i] = $paso;
			$i++;
		}
		$paso->set_es_ultimo(true);
		$this->paso_actual = 0;
	}
	
	/**
	 * Procesa el pedido de página anterior
	 *
	 */
	function procesar()
	{
		//-- Si anteriormente se genero algún paso, se procede a procesar su request
		if (isset($this->paso_generado)) {
			$paso_procesar = $this->paso_generado;
			unset($this->paso_generado);
			inst::logger()->debug('Procesando paso "'. $this->pasos[$paso_procesar]->get_nombre().'"');
			$this->pasos[$paso_procesar]->procesar();
			if ($this->pasos[$paso_procesar]->esta_completo()) {
				// Cuando se completa un paso, se mueve al siguiente, permite procesar un paso y generar el siguiente en el mismo pedido y 
				//	ademas evita que el f5 refresque todo
				$this->paso_actual = $paso_procesar + 1;	
			} else {
				$this->paso_actual = $paso_procesar;
			}
		}
	}

	/**
	 * Genera el HTML del nuevo pedido
	 */
	function generar()
	{
		//-- Determina cual es el nuevo paso a generar
		if (isset($_GET['paso']) || ! isset($this->paso_actual)) { 
			$this->paso_actual = isset($_GET['paso']) ? (int) $_GET['paso'] : 0;
		}
		//Se asegura que todos los pasos hasta el actual esten bien
		for ($i=0; $i < $this->paso_actual; $i++) {
			if (!$this->pasos[$i]->esta_completo() && !$this->pasos[$i]->es_opcional()) {
				$this->paso_actual = $i;
				break;
			}
		}
		//Evita el overflow de pasos
		if ($this->paso_actual >= count($this->pasos)) {
			$this->paso_actual--;
		}
		inst::logger()->debug('Generando paso "'. $this->pasos[$this->paso_actual]->get_nombre().'"');
		include($this->get_dir_templates().'/marco.php');
	}
	
	
	function destruir()
	{
		$this->paso_generado = $this->paso_actual;
		unset($this->paso_actual);
	}
	
	//------------------------------------------------
	//---- Metodos de generación HTML
	//------------------------------------------------

	function mostrar_pasos()
	{
		return $this->mostrar_pasos;
	}
	
	function set_mostrar_pasos($mostrar)
	{
		$this->mostrar_pasos = $mostrar;
	}
	
	function generar_html_pasos()	
	{
		$actual = $this->paso();
		foreach ($this->pasos as $i => $paso) {
			echo "<tr>";
			echo "<td class='navnum'>";
			if ($i <= $actual->get_numero() && ($this->pasos[$i]->esta_completo() || $this->pasos[$i]->es_opcional())) {
				echo '<span class="success">&radic;</span>';
			} elseif ($paso->tiene_errores()) {
				echo '<span class="error">&#10007;</span>';
			} else {
				echo $i;
			}
			echo '</td><td>';
			$tiene_link = ($i == 0) || ($i <= $actual->get_numero() + 1 && 
								($this->pasos[$i-1]->esta_completo() || $this->pasos[$i-1]->es_opcional()));
			$nombre = $paso->get_nombre();
			if ($tiene_link) {
				echo "<a href='".$this->get_url("paso=$i")."'>$nombre</a>";
			} else {
				echo $nombre;
			}
			echo '</td></tr>';
		}
	}	
	
	//---------------------------------------------
	//-----	CONSULTAS
	//---------------------------------------------
	
	function get_dir_templates()
	{
		return INST_DIR."/acciones/{$this->accion}/templates";
	}
	
	function get_id()
	{
		return $this->accion;
	}
	
	function get_nombre()
	{
		return $this->nombre;
	}
	
	function paso($paso = null)
	{
		if (! isset($paso)) {
			$paso = $this->paso_actual;
		}
		if (isset($this->pasos[$paso])) {
			return $this->pasos[$paso];
		} else {
			throw new inst_error("No existe el paso $paso");
		}
	}
	
	function get_cant_pasos()
	{
		return count($this->pasos);		
	}
	
	function get_nro_paso_siguiente()
	{
		return $this->paso_actual + 1;
	}
	
	function get_nro_paso_actual()
	{
		return $this->paso_actual;
	}
	
		
	function get_porcentaje()
	{
		$pasos_completos = max($this->paso_actual - ($this->pasos[$this->paso_actual]->esta_completo() ? 0 : 1), 0);
		if (count($this->pasos) > 1) {
			$porcentaje = (int)((100 * ($pasos_completos / (count($this->pasos)-1))) / 5) * 5;
		} else {
			$porcentaje = $this->pasos[$this->paso_actual]->esta_completo() ? 100 : 0;
		}
		return $porcentaje;
	}	
	
	
	function get_dir_ayudas()
	{
		return INST_DIR."/acciones/{$this->accion}/ayuda";
	}
	
	
	//---------------------------------------------
	//-----	GENERACION URLS
	//---------------------------------------------	
	
	function get_url_ayudas()
	{
		return "acciones/{$this->accion}/ayuda";
	}
	
	function get_url($extra='')
	{
		if ($extra != '') {
			$extra = '&'.$extra;
		}
		return "?accion={$this->accion}$extra";
	}
	
	
	function get_url_paso_siguiente()
	{
		return $this->get_url('paso='.$this->get_nro_paso_siguiente());
	}
	
	function get_url_paso_actual()
	{
		return $this->get_url('paso='.$this->get_nro_paso_actual());
	}	
	

}

?>