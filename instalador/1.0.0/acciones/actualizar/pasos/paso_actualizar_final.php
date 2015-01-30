<?php

class paso_actualizar_final extends paso
{
	function conf()
	{
		$this->opcional = true;
		$this->completo = true;
		$this->con_temporales = true;
		$this->nombre = 'Finalización';
	}
	
	function get_con_temporales()
	{
		return $this->con_temporales;
	}
	
	function procesar()
	{
		if (isset($_GET['eliminar_temporales']) && $_GET['eliminar_temporales'] == 1) {
			$terminado = true;
			//-- Copia Fs Toba
			if (isset($_SESSION['backup_toba']) && file_exists($_SESSION['backup_toba'])) {
				inst::archivos()->eliminar_directorio($_SESSION['backup_toba']);
				if (file_exists($_SESSION['backup_toba'])) {
					$terminado = false;
				}
			}			
			//-- Copia Fs Aplicacion		
			if (isset($_SESSION['backup_aplicacion']) && file_exists($_SESSION['backup_aplicacion'])) {
				inst::archivos()->eliminar_directorio($_SESSION['backup_aplicacion']);
				if (file_exists($_SESSION['backup_aplicacion'])) {
					$terminado = false;
				}				
			}			
			//-- Esquema Toba
			if (isset($_SESSION['backup_schema_toba']) && isset($_SESSION['backup_schema_toba_conexion'])) {
				$conexion = inst::db_manager()->conectar($_SESSION['backup_schema_toba_conexion']);
				if (inst::db_manager()->existe_schema($conexion, $_SESSION['backup_schema_toba'])) {
					inst::db_manager()->borrar_schema($conexion, $_SESSION['backup_schema_toba']);
				}
			}
			if ($terminado) {
				$this->con_temporales = false;
			}
		}

	}
}
?>