<?php
class UsuarioController
{
	public static function ObtenerPorId ($id, $pdo)
	{
		$usuario = Usuario::ObtenerPorId($id, $pdo);
		return $usuario;
	}
	public static function CrearUsuario($idGerencia, $idSede, $dni, $nombre, $contrasena, $mail, $puntaje, $pdo)
	{
		$pdo->beginTransaction();
		$usuario = Usuario::CrearUsuario($idGerencia, $idSede, $dni, $nombre, $contrasena, $mail, $puntaje, $pdo);
		$pdo->commit();
		return $usuario;
	}
	public static function ObtenerTodos($pdo)
	{
		$listaUsuarios = Usuario::ObtenerTodos($pdo);
		return $listaUsuarios;
	}

	public static function Logeo($nombre, $contrasena, $pdo){
		$logeo = Usuario::Login($nombre, $contrasena, $pdo);
		return $logeo;
	}
	
	public static function ActualizarPuntaje($ID_Usuario, $puntaje, $pdo)
	{
		$usuario = UsuarioController::ObtenerPorId($ID_Usuario, $pdo);
		$puntaje += $usuario->Puntaje;
		$usuario = Usuario::ActualizarPuntaje($ID_Usuario, $puntaje, $pdo);
		return $usuario;
	}
	
	public static function CambiarContrasena($idUsuario, $contrasenaActual, $contrasenaNueva, $pdo)
	{
		//include ('./Mail-1.2.0/Mail.php');
		$usuario = UsuarioController::ObtenerPorId($idUsuario, $pdo);
		if ($usuario->ObtenerContrasena() == hash('sha256', $contrasenaActual))
		{
			$usuario = Usuario::ActualizarContrasena($idUsuario, $contrasenaNueva, $pdo);
			//Envio mail
			/*$recipients = $usuario->ObtenerMail();
			$headers['From']    = 'puerta18bh@gmail.com';
			$headers['To']      = $usuario->ObtenerMail();
			$headers['Subject'] = 'Cambio de contraseña - Buho Trivia';
			
			$body = 'Para aceptar el cambio de su contraseña, ingrese a la siguiente página web: http://p18bh.ml/api/confirmarCambioContrasena?token=' . urlencode($usuario->ObtenerCodigoVerificacion());
			
			$smtpinfo["host"] = "smtp.gmail.com"; //Server de SMTP (x ejemplo hotmail)
			$smtpinfo["port"] = "25";
			$smtpinfo["auth"] = true;
			$smtpinfo["username"] = "puerta18bh@gmail.com"; // Cuenta de mail
			$smtpinfo["password"] = "Puerta18"; // Contraseña del mail
			
			
			// Create the mail object using the Mail::factory method
			$mail_object =& Mail::factory("smtp", $smtpinfo);
			
			$mail_object->send($recipients, $headers, $body);*/
			/*require './PHPMailer-master/PHPMailerAutoload.php';
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;
			
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';
			
			//Set the hostname of the mail server
			$mail->Host = gethostbyname('smtp.gmail.com');
			// use
			// $mail->Host = gethostbyname('smtp.gmail.com');
			// if your network does not support SMTP over IPv6
			
			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$mail->Port = 587;
			
			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'tls';
			
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			
			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = "puerta18bh@gmail.com";
			
			//Password to use for SMTP authentication
			$mail->Password = "Puerta18";
			
			//Set who the message is to be sent from
			$mail->setFrom('puerta18bh@gmail.com', 'Puerta 18');
			
			//Set an alternative reply-to address
			//$mail->addReplyTo('replyto@example.com', 'First Last');
			
			//Set who the message is to be sent to
			$mail->addAddress($usuario->ObtenerMail(), $usuario->Nombre_Completo);
			
			//Set the subject line
			$mail->Subject = 'Cambio de contraseña - Eco Trivia';
			
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
			$mail->msgHTML('Para aceptar el cambio de su contraseña, ingrese a la siguiente página web: http://ecotrivia.tk/api/confirmarCambioContrasena.php?token=' . urlencode($usuario->ObtenerCodigoVerificacion()));
			//Replace the plain text body with one created manually
			//$mail->AltBody = 'This is a plain-text message body';
			
			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.png');
			
			//send the message, check for errors
			if (!$mail->send()) {
				//echo "Mailer Error: " . $mail->ErrorInfo;
				return array("EstadoBool" => false, "Estado" => "Error", "Descripcion" => "No se pudo enviar mail de cambio de contraseña. La contraseña no será cambiada. Reintente más tarde.");
			} else {
				//echo "Message sent!";
			}*/
			
			$to = $usuario->ObtenerMail();
			$subject = 'Ecotrivia - Cambio de contraseña';
			$message = 'Para aceptar el cambio de su contraseña, ingrese a la siguiente página web: http://ecotrivia.tk/api/confirmarCambioContrasena.php?token=' . urlencode($usuario->ObtenerCodigoVerificacion());
			$headers = 'From: noreply@ecotrivia.tk' . "\r\n" .
				'Reply-To: noreply@ecotrivia.tk' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$enviado = false;
			if (@mail($to, $subject, $message, $headers)) {
				$enviado = true;
			} else {
				$enviado = false;
				return array("EstadoBool" => false, "Estado" => "Error", "Descripcion" => "No se pudo enviar mail de cambio de contraseña. La contraseña no será cambiada. Reintente más tarde.");
			}
			
			return array("EstadoBool" => true, "Estado" => "Ok", "Descripcion" => "Se le envió un mail a " . $usuario->ObtenerMail() . " para que confirme su cambio de contraseña.");
		}
		else
			return array("EstadoBool" => false, "Estado" => "Error", "Descripcion" => "La contraseña actual es incorrecta.");
	}
	
	public static function ConfirmarCambioContrasena($codigoConfirmacion, $pdo)
	{
		Usuario::ConfirmarCambioContrasena($codigoConfirmacion, $pdo);
	}
}
?>