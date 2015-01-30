<?php
class dt_comunidades extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_comunidad, comunidad FROM comunidades ORDER BY comunidad";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);

		return toba::db('comunidades')->consultar($sql);
	}


	function get_listado()
	{
		$sql = "SELECT
			t_c.id_comunidad,
			t_c.comunidad,
			t_c.direccion,
			t_c.telefono
		FROM
			comunidades as t_c
		ORDER BY comunidad";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>