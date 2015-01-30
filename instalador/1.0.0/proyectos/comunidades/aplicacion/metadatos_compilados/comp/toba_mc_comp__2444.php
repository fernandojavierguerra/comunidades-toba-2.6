<?php

class toba_mc_comp__2444
{
	static function get_metadatos()
	{
		return array (
  '_info' => 
  array (
    'proyecto' => 'comunidades',
    'objeto' => '2444',
    'anterior' => NULL,
    'identificador' => NULL,
    'reflexivo' => NULL,
    'clase_proyecto' => 'toba',
    'clase' => 'toba_ci',
    'subclase' => 'ci_login',
    'subclase_archivo' => 'login/ci_login.php',
    'objeto_categoria_proyecto' => NULL,
    'objeto_categoria' => NULL,
    'nombre' => 'Login Genérico',
    'titulo' => 'Autentificación de Usuarios',
    'colapsable' => 0,
    'descripcion' => NULL,
    'fuente_proyecto' => 'comunidades',
    'fuente' => 'comunidades',
    'solicitud_registrar' => NULL,
    'solicitud_obj_obs_tipo' => NULL,
    'solicitud_obj_observacion' => NULL,
    'parametro_a' => NULL,
    'parametro_b' => NULL,
    'parametro_c' => NULL,
    'parametro_d' => NULL,
    'parametro_e' => NULL,
    'parametro_f' => NULL,
    'usuario' => NULL,
    'creacion' => '2006-07-20 13:55:33',
    'punto_montaje' => '16',
    'clase_editor_proyecto' => 'toba_editor',
    'clase_editor_item' => '1000249',
    'clase_archivo' => 'nucleo/componentes/interface/toba_ci.php',
    'clase_vinculos' => NULL,
    'clase_editor' => '1000249',
    'clase_icono' => 'objetos/multi_etapa.gif',
    'clase_descripcion_corta' => 'ci',
    'clase_instanciador_proyecto' => 'toba_editor',
    'clase_instanciador_item' => '1642',
    'objeto_existe_ayuda' => NULL,
    'ap_clase' => NULL,
    'ap_archivo' => NULL,
    'ap_punto_montaje' => NULL,
    'cant_dependencias' => '5',
    'posicion_botonera' => 'abajo',
  ),
  '_info_eventos' => 
  array (
  ),
  '_info_puntos_control' => 
  array (
  ),
  '_info_ci' => 
  array (
    'ev_procesar_etiq' => NULL,
    'ev_cancelar_etiq' => NULL,
    'objetos' => NULL,
    'ancho' => NULL,
    'alto' => NULL,
    'posicion_botonera' => NULL,
    'tipo_navegacion' => NULL,
    'con_toc' => 0,
    'botonera_barra_item' => 0,
  ),
  '_info_ci_me_pantalla' => 
  array (
    0 => 
    array (
      'pantalla' => '1241',
      'identificador' => 'login',
      'etiqueta' => 'Login',
      'descripcion' => NULL,
      'tip' => NULL,
      'imagen_recurso_origen' => 'apex',
      'imagen' => NULL,
      'objetos' => NULL,
      'eventos' => NULL,
      'orden' => 1,
      'punto_montaje' => '16',
      'subclase' => 'pant_login',
      'subclase_archivo' => 'login/pant_login.php',
      'template' => NULL,
      'template_impresion' => NULL,
    ),
    1 => 
    array (
      'pantalla' => '1240',
      'identificador' => 'cambiar_contrasenia',
      'etiqueta' => 'Contraseña',
      'descripcion' => NULL,
      'tip' => NULL,
      'imagen_recurso_origen' => 'apex',
      'imagen' => NULL,
      'objetos' => NULL,
      'eventos' => NULL,
      'orden' => 2,
      'punto_montaje' => '16',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'template' => NULL,
      'template_impresion' => NULL,
    ),
  ),
  '_info_obj_pantalla' => 
  array (
    0 => 
    array (
      'pantalla' => '1240',
      'proyecto' => 'comunidades',
      'objeto_ci' => '2444',
      'dep_id' => '1305',
      'orden' => 0,
      'identificador_pantalla' => 'cambiar_contrasenia',
      'identificador_dep' => 'form_passwd_vencido',
    ),
    1 => 
    array (
      'pantalla' => '1241',
      'proyecto' => 'comunidades',
      'objeto_ci' => '2444',
      'dep_id' => '1304',
      'orden' => 0,
      'identificador_pantalla' => 'login',
      'identificador_dep' => 'datos',
    ),
    2 => 
    array (
      'pantalla' => '1241',
      'proyecto' => 'comunidades',
      'objeto_ci' => '2444',
      'dep_id' => '1306',
      'orden' => 1,
      'identificador_pantalla' => 'login',
      'identificador_dep' => 'openid',
    ),
    3 => 
    array (
      'pantalla' => '1241',
      'proyecto' => 'comunidades',
      'objeto_ci' => '2444',
      'dep_id' => '1307',
      'orden' => 2,
      'identificador_pantalla' => 'login',
      'identificador_dep' => 'seleccion_usuario',
    ),
    4 => 
    array (
      'pantalla' => '1241',
      'proyecto' => 'comunidades',
      'objeto_ci' => '2444',
      'dep_id' => '1303',
      'orden' => 3,
      'identificador_pantalla' => 'login',
      'identificador_dep' => 'cas',
    ),
  ),
  '_info_evt_pantalla' => 
  array (
  ),
  '_info_dependencias' => 
  array (
    0 => 
    array (
      'identificador' => 'cas',
      'proyecto' => 'comunidades',
      'objeto' => '2439',
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'comunidades',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    1 => 
    array (
      'identificador' => 'datos',
      'proyecto' => 'comunidades',
      'objeto' => '2440',
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'comunidades',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    2 => 
    array (
      'identificador' => 'form_passwd_vencido',
      'proyecto' => 'comunidades',
      'objeto' => '2441',
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'comunidades',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    3 => 
    array (
      'identificador' => 'openid',
      'proyecto' => 'comunidades',
      'objeto' => '2442',
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'comunidades',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    4 => 
    array (
      'identificador' => 'seleccion_usuario',
      'proyecto' => 'comunidades',
      'objeto' => '2443',
      'clase' => 'toba_ei_cuadro',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_cuadro.php',
      'subclase' => 'cuadro_autologin',
      'subclase_archivo' => 'login/cuadro_autologin.php',
      'fuente' => 'comunidades',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
  ),
);
	}

}

?>