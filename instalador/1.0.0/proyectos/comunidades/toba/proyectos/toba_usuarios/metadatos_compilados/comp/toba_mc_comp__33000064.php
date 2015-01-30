<?php

class toba_mc_comp__33000064
{
	static function get_metadatos()
	{
		return array (
  '_info' => 
  array (
    'proyecto' => 'toba_usuarios',
    'objeto' => 33000064,
    'anterior' => NULL,
    'identificador' => NULL,
    'reflexivo' => NULL,
    'clase_proyecto' => 'toba',
    'clase' => 'toba_datos_tabla',
    'subclase' => NULL,
    'subclase_archivo' => NULL,
    'objeto_categoria_proyecto' => NULL,
    'objeto_categoria' => NULL,
    'nombre' => 'Usuario - editar - editor - datos - pregunta_secreta',
    'titulo' => NULL,
    'colapsable' => NULL,
    'descripcion' => NULL,
    'fuente_proyecto' => 'toba_usuarios',
    'fuente' => 'toba_usuarios',
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
    'creacion' => '2011-05-17 12:05:26',
    'punto_montaje' => 12000004,
    'clase_editor_proyecto' => 'toba_editor',
    'clase_editor_item' => '1000250',
    'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_tabla.php',
    'clase_vinculos' => NULL,
    'clase_editor' => '1000250',
    'clase_icono' => 'objetos/datos_tabla.gif',
    'clase_descripcion_corta' => 'datos_tabla',
    'clase_instanciador_proyecto' => NULL,
    'clase_instanciador_item' => NULL,
    'objeto_existe_ayuda' => NULL,
    'ap_clase' => NULL,
    'ap_archivo' => NULL,
    'ap_punto_montaje' => 12000004,
    'cant_dependencias' => 0,
    'posicion_botonera' => NULL,
  ),
  '_info_estructura' => 
  array (
    'tabla' => 'apex_usuario_pregunta_secreta',
    'alias' => NULL,
    'min_registros' => NULL,
    'max_registros' => NULL,
    'ap' => 1,
    'punto_montaje' => 12000004,
    'ap_sub_clase' => NULL,
    'ap_sub_clase_archivo' => NULL,
    'ap_modificar_claves' => 0,
    'ap_clase' => 'ap_tabla_db_s',
    'ap_clase_archivo' => 'nucleo/componentes/persistencia/toba_ap_tabla_db_s.php',
    'tabla_ext' => NULL,
    'esquema' => NULL,
    'esquema_ext' => NULL,
  ),
  '_info_columnas' => 
  array (
    0 => 
    array (
      'objeto_proyecto' => 'toba_usuarios',
      'objeto' => 33000064,
      'col_id' => 33000058,
      'columna' => 'cod_pregunta_secreta',
      'tipo' => 'E',
      'pk' => 1,
      'secuencia' => 'apex_usuario_pregunta_secreta_seq',
      'largo' => NULL,
      'no_nulo' => NULL,
      'no_nulo_db' => 1,
      'externa' => 0,
      'tabla' => 'apex_usuario_pregunta_secreta',
    ),
    1 => 
    array (
      'objeto_proyecto' => 'toba_usuarios',
      'objeto' => 33000064,
      'col_id' => 33000059,
      'columna' => 'usuario',
      'tipo' => 'C',
      'pk' => 0,
      'secuencia' => '',
      'largo' => 60,
      'no_nulo' => NULL,
      'no_nulo_db' => 1,
      'externa' => 0,
      'tabla' => 'apex_usuario_pregunta_secreta',
    ),
    2 => 
    array (
      'objeto_proyecto' => 'toba_usuarios',
      'objeto' => 33000064,
      'col_id' => 33000060,
      'columna' => 'pregunta',
      'tipo' => 'X',
      'pk' => 0,
      'secuencia' => '',
      'largo' => NULL,
      'no_nulo' => NULL,
      'no_nulo_db' => 1,
      'externa' => 0,
      'tabla' => 'apex_usuario_pregunta_secreta',
    ),
    3 => 
    array (
      'objeto_proyecto' => 'toba_usuarios',
      'objeto' => 33000064,
      'col_id' => 33000061,
      'columna' => 'respuesta',
      'tipo' => 'X',
      'pk' => 0,
      'secuencia' => '',
      'largo' => NULL,
      'no_nulo' => NULL,
      'no_nulo_db' => 1,
      'externa' => 0,
      'tabla' => 'apex_usuario_pregunta_secreta',
    ),
    4 => 
    array (
      'objeto_proyecto' => 'toba_usuarios',
      'objeto' => 33000064,
      'col_id' => 33000062,
      'columna' => 'activa',
      'tipo' => 'E',
      'pk' => 0,
      'secuencia' => '',
      'largo' => NULL,
      'no_nulo' => NULL,
      'no_nulo_db' => 1,
      'externa' => 0,
      'tabla' => 'apex_usuario_pregunta_secreta',
    ),
  ),
  '_info_externas' => 
  array (
  ),
  '_info_externas_col' => 
  array (
  ),
  '_info_valores_unicos' => 
  array (
  ),
  '_info_fks' => 
  array (
  ),
);
	}

}

?>