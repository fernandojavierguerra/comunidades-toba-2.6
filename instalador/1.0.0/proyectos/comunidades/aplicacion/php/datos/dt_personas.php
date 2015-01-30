<?php
class dt_personas extends toba_datos_tabla
{
	function get_persona_nombre($id_persona)
	{
		print_r($id_persona);
		$sql = "SELECT apellido ||', '|| nombres as persona_nombre FROM personas WHERE id_persona = {$id_persona}";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}
	
	function get_descripciones()
	{
		$sql = "SELECT id_persona, nombres FROM personas ORDER BY nombres";
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['id_comunidad'])) {
			$where[] = "t_p.id_comunidad = ".quote($filtro['id_comunidad']);
		}
		if (isset($filtro['nombres'])) {
			$where[] = "t_p.nombres ILIKE ".quote("%{$filtro['nombres']}%");
		}
		if (isset($filtro['apellido'])) {
			$where[] = "t_p.apellido ILIKE ".quote("%{$filtro['apellido']}%");
		}
		if (isset($filtro['nrodocumento'])) {
			$where[] = "t_p.nrodocumento ILIKE ".quote("%{$filtro['nrodocumento']}%");
		}
		$sql = "SELECT
			t_p.id_persona,
			t_c.comunidad as id_comunidad_nombre,
			t_p.nombres,
			t_p.apellido,
			t_p.nombres ||', '|| t_p.apellido as apellido_nombre,
			t_p.nrodocumento,
			t_p.fechanacimiento,
			t_p.direccion,
			t_b.barrio as id_barrio_nombre,
			t_t.tipodocumento as id_tipodocumento_nombre,
			t_p.nrotelcelular,
			t_p.nrotelfijo,
			t_p.estado,
			t_p.fechaingreso,
			t_p.email
		FROM
			personas as t_p    LEFT OUTER JOIN barrios as t_b ON (t_p.id_barrio = t_b.id_barrio)
			LEFT OUTER JOIN tiposdocumento as t_t ON (t_p.id_tipodocumento = t_t.id_tipodocumento),
			comunidades as t_c
		WHERE
				t_p.id_comunidad = t_c.id_comunidad
		ORDER BY nombres";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}
	
	function get_listado_personas_grupo($filtro=array())
	{
		$where = array();
		if (isset($filtro['anio'])) {
			$where[] = "anio = ".quote($filtro['anio']);
		}
		if (isset($filtro['grupo'])) {
			$where[] = "t_g.grupo ILIKE ".quote("%{$filtro['grupo']}%");
		}
		if (isset($filtro['id_tipogrupo'])) {
			$where[] = "t_tg.id_tipogrupo = ".quote($filtro['id_tipogrupo']);
		}

		$sql = "SELECT
			t_p.id_comunidad as id_comunidad,
			t_c.comunidad as comunidad,
			t_p.id_persona as id_persona,  
			t_p.nombres as nombres,
			t_p.apellido as apellido,
			t_p.apellido ||', '|| t_p.nombres as apellido_nombres,
			t_t.tipodocumento as tipodocumento,
			t_p.nrodocumento as nrodocumento,
			t_tg.tipogrupo as tipogrupo,
			t_tg.id_tipogrupo as id_tipogrupo,
			t_g.grupo as grupo,
			t_g.anio as anio
		FROM
			personas as t_p 
			LEFT OUTER JOIN tiposdocumento as t_t ON (t_p.id_tipodocumento = t_t.id_tipodocumento),
			personagrupo as t_pg,
			grupos as t_g,
			tiposgrupo as t_tg,
			comunidades as t_c
		WHERE
			t_p.id_comunidad = t_c.id_comunidad AND
			t_p.id_persona   = t_pg.id_persona AND
			t_pg.id_grupo    = t_g.id_grupo AND
			t_g.id_tipogrupo = t_tg.id_tipogrupo
		ORDER BY id_comunidad,anio, id_tipogrupo , apellido_nombres";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//Filtrar por perfil de datos
		$sql = toba::perfil_de_datos()->filtrar($sql);
		return toba::db('comunidades')->consultar($sql);
	}

}
?>