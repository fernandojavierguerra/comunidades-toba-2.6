<?php

class toba_mc_gene__dim_14
{
	static function get_info()
	{
		return array (
  'proyecto' => 'comunidades',
  'dimension' => '14',
  'nombre' => 'Comunidades',
  'descripcion' => NULL,
  'schema' => NULL,
  'tabla' => 'comunidades',
  'col_id' => 'id_comunidad',
  'col_desc' => 'comunidad',
  'col_desc_separador' => NULL,
  'multitabla_col_tabla' => NULL,
  'multitabla_id_tabla' => NULL,
  'fuente_datos_proyecto' => 'comunidades',
  'fuente_datos' => 'comunidades',
  'gatillos' => 
  array (
    0 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '28',
      'tipo' => 'directo',
      'orden' => '1',
      'tabla_rel_dim' => 'comunidades',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    1 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '21',
      'tipo' => 'directo',
      'orden' => '2',
      'tabla_rel_dim' => 'barrios',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    2 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '22',
      'tipo' => 'directo',
      'orden' => '3',
      'tabla_rel_dim' => 'grupos',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    3 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '23',
      'tipo' => 'directo',
      'orden' => '4',
      'tabla_rel_dim' => 'sacramentos',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    4 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '24',
      'tipo' => 'directo',
      'orden' => '5',
      'tabla_rel_dim' => 'templos',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    5 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '25',
      'tipo' => 'directo',
      'orden' => '6',
      'tabla_rel_dim' => 'tiposdocumento',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    6 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '26',
      'tipo' => 'directo',
      'orden' => '7',
      'tabla_rel_dim' => 'tiposgrupo',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    7 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '27',
      'tipo' => 'directo',
      'orden' => '8',
      'tabla_rel_dim' => 'tiposparentesco',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    8 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '29',
      'tipo' => 'directo',
      'orden' => '9',
      'tabla_rel_dim' => 'personas',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
    9 => 
    array (
      'proyecto' => 'comunidades',
      'dimension' => '14',
      'gatillo' => '30',
      'tipo' => 'directo',
      'orden' => '10',
      'tabla_rel_dim' => 'personagrupo',
      'columnas_rel_dim' => 'id_comunidad',
      'tabla_gatillo' => NULL,
      'ruta_tabla_rel_dim' => NULL,
    ),
  ),
);
	}

}

?>