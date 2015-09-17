<?php
Class Partida_UsuarioController{
	public static function AgregarUsuario($ID_Partida, $ID_Usuario, $pdo){
		$usuarioPartida = Partida_Usuario::AgregarUsuarioAPartida($ID_Partida, $ID_Usuario, $pdo);
		return $usuarioPartida;
	}
}
?>