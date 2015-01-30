<?php

class sesion
{
	function iniciar()
	{
		if(ini_get('session.auto_start')) {
			throw new inst_error('Desactive la directiva "session.auto_start" del php.ini');
		}
		session_start();		
		if(!isset($_SESSION['INST_DIR'])) {
		    $_SESSION['INST_DIR'] = INST_DIR;
		} else if($_SESSION['INST_DIR'] != INST_DIR) {
			$error = "Error de seguridad! Esta sesion no es valida para esta copia del instalador. Empezar de nuevo.";
		    inst::logger()->error($error);
		    header('Location: ?reiniciar=1');
		}
	}
	
	
}


?>