<?php
Class Partida_UsuarioController{
	public static function AgregarUsuario($ID_Partida, $ID_Usuario, $pdo){
		$usuarioPartida = Partida_Usuario::AgregarUsuarioAPartida($ID_Partida, $ID_Usuario, $pdo);
		return $usuarioPartida;
	}
	
	public static function Obtener($ID_Partida, $ID_Usuario, $pdo)
	{
		$partidaUsuario = Partida_Usuario::Obtener($ID_Partida, $ID_Usuario, $pdo);
		return $partidaUsuario;
	}
	
	public static function Finalizar($ID_Partida_Usuario, $pdo)
	{
		$partidaUsuarioFinalizada = Partida_Usuario::Finalizar($ID_Partida_Usuario, $pdo);
		return $partidaUsuarioFinalizada;
	}
}
?>