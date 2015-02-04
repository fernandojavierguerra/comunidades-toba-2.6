<?php
class dt_grupos extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['grupo'])) {
			$where[] = "t_g.grupo ILIKE ".quote("%{$filtro['grupo']}%");
		}
		if (isset($filtro['id_comunidad'])) {
			$where[] = "t_g.id_comunidad = ".quote($filtro['id_comunidad']);
		}
		if (isset($filtro['id_tipogrupo'])) {
			$where[] = "t_g.id_tipogrupo = ".quote($filtro['id_tipogrupo']);
		}
		if (isset($filtro['anio'])) {
			$where[] = "t_g.anio = ".quote($filtro['anio']);
		}
		$sql = "SELECT
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
		ORDER BY grupo";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}



	function get_descripciones()
	{
		$sql = "SELECT id_grupo, grupo FROM grupos ORDER BY grupo";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>