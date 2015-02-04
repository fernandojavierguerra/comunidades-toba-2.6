<?php
class dt_sacramentos extends toba_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_s.id_sacramento,
			t_s.sacramento,
			t_c.comunidad as id_comunidad_nombre
		FROM
			sacramentos as t_s,
			comunidades as t_c
		WHERE
				t_s.id_comunidad = t_c.id_comunidad
		ORDER BY sacramento";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}


	function get_descripciones()
	{
		$sql = "SELECT id_sacramento, sacramento FROM sacramentos ORDER BY sacramento";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>