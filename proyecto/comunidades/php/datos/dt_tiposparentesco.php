<?php
class dt_tiposparentesco extends toba_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_t.id_parentesco,
			t_c.comunidad as id_comunidad_nombre,
			t_t.parentesco
		FROM
			tiposparentesco as t_t,
			comunidades as t_c
		WHERE
				t_t.id_comunidad = t_c.id_comunidad
		ORDER BY parentesco";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_parentesco, parentesco FROM tiposparentesco ORDER BY parentesco";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);

		return toba::db('comunidades')->consultar($sql);
	}

}
?>