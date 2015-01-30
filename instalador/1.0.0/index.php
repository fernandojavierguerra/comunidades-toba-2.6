<?php
     
try {
	define('INST_DIR', dirname(__FILE__));
	require_once(INST_DIR.'/lib/inst.php');
	inst::iniciar();
	inst::controlador()->procesar();
} catch (Exception $e) {
	echo "<h3>".$e->getMessage()."</h3>";	
	inst::logger()->error($e);
}

?>