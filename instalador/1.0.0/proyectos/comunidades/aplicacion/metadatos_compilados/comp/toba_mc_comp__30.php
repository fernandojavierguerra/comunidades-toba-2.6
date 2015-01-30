<?php

class toba_mc_comp__30
{
	static function get_metadatos()
	{
		return array (
  'molde' => 
  array (
    'proyecto' => 'comunidades',
    'molde' => '30',
    'operacion_tipo' => '10',
    'nombre' => 'Tipos Documento',
    'item' => '3498',
    'carpeta_archivos' => 'tipos_documento',
    'prefijo_clases' => '_tipos_documento',
    'fuente' => 'comunidades',
    'clase' => 'toba_asistente_abms',
  ),
  'molde_abms' => 
  array (
    'proyecto' => 'comunidades',
    'molde' => '30',
    'tabla' => 'tiposdocumento',
    'gen_usa_filtro' => 0,
    'gen_separar_pantallas' => 1,
    'cuadro_eof' => NULL,
    'cuadro_id' => 'id_tipodocumento',
    'filtro_comprobar_parametros' => NULL,
    'cuadro_forzar_filtro' => NULL,
    'cuadro_eliminar_filas' => 0,
    'cuadro_carga_origen' => 'datos_tabla',
    'cuadro_carga_sql' => 'SELECT
	t_t.id_tipodocumento,
	t_c.comunidad as id_comunidad_nombre,
	t_t.tipodocumento
FROM
	tiposdocumento as t_t,
	comunidades as t_c
WHERE
		t_t.id_comunidad = t_c.id_comunidad
ORDER BY tipodocumento',
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
      'molde' => '30',
      'fila' => '153',
      'orden' => '1',
      'columna' => 'id_tipodocumento',
      'etiqueta' => 'Id Tipodocumento',
      'en_cuadro' => 0,
      'en_form' => 0,
      'en_filtro' => 0,
      'filtro_operador' => '=',
      'cuadro_estilo' => '0',
      'cuadro_formato' => '7',
      'dt_tipo_dato' => 'E',
      'dt_largo' => NULL,
      'dt_secuencia' => 'tiposdocumento_id_tipodocumento_seq',
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
      'molde' => '30',
      'fila' => '154',
      'orden' => '2',
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
    2 => 
    array (
      'proyecto' => 'comunidades',
      'molde' => '30',
      'fila' => '155',
      'orden' => '3',
      'columna' => 'tipodocumento',
      'etiqueta' => 'Tipodocumento',
      'en_cuadro' => 1,
      'en_form' => 1,
      'en_filtro' => 0,
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
  ),
);
	}

}

?>