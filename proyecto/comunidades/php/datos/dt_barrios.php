<?php
class dt_barrios extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['barrio'])) {
			$where[] = "barrio ILIKE ".quote("%{$filtro['barrio']}%");
		}
		$sql = "SELECT
			t_b.id_barrio,
			t_c.comunidad as id_comunidad_nombre,
			t_b.barrio
		FROM
			barrios as t_b,
			comunidades as t_c
		WHERE
				t_b.id_comunidad = t_c.id_comunidad
		ORDER BY barrio";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}


	function get_descripciones()
	{
		$sql = "SELECT id_barrio, barrio FROM barrios ORDER BY barrio";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}



}
?>