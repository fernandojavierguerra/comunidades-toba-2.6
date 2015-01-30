<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class instalador_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'accion_actualizar' => 'acciones/actualizar/accion_actualizar.php',
		'paso_actualizar_destino' => 'acciones/actualizar/pasos/paso_actualizar_destino.php',
		'paso_actualizar_final' => 'acciones/actualizar/pasos/paso_actualizar_final.php',
		'paso_actualizar_inicial' => 'acciones/actualizar/pasos/paso_actualizar_inicial.php',
		'paso_actualizar_migrar' => 'acciones/actualizar/pasos/paso_actualizar_migrar.php',
		'paso_actualizar_requisitos' => 'acciones/actualizar/pasos/paso_actualizar_requisitos.php',
		'paso_actualizar_resguardar' => 'acciones/actualizar/pasos/paso_actualizar_resguardar.php',
		'accion_actualizarparcial' => 'acciones/actualizarparcial/accion_actualizarparcial.php',
		'paso_actualizarparcial_actualizar' => 'acciones/actualizarparcial/pasos/paso_actualizarparcial_actualizar.php',
		'paso_actualizarparcial_destino' => 'acciones/actualizarparcial/pasos/paso_actualizarparcial_destino.php',
		'paso_actualizarparcial_final' => 'acciones/actualizarparcial/pasos/paso_actualizarparcial_final.php',
		'paso_actualizarparcial_inicial' => 'acciones/actualizarparcial/pasos/paso_actualizarparcial_inicial.php',
		'paso_actualizarparcial_requisitos' => 'acciones/actualizarparcial/pasos/paso_actualizarparcial_requisitos.php',
		'paso_actualizarparcial_resguardar' => 'acciones/actualizarparcial/pasos/paso_actualizarparcial_resguardar.php',
		'accion_diagnosticar' => 'acciones/diagnosticar/accion_diagnosticar.php',
		'paso_diagnosticar_descargar' => 'acciones/diagnosticar/pasos/paso_diagnosticar_descargar.php',
		'paso_diagnosticar_final' => 'acciones/diagnosticar/pasos/paso_diagnosticar_final.php',
		'paso_diagnosticar_inicial' => 'acciones/diagnosticar/pasos/paso_diagnosticar_inicial.php',
		'paso_diagnosticar_recolectar' => 'acciones/diagnosticar/pasos/paso_diagnosticar_recolectar.php',
		'accion_instalar' => 'acciones/instalar/accion_instalar.php',
		'paso_instalar_bases' => 'acciones/instalar/pasos/paso_instalar_bases.php',
		'paso_instalar_configuracion' => 'acciones/instalar/pasos/paso_instalar_configuracion.php',
		'paso_instalar_destino' => 'acciones/instalar/pasos/paso_instalar_destino.php',
		'paso_instalar_final' => 'acciones/instalar/pasos/paso_instalar_final.php',
		'paso_instalar_inicial' => 'acciones/instalar/pasos/paso_instalar_inicial.php',
		'paso_instalar_publicacion' => 'acciones/instalar/pasos/paso_instalar_publicacion.php',
		'paso_instalar_requisitos' => 'acciones/instalar/pasos/paso_instalar_requisitos.php',
		'accion' => 'lib/accion.php',
		'archivos' => 'lib/archivos.php',
		'auditoria_tablas_postgres' => 'lib/auditoria_tablas_postgres.php',
		'configuracion' => 'lib/configuracion.php',
		'controlador' => 'lib/controlador.php',
		'controles' => 'lib/controles.php',
		'db_manager' => 'lib/db_manager.php',
		'editor_texto' => 'lib/editor_archivos.php',
		'editor_archivos' => 'lib/editor_archivos.php',
		'inst' => 'lib/inst.php',
		'inst_error' => 'lib/inst_error.php',
		'inst_ini' => 'lib/inst_ini.php',
		'inst_migraciones' => 'lib/inst_migraciones.php',
		'inst_version' => 'lib/inst_version.php',
		'logger' => 'lib/logger.php',
		'manejador_negocio' => 'lib/manejador_negocio.php',
		'paso' => 'lib/paso.php',
		'paso_requisitos_base' => 'lib/pasos_comunes/paso_requisitos_base.php',
		'PHPMailer' => 'lib/phpmailer/class.phpmailer.php',
		'POP3' => 'lib/phpmailer/class.pop3.php',
		'SMTP' => 'lib/phpmailer/class.smtp.php',
		'sesion' => 'lib/sesion.php',
		'toba' => 'lib/toba_lib.php',
		'base_intermedia' => 'lib/toba_lib.php',
		'toba_lib' => 'lib/toba_lib.php',
	);
}
?>