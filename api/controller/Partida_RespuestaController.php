<?php
Class Partida_RespuestaController{
	public static function AgregarPartidaRespuesta($ID_Respuesta, $ID_Partida_Pregunta, $ID_Partida_Usuario, $pdo){
		$partidaRespuesta = Partida_Respuesta::AgregarRespuesta($ID_Respuesta, $ID_Partida_Pregunta, $ID_Partida_Usuario, $pdo);
		return $partidaRespuesta;
	}

	public static function VerificarRespuesta($ID_Respuesta, $pdo){
		$partidaRespuesta = Partida_Respuesta::VerificarRespuesta($ID_Respuesta, $pdo);
		$respuesta = Respuesta::ObtenerPorId($partidaRespuesta->ID_Respuesta, $pdo);
		$esCorrecta = $respuesta->esCorrecta();
		return $esCorrecta;
	}
	
	public static function ObtenerRespuestasPartidaUsuario($ID_Partida_Usuario, $pdo)
	{
		$arrPartidaRespuesta = Partida_Respuesta::ObtenerPorPartidaUsuario($ID_Partida_Usuario, $pdo);
	}

}
?>