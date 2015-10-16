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
		include ('./Mail-1.2.0/Mail.php');
		$usuario = UsuarioController::ObtenerPorId($idUsuario, $pdo);
		if ($usuario->ObtenerContrasena() == $contrasenaActual)
		{
			$usuario = Usuario::ActualizarContrasena($idUsuario, $contrasenaNueva, $pdo);
			//Envio mail
			$recipients = $usuario->ObtenerMail();
			$headers['From']    = 'noreply@p18bh.honor.es';
			$headers['To']      = $usuario->ObtenerMail();
			$headers['Subject'] = 'Cambio de contraseña - Buho Trivia';
			
			$body = 'Para aceptar el cambio de su contraseña, ingrese a la siguiente página web: http://localhost:8082/api/confirmarCambioContrasena?token=' . urlencode($usuario->ObtenerCodigoVerificacion());
			
			$smtpinfo["host"] = "mx1.2freehosting.com"; //Server de SMTP (x ejemplo hotmail)
			$smtpinfo["port"] = "2525";
			$smtpinfo["auth"] = true;
			$smtpinfo["username"] = "noreply@p18bh.honor.es"; // Cuenta de mail
			$smtpinfo["password"] = "Puerta18"; // Contraseña del mail
			
			
			// Create the mail object using the Mail::factory method
			$mail_object =& Mail::factory("smtp", $smtpinfo);
			
			$mail_object->send($recipients, $headers, $body);
			return array("Estado" => "Ok", "Descripcion" => "Se le envió un mail a " . $usuario->ObtenerMail() . " para que confirme su cambio de contraseña.");
		}
		else
			return array("Estado" => "Error", "Descripcion" => "La contraseña actual es incorrecta.");
	}
	
	public static function ConfirmarCambioContrasena($codigoConfirmacion, $pdo)
	{
		Usuario::ConfirmarCambioContrasena($codigoConfirmacion, $pdo);
	}
}
?>