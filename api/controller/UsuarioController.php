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

	public static function Logeo($nombre, $contrasena, $dni, $pdo){
		$logeo = Usuario::Login($nombre, $contrasena, $dni, $pdo);
		return $logeo;
	}
}
?>