<?php
Class Partida_RespuestaController{
	public static function AgregarPartidaRespuesta($ID_Respuesta, $ID_Partida_Pregunta, $ID_Partida_Usuario, $pdo){
		$partidaRespuesta = Partida_Respuesta::AgregarPartidaRespuesta($ID_Respuesta, $ID_Partida_Pregunta, $ID_Partida_Usuario, $pdo);
		return $partidaRespuesta;
	}

	public static function VerificarRespuesta($ID_Respuesta, $pdo){
		$partidaRespuesta = Partida_Respuesta::VerificarRespuesta($ID_Respuesta, $pdo);
		$respuesta = Respuesta::ObtenerPorId($partidaRespuesta->ID_Respuesta, $pdo);
		$esCorrecta = $respuesta->esCorrecta();
		return $esCorrecta;
	}

}
?>