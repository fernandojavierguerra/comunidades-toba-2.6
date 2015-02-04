<?php
class dt_tiposdocumento extends toba_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_t.id_tipodocumento,
			t_c.comunidad as id_comunidad_nombre,
			t_t.tipodocumento
		FROM
			tiposdocumento as t_t,
			comunidades as t_c
		WHERE
				t_t.id_comunidad = t_c.id_comunidad
		ORDER BY tipodocumento";
		
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_tipodocumento, tipodocumento FROM tiposdocumento ORDER BY tipodocumento";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
	
		return toba::db('comunidades')->consultar($sql);
	}



}
?>