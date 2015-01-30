<h1>Control de Requisitos Previos</h1>

<div class="systemChecks">
	<table>
    	<?php
    		$fallo_control = false; 
    		foreach (inst::paso()->get_controles() as $control): ?>
      	<tr>
			<td>
	  			<?php print $control['titulo'] ?>
			</td>

			<td style="float: right">
	  			<?php if(! $control['error']): ?>
	    			<h2 class="success"> <?php print ('Correcto') ?> </h2>
	  			<?php elseif($control['severidad'] == 'warning'): ?>
	    			<h2 class="warning"> <?php print ('Precauci&oacute;n') ?> </h2>
	  			<?php elseif($control['severidad'] == 'error'): ?>
	    			<h2 class="error"> <?php print ('Error') ?> </h2>
	    		<?php else: ?>
	    			<h2 class="<?php echo $control['severidad'];?>"> <?php print $control['severidad']; ?> </h2>
	  			<?php endif; ?>
			</td>
      	</tr>

      		<?php if($control['error'] && isset($control['mensaje'])): ?>
	      	<tr>
				<td colspan="2" class="notice">
		  			<?php print $control['mensaje']; ?>
				</td>
	      	</tr>
	      	<?php	$fallo_control = true; 
	      		endif; ?>
		<?php endforeach; 
		
		
?>
	</table>	
<br>

</div>

<br>

<?php if ($fallo_control): ?>
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_actual();?>">Chequear Nuevamente</a></span>
</div>
<?php endif; ?>


<?php if (!inst::paso()->tiene_errores()): ?>
<div class="go">
		<span class="goToNext"><a href="<?php echo inst::accion()->get_url_paso_siguiente();?>">Avanzar al paso siguiente&raquo;</a></span>
</div>
<?php endif; ?>
