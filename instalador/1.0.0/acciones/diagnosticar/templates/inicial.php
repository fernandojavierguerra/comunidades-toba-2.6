<h1>Diagn�stico del Sistema</h1>
<p>
	Bienvenido al asistente de diagn�sito del sistema <b><?php echo inst::configuracion()->get('proyecto','nombre'); ?></b>.<br><br>
	Este asistente lo guiar� para recolectar toda la informaci�n necesaria para poder diagnosticar errores tanto en la instalaci�n como en la ejecuci�n del sistema.
	Tambi�n puede visualizar el registro de sucesos del instalador y la configuraci�n de PHP.
</p>

<?php
$logo_php = '<img src="' . $_SERVER['PHP_SELF'] .'?=' . php_logo_guid() . '" style="border: 0; width: 80px;vertical-align:middle" />';
?>
<a  href='<?php echo inst::accion()->get_url('ver_log=1'); ?>'>
<br>Logs del instalador</a>
<br><br>
<a  href='<?php echo inst::accion()->get_url('phpinfo=1'); ?>'>
Informaci�n sobre instalaci�n PHP <?php echo phpversion(); ?></a>
<br><br>
<?php if (isset($_GET['ver_log'])) { ?>
<pre class='diagnostico-logs'>
<?php
	echo htmlentities(file_get_contents(inst::logger()->get_archivo()));
?>
</pre>
<?php } ?>
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_siguiente();?>">Avanzar al paso siguiente&raquo;</a></span>
</div>

