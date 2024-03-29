<h1><?php echo inst::accion()->get_descripcion(); ?></h1>
<p>
	Bienvenido al programa de instalaci&oacute;n del sistema <b><?php echo inst::configuracion()->get('proyecto','nombre'); ?></b>.<br><br>
	La instalaci&oacute;n consiste en una serie de <?php echo inst::accion()->get_cant_pasos() - 1; ?> pasos. 
	El instalador lo guiar&aacute; a trav&eacute;s de estos pasos y le proveer&aacute; asistencia a lo largo de todo el proceso para que al finalizar pueda tener el sistema funcionando.
	Una vez que haya completado un paso, podr&aacute; regresar e introducir cambios en cualquier momento.<br><br>
</p>

<p>
Para consultas sobre la instalación de la plataforma tecnológica requerida para esta aplicación por favor utilizar el
<a href='http://comunidad.siu.edu.ar/' target='_blank'>Foro Comunidad</a>
</p>
<br>
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_siguiente();?>">Comenzar la Instalaci&oacute;n&raquo;</a></span>
</div>