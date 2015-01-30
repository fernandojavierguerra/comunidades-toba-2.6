<?php

class inst_migraciones
{
	
	private $version_base_actual;
	private $version_aplicacion_actual;
	private $migraciones;
	private $conexion;
	private $esquema;	
	
	public function __construct($conexion, $esquema) {
		
		$this->version_base_actual = $this->get_version_actual($conexion);
		$this->version_aplicacion_actual = new inst_version(inst::configuracion()->get('proyecto', 'version'));
		$this->conexion = $conexion;
		$this->esquema = $esquema;
	}
	
	public function migrar() {
		try {
			inst::db_manager()->abrir_transaccion($this->conexion);
			$this->pre_conversion();
			$this->convertir_datos();
			$this->post_conversion();
			inst::db_manager()->cerrar_transaccion($this->conexion);
		}
		catch(Exception $e) {
			inst::db_manager()->abortar_transaccion($this->conexion);
			throw $e;
		}
	}	
	
	protected function pre_conversion() {
		
		// La base que se va a convertir tiene que tener por lo menos la estructura de la base Pampa en 5.4.0
		if($this->version_base_actual > "4" && $this->version_base_actual < "5.4")
			throw new Exception("conversor - pre_conversion(): La base que est&aacute; queriendo convertir no es compatible. La base tiene que ser mayor o igual que 5.4");
		
		// Hay versiones de Pampa que pueden tener cambios que este conversor aun no contempla.
		// Por tal motivo, no puedo convertir desde esa base.
		if($this->version_base_actual > Parametro::get('SISTEMA', 'maxima_version'))
			throw new Exception("conversor - pre_conversion(): La base que est&aacute; queriendo convertir no es compatible. Hay cambios introducidos en su sistema que no son soportados a&uacute;n por esta versi&oacute;n");

		// Puede ser que la tabla cambios no exista por 2 motivos:
		//  * Vengo de una base Pampa 5.4 (todavia no se habia creado)
		//  * Vengo de una base nueva de Mapuche (que se hizo a partir de la 5.4 y que tampoco tiene la tabla)
		// Si no existe, la creo.
		if(!$this->existe_tabla_cambios()) {
			// TODO: Falta loguear este evento
			$this->crear_tabla_cambios();
		}		
	}

	protected function post_conversion() {

		// Cambio la version en rrhhini
		// TODO: Falta loguear este evento
		$this->actualizar_version_rrhhini(Parametro::get('SISTEMA', 'version_base'));

	}
	
	
}

?>