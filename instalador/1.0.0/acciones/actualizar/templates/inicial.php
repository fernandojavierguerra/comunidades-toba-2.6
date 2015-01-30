<h1><?php echo inst::accion()->get_descripcion(); ?></h1>
<p>
	Bienvenido a la actualizaci�n del sistema <b><?php echo inst::configuracion()->get('proyecto','nombre'); ?></b>.<br><br>
	El proceso de actualizaci�n consiste en una serie de <?php echo inst::accion()->get_cant_pasos() - 1; ?> pasos. 
	Este programa lo guiar&aacute; a trav&eacute;s de estos pasos y le proveer&aacute; asistencia a lo largo de todo el proceso para que al finalizar pueda tener el sistema funcionando.
	Una vez que haya completado un paso, podr&aacute; regresar e introducir cambios en cualquier momento.<br><br>
</p>

<p>
Para consultas sobre la instalaci�n de la plataforma tecnol�gica requerida para esta aplicaci�n por favor utilizar el
<a href='http://comunidad.siu.edu.ar/' target='_blank'>Foro Comunidad</a>
</p>
<br>
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_siguiente();?>">Comenzar Actualizaci�n</a></span>
</div>