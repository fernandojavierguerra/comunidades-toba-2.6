<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class comunidades_autoload 
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
		'comunidades_ci' => 'extension_toba/componentes/comunidades_ci.php',
		'comunidades_cn' => 'extension_toba/componentes/comunidades_cn.php',
		'comunidades_datos_relacion' => 'extension_toba/componentes/comunidades_datos_relacion.php',
		'comunidades_datos_tabla' => 'extension_toba/componentes/comunidades_datos_tabla.php',
		'comunidades_ei_arbol' => 'extension_toba/componentes/comunidades_ei_arbol.php',
		'comunidades_ei_archivos' => 'extension_toba/componentes/comunidades_ei_archivos.php',
		'comunidades_ei_calendario' => 'extension_toba/componentes/comunidades_ei_calendario.php',
		'comunidades_ei_codigo' => 'extension_toba/componentes/comunidades_ei_codigo.php',
		'comunidades_ei_cuadro' => 'extension_toba/componentes/comunidades_ei_cuadro.php',
		'comunidades_ei_esquema' => 'extension_toba/componentes/comunidades_ei_esquema.php',
		'comunidades_ei_filtro' => 'extension_toba/componentes/comunidades_ei_filtro.php',
		'comunidades_ei_firma' => 'extension_toba/componentes/comunidades_ei_firma.php',
		'comunidades_ei_formulario' => 'extension_toba/componentes/comunidades_ei_formulario.php',
		'comunidades_ei_formulario_ml' => 'extension_toba/componentes/comunidades_ei_formulario_ml.php',
		'comunidades_ei_grafico' => 'extension_toba/componentes/comunidades_ei_grafico.php',
		'comunidades_ei_mapa' => 'extension_toba/componentes/comunidades_ei_mapa.php',
		'comunidades_servicio_web' => 'extension_toba/componentes/comunidades_servicio_web.php',
		'comunidades_comando' => 'extension_toba/comunidades_comando.php',
		'comunidades_modelo' => 'extension_toba/comunidades_modelo.php',
	);
}
?>