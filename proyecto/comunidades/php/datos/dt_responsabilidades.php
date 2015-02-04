<?php
class dt_responsabilidades extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_responsabilidad, responsabilidad FROM responsabilidades ORDER BY responsabilidad";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['id_comunidad'])) {
			$where[] = "t_r.id_comunidad = ".quote($filtro['id_comunidad']);
		}
		$sql = "SELECT
			t_c.comunidad as id_comunidad_nombre,
			t_r.id_responsabilidad,
			t_r.responsabilidad
		FROM
			responsabilidades as t_r,
			comunidades as t_c
		WHERE
				t_r.id_comunidad = t_c.id_comunidad
		ORDER BY responsabilidad";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>