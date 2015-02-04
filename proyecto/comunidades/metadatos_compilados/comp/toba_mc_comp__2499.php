<?php

class toba_mc_comp__2499
{
	static function get_metadatos()
	{
		return array (
  '_info' => 
  array (
    'proyecto' => 'comunidades',
    'objeto' => '2499',
    'anterior' => NULL,
    'identificador' => NULL,
    'reflexivo' => NULL,
    'clase_proyecto' => 'toba',
    'clase' => 'toba_datos_relacion',
    'subclase' => NULL,
    'subclase_archivo' => NULL,
    'objeto_categoria_proyecto' => NULL,
    'objeto_categoria' => NULL,
    'nombre' => 'Personas - DR',
    'titulo' => NULL,
    'colapsable' => NULL,
    'descripcion' => NULL,
    'fuente_proyecto' => 'comunidades',
    'fuente' => 'comunidades',
    'solicitud_registrar' => NULL,
    'solicitud_obj_obs_tipo' => NULL,
    'solicitud_obj_observacion' => NULL,
    'parametro_a' => NULL,
    'parametro_b' => NULL,
    'parametro_c' => NULL,
    'parametro_d' => NULL,
    'parametro_e' => NULL,
    'parametro_f' => NULL,
    'usuario' => NULL,
    'creacion' => '2015-01-15 13:53:30',
    'punto_montaje' => '16',
    'clase_editor_proyecto' => 'toba_editor',
    'clase_editor_item' => '1000251',
    'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_relacion.php',
    'clase_vinculos' => NULL,
    'clase_editor' => '1000251',
    'clase_icono' => 'objetos/datos_relacion.gif',
    'clase_descripcion_corta' => 'datos_relacion',
    'clase_instanciador_proyecto' => NULL,
    'clase_instanciador_item' => NULL,
    'objeto_existe_ayuda' => NULL,
    'ap_clase' => NULL,
    'ap_archivo' => NULL,
    'ap_punto_montaje' => '16',
    'cant_dependencias' => '4',
    'posicion_botonera' => NULL,
  ),
  '_info_estructura' => 
  array (
    'proyecto' => 'comunidades',
    'objeto' => '2499',
    'debug' => 0,
    'ap' => '2',
    'punto_montaje' => '16',
    'ap_clase' => NULL,
    'ap_archivo' => NULL,
    'sinc_susp_constraints' => 0,
    'sinc_orden_automatico' => 1,
    'sinc_lock_optimista' => 1,
  ),
  '_info_relaciones' => 
  array (
    0 => 
    array (
      'proyecto' => 'comunidades',
      'objeto' => '2499',
      'asoc_id' => '81',
      'padre_proyecto' => 'comunidades',
      'padre_objeto' => '2494',
      'padre_id' => 'personas',
      'hijo_proyecto' => 'comunidades',
      'hijo_objeto' => '2510',
      'hijo_id' => 'sacramentospersona',
      'cascada' => NULL,
      'orden' => '1',
    ),
    1 => 
    array (
      'proyecto' => 'comunidades',
      'objeto' => '2499',
      'asoc_id' => '82',
      'padre_proyecto' => 'comunidades',
      'padre_objeto' => '2494',
      'padre_id' => 'personas',
      'hijo_proyecto' => 'comunidades',
      'hijo_objeto' => '2491',
      'hijo_id' => 'personagrupo',
      'cascada' => NULL,
      'orden' => '2',
    ),
    2 => 
    array (
      'proyecto' => 'comunidades',
      'objeto' => '2499',
      'asoc_id' => '83',
      'padre_proyecto' => 'comunidades',
      'padre_objeto' => '2494',
      'padre_id' => 'personas',
      'hijo_proyecto' => 'comunidades',
      'hijo_objeto' => '2523',
      'hijo_id' => 'familiares',
      'cascada' => NULL,
      'orden' => '3',
    ),
  ),
  '_info_dependencias' => 
  array (
    0 => 
    array (
      'identificador' => 'personas',
      'proyecto' => 'comunidades',
      'objeto' => '2494',
      'clase' => 'toba_datos_tabla',
      'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_tabla.php',
      'subclase' => 'dt_personas',
      'subclase_archivo' => 'datos/dt_personas.php',
      'fuente' => 'comunidades',
      'parametros_a' => '1',
      'parametros_b' => '1',
    ),
    1 => 
    array (
      'identificador' => 'sacramentospersona',
      'proyecto' => 'comunidades',
      'objeto' => '2510',
      'clase' => 'toba_datos_tabla',
      'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_tabla.php',
      'subclase' => 'dt_sacramentospersona',
      'subclase_archivo' => 'datos/dt_sacramentospersona.php',
      'fuente' => 'comunidades',
      'parametros_a' => '0',
      'parametros_b' => '7',
    ),
    2 => 
    array (
      'identificador' => 'personagrupo',
      'proyecto' => 'comunidades',
      'objeto' => '2491',
      'clase' => 'toba_datos_tabla',
      'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_tabla.php',
      'subclase' => 'dt_personagrupo',
      'subclase_archivo' => 'datos/dt_personagrupo.php',
      'fuente' => 'comunidades',
      'parametros_a' => '0',
      'parametros_b' => NULL,
    ),
    3 => 
    array (
      'identificador' => 'familiares',
      'proyecto' => 'comunidades',
      'objeto' => '2523',
      'clase' => 'toba_datos_tabla',
      'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_tabla.php',
      'subclase' => 'dt_familiares',
      'subclase_archivo' => 'datos/dt_familiares.php',
      'fuente' => 'comunidades',
      'parametros_a' => '0',
      'parametros_b' => NULL,
    ),
  ),
  '_info_columnas_asoc_rel' => 
  array (
    0 => 
    array (
      'asoc_id' => '81',
      'proyecto' => 'comunidades',
      'objeto' => '2499',
      'hijo_clave' => '989',
      'hijo_objeto' => '2510',
      'col_hija' => 'id_persona',
      'padre_objeto' => '2494',
      'padre_clave' => '971',
      'col_padre' => 'id_persona',
    ),
    1 => 
    array (
      'asoc_id' => '82',
      'proyecto' => 'comunidades',
      'objeto' => '2499',
      'hijo_clave' => '967',
      'hijo_objeto' => '2491',
      'col_hija' => 'id_persona',
      'padre_objeto' => '2494',
      'padre_clave' => '971',
      'col_padre' => 'id_persona',
    ),
    2 => 
    array (
      'asoc_id' => '83',
      'proyecto' => 'comunidades',
      'objeto' => '2499',
      'hijo_clave' => '994',
      'hijo_objeto' => '2523',
      'col_hija' => 'id_persona',
      'padre_objeto' => '2494',
      'padre_clave' => '971',
      'col_padre' => 'id_persona',
    ),
  ),
);
	}

}

?>