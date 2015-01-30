<h1>Publicación del Sistema</h1>
<p>
	La creación de los archivos de configuración se realizó satisfactoriamente. Para poder navegar el sistema debe ser publicado en el servidor web.
	<?php if (! INST_ES_WINDOWS): ?>
		<ol>
			<li>Por favor, incluya el archivo de configuraci&oacute;n <em>toba.conf</em> en la configuración de apache. Para sistemas basados en debian ejecutar como root:<br>
			
		 	<pre class='example'>ln -s <?php echo $_SESSION['path_instalacion']?>/instalacion/toba.conf /etc/apache2/sites-enabled/<?php echo inst::configuracion()->get('proyecto', 'id'); ?>.conf</pre>
			<li>Ahora reinicie el <em>Servidor Apache</em>. Para sistemas basados en debian ejecutar como root:
			
			<pre class='example'>/etc/init.d/apache2 reload</pre>
			<li>Una vez reiniciado haga click en <b>Avanzar al paso siguiente</b><br>
		</ol>
	<?php else: ?>
		<ol>
			<li>Por favor, incluya la siguiente l&iacute;nea en el archivo de configuraci&oacute;n de Apache<br>
			 <pre class='example'>Include "<?php echo $_SESSION['path_instalacion']?>\instalacion\toba.conf"</pre>
			<li>Ahora reinicie el <b>Servidor Apache</b> y luego haga click en <b>Avanzar al paso siguiente</b><br>
		</ol>
	<?php endif; ?>
</p>

	
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_siguiente();?>">Avanzar al paso siguiente</a></span>
</div>