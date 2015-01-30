<?php

class paso_diagnosticar_final extends paso
{
	function conf()
	{
		$this->opcional = true;
		$this->completo = true;
		$this->nombre = 'Finalización';
	}
}
?>