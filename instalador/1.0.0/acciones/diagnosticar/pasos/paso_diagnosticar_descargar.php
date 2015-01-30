<?php

class paso_diagnosticar_descargar extends paso
{
	protected $datos_configuracion;
		
	function conf()
	{
		$this->nombre = 'Resultado';
	}
	
	function procesar()
	{
		$this->resetear_errores();
		if (! empty($_POST)) {
			$this->datos_configuracion = $_POST;
			$this->validar_parametros();
			if (! $this->tiene_errores()) {
				$this->enviar_mail();
			}
			if (! $this->tiene_errores()) {
				$this->set_completo();
			}
		}		
	}	
	
	function enviar_mail()
	{
	   	$mail = new PHPMailer();
	   	$mail->IsSMTP();
	   	//$mail->SMTPDebug = true;
		$mail->Timeout  = 30;
		$host = trim($this->datos_configuracion['smtp_host']);
		if ($this->datos_configuracion['smtp_seguridad'] == 'ssl') {
			if (! extension_loaded('openssl')) {
				$this->set_error('no_ssl', 'Para usar encriptación SSL es necesario activar la extensión "openssl" en el php.ini');
				return;				
			} else {
				$host = 'ssl://'.$host;
			}
		}		
		$mail->Host     = trim($host);
		if (isset($this->datos_configuracion['smtp_auth']) && $this->datos_configuracion['smtp_auth']) {
			$mail->SMTPAuth = true;
			$mail->Username = trim($this->datos_configuracion['smtp_usuario']);
			$mail->Password = trim($this->datos_configuracion['smtp_clave']);
		}		
		$mail->From     = trim($this->datos_configuracion['smtp_from']);
		$mail->FromName = trim($this->datos_configuracion['smtp_from']);
		$mail->AddAddress(trim($this->datos_configuracion['smtp_to']));
		$cc = trim($this->datos_configuracion['smtp_cc']);
		if ($cc != ''){	
			$mail->AddCC($cc);
		}	
		$mail->Subject  = "Diagnóstico ".inst::configuracion()->get('proyecto', 'id').' '.inst::configuracion()->get('proyecto', 'version');
		$mail->Body     = trim($this->datos_configuracion['smtp_body']);
		$mail->AddAttachment($_SESSION['archivo_diagnostico'], basename($_SESSION['archivo_diagnostico']));
		
		if(!$mail->Send()) {
			$this->set_error('no_envio', $mail->ErrorInfo);
		}			
	}
	
	
	function validar_parametros()
	{
		if (trim($this->datos_configuracion['smtp_from']) == '') {
			$this->set_error('no_from', 'Debe indicar el remitente del correo');
		} else {
			if (! preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $this->datos_configuracion['smtp_from'])) {
				$this->set_error('email_invalido', 'La dirección de E-Mail del remitente no es válida');				
			}
		}
		if (trim($this->datos_configuracion['smtp_to']) == '') {
			$this->set_error('no_from', 'Debe indicar el destino del correo');
		} else {
			if (! preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $this->datos_configuracion['smtp_to'])) {
				$this->set_error('email_invalido', 'La dirección de E-Mail del destino no es válida');				
			}
		}		
		if (trim($this->datos_configuracion['smtp_host']) == '') {
			$this->set_error('no_host', 'Debe indicar el nombre o dirección ip del servidor SMTP');
		}
	}
	
	
	function get_datos_configuracion()
	{
		if (! isset($this->datos_configuracion)) {
			return $this->get_datos_configuracion_defecto();
		} else {
			return $this->datos_configuracion;
		}
	}
	
	function get_datos_configuracion_defecto()
	{
		$datos_defecto = array(
			'smtp_from' => '',
			'smtp_to' => inst::configuracion()->get('proyecto', 'mail_soporte'),
			'smtp_cc' => '',
			'smtp_host' => '',
			'smtp_auth' => false,
			'smtp_usuario' => '',
			'smtp_clave' => '',
			'smtp_seguridad' => '',
			'smtp_body' => 'Mensaje para el equipo de soporte'
		);
		if (isset($_SESSION['path_instalacion'])) {
			$archivo_smtp = $_SESSION['path_instalacion'].'/instalacion/smtp.ini';
			if (file_exists($archivo_smtp)) {
				$archivo_ini = new inst_ini($archivo_smtp);
				if ($archivo_ini->existe_entrada('instalacion')) {
					foreach($archivo_ini->get_datos_entrada('instalacion') as $clave => $valor) {
						$datos_defecto['smtp_'.$clave] = $valor;
					}
				}
			}
		}
		return $datos_defecto;
	}	

	function get_correo_destino()
	{
		return $this->datos_configuracion['smtp_to'];	
	}	
	
	function get_tamanio_archivo()
	{
		$bytes = filesize($_SESSION['archivo_diagnostico']);
        $s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($bytes)/log(1024));
        return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
	}
	
	
	function get_url_archivo()
	{
		return 'logs/'.$this->get_nombre_archivo();	
	}
	
	function get_nombre_archivo()
	{
		return basename($_SESSION['archivo_diagnostico']);
	}
	
}
?>