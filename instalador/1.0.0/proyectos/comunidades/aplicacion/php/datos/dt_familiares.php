<?php
class dt_familiares extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['id_persona'])) {
			$where[] = "id_persona = ".quote($filtro['id_persona']);
		}
		if (isset($filtro['id_comunidad'])) {
			$where[] = "id_comunidad = ".quote($filtro['id_comunidad']);
		}
		$sql = "SELECT
			t_f.id_persona,
			t_f.id_parentesco,
			t_c.comunidad as id_comunidad_nombre,
			t_f.id_familiar
		FROM
			familiares as t_f,
			comunidades as t_c
		WHERE
				t_f.id_comunidad = t_c.id_comunidad";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>