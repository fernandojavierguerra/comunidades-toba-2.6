<?php
class dt_tiposgrupo extends toba_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_t.id_tipogrupo,
			t_t.tipogrupo,
			t_c.comunidad as id_comunidad_nombre
		FROM
			tiposgrupo as t_t,
			comunidades as t_c
		WHERE
				t_t.id_comunidad = t_c.id_comunidad
		ORDER BY tipogrupo";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}



		function get_descripciones()
		{
			$sql = "SELECT id_tipogrupo, tipogrupo FROM tiposgrupo ORDER BY tipogrupo";
			//Filtrar por perfil de datos
			$sql = toba::perfil_de_datos()->filtrar($sql);

			return toba::db('comunidades')->consultar($sql);
		}





}
?>