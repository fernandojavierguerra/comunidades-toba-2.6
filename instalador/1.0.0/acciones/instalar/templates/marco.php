<?php
if (inst::paso()->get_id() == 'final') {
	inst::accion()->set_mostrar_pasos(false);
} else {
	inst::accion()->set_mostrar_pasos(true);
}
include(INST_DIR.'/lib/templates_comunes/marco.php');
?>