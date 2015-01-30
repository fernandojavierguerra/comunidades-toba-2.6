<?php

class inst_version
{
	protected $partes;
	protected $build;
	protected $inestable;
	protected $inestables = array('pre-alpha', 'alpha', 'beta', 'rc');
	
	function __construct($numero)
	{
		if ($numero == 'trunk') {
			$this->build = 'trunk';
			return ;
		}
		$formato = 'El formato debe ser x.y.z (inestable-build). Donde (inestable-build) es opcional';
		$numero = trim($numero);
		$this->build = null;
		$this->partes = explode('.', $numero);
		//Validando el numero
		if (count($this->partes) < 3) {
			throw new inst_error("El n�mero de versi�n $numero es incorrecto. Se requiere al menos 3 partes. ".$formato);
		}
		if (!is_numeric($this->partes[2])) {
			$digito = intval($this->partes[2]);
			if (! is_numeric($digito)) {
				throw new inst_error("El n�mero de versi�n $numero es incorrecto. Las partes deben ser num�ricas. ".$formato);
			}
			$extra = trim(str_replace(array('(',')'), '', substr($this->partes[2], strlen($digito))));			
			$this->partes[2] = $digito;
			$extra = explode('-', $extra);
			$build = '';
			if (count($extra) == 2) {
				$inestable = $extra[0];
				$build = $extra[1];
			} else {
				if (is_numeric($extra[0])) {
					$build = $extra[0];
				} else {
					$inestable = $extra[0];
				}
			}
			if (isset($inestable)) {
				if (! in_array($inestable, $this->inestables)) {
					throw new inst_error("El n�mero de versi�n $numero es incorrecto. El codigo de inestable '$inestable' no es v�lido. ".$formato);
				}
				$this->inestable = $inestable;				
			}
			if ($build != '') {
				$this->build = $build;				
				if (! is_numeric($build)) {
					throw new inst_error("El n�mero de versi�n $numero es incorrecto. El codigo de build '$build' no es v�lido. ".$formato);					
				}
			}
		}
		foreach ($this->partes as $parte) {
			if (!is_numeric($parte) || !is_int(intval($parte))) {
				throw new inst_error("El n�mero de versi�n $numero es incorrecto. Las partes deben ser num�ricas ".$formato);
			}
		}
		
	}
	
	function __toString()
	{
		if ($this->build == 'trunk') {
			return 'trunk';
		}
		$s = implode('.', $this->partes);
		if (isset($this->build) || isset($this->inestable)) {
			$s .= ' (';
			if (isset($this->inestable)) {
				$s .= $this->inestable;
			}
			if (isset($this->build)) {
				if (isset($this->inestable)) {
					$s .= '-';
				}
				$s .= $this->build;
			}
			$s .= ')';
		}
		return $s;
	}

	function get_string_partes($separador = '_')
	{
		if ($this->build == 'trunk') {
			return 'trunk';
		}		
		$s = $this->__toString();
		return str_replace('.', $separador, $s);
	}
	
	function get_release($separador = '.')
	{
		if ($this->build == 'trunk') {
			return 'trunk';
		}		
		return $this->partes[0].$separador.$this->partes[1];
	}
	
	function get_build()
	{
		return $this->build;
	}	
	
	function get_builds_intermedios($hasta)
	{
		$intermedios = array(); 
		if (isset($this->build) && isset($hasta->build)) {
			$desde = $this->build;
			$hasta = $hasta->build;
			$rango = range($desde, $hasta);		
			if ($desde < $hasta) {
				return array_splice($rango, 1);
			} else {
				return array_splice($rango, 0, -1);				
			}
		}
		return $intermedios;
	}	
	

	function es_igual($version)
	{
		return $this->comparar($version) == 0;
	}
	
	function es_menor($version)
	{
		return $this->comparar($version) < 0;
	}
	
	function es_mayor($version)
	{
		return $this->comparar($version) > 0;
	}
	
	function es_mayor_igual($version)
	{
		return ($this->comparar($version) >= 0);
	}
	
	function es_menor_igual($version)
	{
		return ($this->comparar($version) <= 0);		
	}	
	
	/**
	 * Compara dos versiones y retorna si la actual es mayor (1), igual (0) o menor (-1)
	 */
	function comparar($otra_version)
	{
		if ($this->build == 'trunk') {
			if ($otra_version->build == 'trunk') {
				return 0;
			} else {
				return 1;
			}
		}
		if ($otra_version->build == 'trunk') {
			return -1;
		}		
		foreach ($otra_version->partes as $pos => $parte) {
			if ($this->partes[$pos] < $parte) {
				return -1;	//Es menor
			} else if ($this->partes[$pos] > $parte) {
				return 1;	//Es mayor
			}
		}
		if (isset($this->inestable) || isset($otra_version->inestable)) {
			if (isset($this->inestable)) {
				$indice_actual = array_search($this->inestable, $this->inestables);
			} else {
				$indice_actual = 100;
			}
			if (isset($otra_version->inestable)) {
				$indice_otra = array_search($otra_version->inestable, $this->inestables);
			} else {
				$indice_otra = 100;
			}
			//-- Son otro relase?
			if ($indice_actual < $indice_otra) {
				return -1;
			}
			if ($indice_actual > $indice_otra) {
				return 1;
			}
		}		
		
		//--- Tienen los mismos numeros, se decide por build
		if (! isset($this->build) && !isset($otra_version->build)) {
			return 0; //Ambos no tiene build, se asumen iguales		
		} elseif (! isset($this->build)) {
			return -1; //El otro tiene build, este es menor
		} elseif (! isset($otra_version->build)) {
			return 1; //El otro no tiene build, este es mayor
		}		
		$numero_actual = intval($this->build);
		$numero_otra = intval($otra_version->build);
		if ($numero_actual < $numero_otra) {
			return -1;			
		}
		if ($numero_actual > $numero_otra) {
			return 1;
		}
		return 0; //Son iguales		
	}

	static function comparar_versiones($v1, $v2)
	{
		return $v1->comparar($v2);
	}
	
	function es_cambio_menor_version(inst_version $otra_version)
	{
		if (!isset($otra_version)) {
			return false;
		}
		if ($this->build == 'trunk' || $otra_version->build == 'trunk') {
			return false;
		}
		//-- Tiene que ser igual numero mayor
		if ($otra_version->partes[0] != $this->partes[0]) {
			return false;
		}
		//-- Tiene que ser igual numero medio
		if ($otra_version->partes[1] != $this->partes[1]) {
			return false;
		}		
		return true;
	}	
	
	/**
	 * @return inst_version
	 */
	function get_siguiente_menor()
	{
		if ($this->build == 'trunk') {
			return $this;
		}		
		$siguiente = $this->partes[2] + 1;
		return new inst_version($this->partes[0].'.'.$this->partes[1].'.'.$siguiente);
	}
	
	static function inicial()
	{
		return new inst_version("0.8.3");
	}
	
}


?>
