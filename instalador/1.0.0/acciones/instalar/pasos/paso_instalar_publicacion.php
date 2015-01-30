<?php
class paso_instalar_publicacion extends paso
{
	function conf()
	{
		$this->nombre = 'Publicación';
	}

	function procesar()
	{
		//Creo el archivo que setea las variables de entorno para dejar disponible una consola
		$toba_lib = new toba_lib();		
		$toba_lib->generar_consola_administrativa();			
		//Desactivo los servicios web que puedan haber quedado en el sistema
		if (method_exists($toba_lib, 'desactivar_servicios_web')) {
			$proyecto = inst::configuracion()->get('proyecto', 'id');
			$_SESSION['servicios_web_desactivados'] = $toba_lib->desactivar_servicios_web($proyecto);
		}		
		
		$this->set_completo();		
	}
	

}
?>