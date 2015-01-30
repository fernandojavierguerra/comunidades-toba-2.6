<?php
class dt_sacramentospersona extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['t_s.id_persona'])) {
			$where[] = "id_persona = ".quote($filtro['id_persona']);
		}
		if (isset($filtro['t_s.id_sacramento'])) {
			$where[] = "id_sacramento = ".quote($filtro['id_sacramento']);
		}
		if (isset($filtro['t_s.id_comunidad'])) {
			$where[] = "id_comunidad = ".quote($filtro['id_comunidad']);
		}
		$sql = "SELECT
			t_s.id_sacramentopersona,
			t_p.nombres as id_persona_nombre,
			t_s1.sacramento as id_sacramento_nombre,
			t_c.comunidad as id_comunidad_nombre,
			t_t.templo as id_templo_nombre,
			t_s.fechasacramento
		FROM
			sacramentospersona as t_s    LEFT OUTER JOIN templos as t_t ON (t_s.id_templo = t_t.id_templo),
			personas as t_p,
			sacramentos as t_s1,
			comunidades as t_c
		WHERE
				t_s.id_persona = t_p.id_persona
			AND  t_s.id_sacramento = t_s1.id_sacramento
			AND  t_s.id_comunidad = t_c.id_comunidad";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>