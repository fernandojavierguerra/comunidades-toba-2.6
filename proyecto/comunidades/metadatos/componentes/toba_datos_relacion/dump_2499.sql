------------------------------------------------------------
--[2499]--  Personas - DR 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_relacion', --clase
	'16', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'Personas - DR', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'comunidades', --fuente_datos_proyecto
	'comunidades', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2015-01-15 13:53:30', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_datos_rel
------------------------------------------------------------
INSERT INTO apex_objeto_datos_rel (proyecto, objeto, debug, clave, ap, punto_montaje, ap_clase, ap_archivo, sinc_susp_constraints, sinc_orden_automatico, sinc_lock_optimista) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'0', --debug
	NULL, --clave
	'2', --ap
	'16', --punto_montaje
	NULL, --ap_clase
	NULL, --ap_archivo
	'0', --sinc_susp_constraints
	'1', --sinc_orden_automatico
	'1'  --sinc_lock_optimista
);

------------------------------------------------------------
-- apex_objeto_dependencias
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'comunidades', --proyecto
	'1382', --dep_id
	'2499', --objeto_consumidor
	'2523', --objeto_proveedor
	'familiares', --identificador
	'0', --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'4'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'comunidades', --proyecto
	'1376', --dep_id
	'2499', --objeto_consumidor
	'2491', --objeto_proveedor
	'personagrupo', --identificador
	'0', --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'3'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'comunidades', --proyecto
	'1351', --dep_id
	'2499', --objeto_consumidor
	'2494', --objeto_proveedor
	'personas', --identificador
	'1', --parametros_a
	'1', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'1'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'comunidades', --proyecto
	'1369', --dep_id
	'2499', --objeto_consumidor
	'2510', --objeto_proveedor
	'sacramentospersona', --identificador
	'0', --parametros_a
	'7', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'2'  --orden
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_datos_rel_asoc
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'81', --asoc_id
	NULL, --identificador
	'comunidades', --padre_proyecto
	'2494', --padre_objeto
	'personas', --padre_id
	NULL, --padre_clave
	'comunidades', --hijo_proyecto
	'2510', --hijo_objeto
	'sacramentospersona', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'1'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'82', --asoc_id
	NULL, --identificador
	'comunidades', --padre_proyecto
	'2494', --padre_objeto
	'personas', --padre_id
	NULL, --padre_clave
	'comunidades', --hijo_proyecto
	'2491', --hijo_objeto
	'personagrupo', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'2'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'83', --asoc_id
	NULL, --identificador
	'comunidades', --padre_proyecto
	'2494', --padre_objeto
	'personas', --padre_id
	NULL, --padre_clave
	'comunidades', --hijo_proyecto
	'2523', --hijo_objeto
	'familiares', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'3'  --orden
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_rel_columnas_asoc
------------------------------------------------------------
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'81', --asoc_id
	'2494', --padre_objeto
	'971', --padre_clave
	'2510', --hijo_objeto
	'989'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'82', --asoc_id
	'2494', --padre_objeto
	'971', --padre_clave
	'2491', --hijo_objeto
	'967'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'comunidades', --proyecto
	'2499', --objeto
	'83', --asoc_id
	'2494', --padre_objeto
	'971', --padre_clave
	'2523', --hijo_objeto
	'994'  --hijo_clave
);
