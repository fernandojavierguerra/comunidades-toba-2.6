<?php

class toba_mc_comp__33
{
	static function get_metadatos()
	{
		return array (
  'molde' => 
  array (
    'proyecto' => 'comunidades',
    'molde' => '33',
    'operacion_tipo' => '10',
    'nombre' => 'Grupos',
    'item' => '3502',
    'carpeta_archivos' => 'grupos',
    'prefijo_clases' => '_grupos',
    'fuente' => 'comunidades',
    'clase' => 'toba_asistente_abms',
  ),
  'molde_abms' => 
  array (
    'proyecto' => 'comunidades',
    'molde' => '33',
    'tabla' => 'grupos',
    'gen_usa_filtro' => 1,
    'gen_separar_pantallas' => 1,
    'cuadro_eof' => NULL,
    'cuadro_id' => 'id_grupo',
    'filtro_comprobar_parametros' => 0,
    'cuadro_forzar_filtro' => 1,
    'cuadro_eliminar_filas' => NULL,
    'cuadro_carga_origen' => 'datos_tabla',
    'cuadro_carga_sql' => 'SELECT
	t_g.id_grupo,
	t_g.grupo,
	t_c.comunidad as id_comunidad_nombre,
	t_t.tipogrupo as id_tipogrupo_nombre,
	t_g.anio
FROM
	grupos as t_g,
	comunidades as t_c,
	tiposgrupo as t_t
WHERE
		t_g.id_comunidad = t_c.id_comunidad
	AND  t_g.id_tipogrupo = t_t.id_tipogrupo
ORDER BY grupo',
    'cuadro_carga_php_include' => NULL,
    'cuadro_carga_php_clase' => NULL,
    'cuadro_carga_php_metodo' => NULL,
    'datos_tabla_validacion' => NULL,
    'apdb_pre' => NULL,
  ),
  'molde_abms_fila' => 
  array (
    0 => 
    array (
      'proyecto' => 'comunidades',
      'molde' => '33',
      'fila' => '162',
      'orden' => '1',
      'columna' => 'id_grupo',
      'etiqueta' => 'Id Grupo',
      'en_cuadro' => 0,
      'en_form' => 0,
      'en_filtro' => 0,
      'filtro_operador' => '=',
      'cuadro_estilo' => '0',
      'cuadro_formato' => '7',
      'dt_tipo_dato' => 'E',
      'dt_largo' => NULL,
      'dt_secuencia' => 'grupos_id_grupo_seq',
      'dt_pk' => 1,
      'elemento_formulario' => 'ef_editable_numero',
      'ef_desactivar_modificacion' => NULL,
      'ef_procesar_javascript' => NULL,
      'ef_obligatorio' => 0,
      'ef_carga_origen' => NULL,
      'ef_carga_sql' => NULL,
      'ef_carga_tabla' => NULL,
      'ef_carga_php_include' => NULL,
      'ef_carga_php_clase' => NULL,
      'ef_carga_php_metodo' => NULL,
      'ef_carga_col_clave' => NULL,
      'ef_carga_col_desc' => NULL,
    ),
    1 => 
    array (
      'proyecto' => 'comunidades',
      'molde' => '33',
      'fila' => '163',
      'orden' => '2',
      'columna' => 'grupo',
      'etiqueta' => 'Grupo',
      'en_cuadro' => 1,
      'en_form' => 1,
      'en_filtro' => 1,
      'filtro_operador' => 'ILIKE',
      'cuadro_estilo' => '4',
      'cuadro_formato' => '1',
      'dt_tipo_dato' => 'C',
      'dt_largo' => NULL,
      'dt_secuencia' => '',
      'dt_pk' => 0,
      'elemento_formulario' => 'ef_editable',
      'ef_desactivar_modificacion' => NULL,
      'ef_procesar_javascript' => NULL,
      'ef_obligatorio' => 1,
      'ef_carga_origen' => NULL,
      'ef_carga_sql' => NULL,
      'ef_carga_tabla' => NULL,
      'ef_carga_php_include' => NULL,
      'ef_carga_php_clase' => NULL,
      'ef_carga_php_metodo' => NULL,
      'ef_carga_col_clave' => NULL,
      'ef_carga_col_desc' => NULL,
    ),
    2 => 
    array (
      'proyecto' => 'comunidades',
      'molde' => '33',
      'fila' => '164',
      'orden' => '3',
      'columna' => 'id_comunidad',
      'etiqueta' => 'Id Comunidad',
      'en_cuadro' => 1,
      'en_form' => 1,
      'en_filtro' => 0,
      'filtro_operador' => '=',
      'cuadro_estilo' => '4',
      'cuadro_formato' => '1',
      'dt_tipo_dato' => 'C',
      'dt_largo' => NULL,
      'dt_secuencia' => '',
      'dt_pk' => 0,
      'elemento_formulario' => 'ef_combo',
      'ef_desactivar_modificacion' => NULL,
      'ef_procesar_javascript' => NULL,
      'ef_obligatorio' => 1,
      'ef_carga_origen' => 'datos_tabla',
      'ef_carga_sql' => 'SELECT id_comunidad, comunidad FROM comunidades ORDER BY comunidad',
      'ef_carga_tabla' => 'comunidades',
      'ef_carga_php_include' => NULL,
      'ef_carga_php_clase' => NULL,
      'ef_carga_php_metodo' => NULL,
      'ef_carga_col_clave' => 'id_comunidad',
      'ef_carga_col_desc' => 'comunidad',
    ),
    3 => 
    array (
      'proyecto' => 'comunidades',
      'molde' => '33',
      'fila' => '165',
      'orden' => '4',
      'columna' => 'id_tipogrupo',
      'etiqueta' => 'Id Tipogrupo',
      'en_cuadro' => 1,
      'en_form' => 1,
      'en_filtro' => 1,
      'filtro_operador' => '=',
      'cuadro_estilo' => '4',
      'cuadro_formato' => '1',
      'dt_tipo_dato' => 'C',
      'dt_largo' => NULL,
      'dt_secuencia' => '',
      'dt_pk' => 0,
      'elemento_formulario' => 'ef_combo',
      'ef_desactivar_modificacion' => NULL,
      'ef_procesar_javascript' => NULL,
      'ef_obligatorio' => 1,
      'ef_carga_origen' => 'datos_tabla',
      'ef_carga_sql' => 'SELECT id_tipogrupo, tipogrupo FROM tiposgrupo ORDER BY tipogrupo',
      'ef_carga_tabla' => 'tiposgrupo',
      'ef_carga_php_include' => NULL,
      'ef_carga_php_clase' => NULL,
      'ef_carga_php_metodo' => NULL,
      'ef_carga_col_clave' => 'id_tipogrupo',
      'ef_carga_col_desc' => 'tipogrupo',
    ),
    4 => 
    array (
      'proyecto' => 'comunidades',
      'molde' => '33',
      'fila' => '166',
      'orden' => '5',
      'columna' => 'anio',
      'etiqueta' => 'Anio',
      'en_cuadro' => 1,
      'en_form' => 1,
      'en_filtro' => 1,
      'filtro_operador' => '=',
      'cuadro_estilo' => '0',
      'cuadro_formato' => '7',
      'dt_tipo_dato' => 'E',
      'dt_largo' => NULL,
      'dt_secuencia' => '',
      'dt_pk' => 0,
      'elemento_formulario' => 'ef_editable_numero',
      'ef_desactivar_modificacion' => NULL,
      'ef_procesar_javascript' => NULL,
      'ef_obligatorio' => 1,
      'ef_carga_origen' => NULL,
      'ef_carga_sql' => NULL,
      'ef_carga_tabla' => NULL,
      'ef_carga_php_include' => NULL,
      'ef_carga_php_clase' => NULL,
      'ef_carga_php_metodo' => NULL,
      'ef_carga_col_clave' => NULL,
      'ef_carga_col_desc' => NULL,
    ),
  ),
);
	}

}

?>