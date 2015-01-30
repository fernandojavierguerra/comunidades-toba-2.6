<?php
class dt_personagrupo extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['id_persona'])) {
			$where[] = "t_p.id_persona = ".quote($filtro['id_persona']);
		}
		if (isset($filtro['id_grupo'])) {
			$where[] = "t_p.id_grupo = ".quote($filtro['id_grupo']);
		}
		if (isset($filtro['id_comunidad'])) {
			$where[] = "t_p.id_comunidad = ".quote($filtro['id_comunidad']);
		}
		$sql = "SELECT
			t_p.id_persona,
			t_p.id_grupo,
			t_c.comunidad as id_comunidad_nombre,
			t_r.responsabilidad as id_responsabilidad_nombre,
			per.apellido||', '|| per.nombres as id_persona_nombre,
			gru.grupo as id_grupo_nombre
			
		FROM
			personagrupo as t_p    LEFT OUTER JOIN comunidades as t_c ON (t_p.id_comunidad = t_c.id_comunidad)
			LEFT OUTER JOIN responsabilidades as t_r ON (t_p.id_responsabilidad = t_r.id_responsabilidad)
			LEFT JOIN personas as per ON (t_p.id_persona = per.id_persona)
			LEFT JOIN grupos as gru ON (t_p.id_grupo = gru.id_grupo)";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>