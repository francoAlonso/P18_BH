<?php
Class Partida_PreguntaController{
	public static function AgregarPregunta($pdo, $ID_Partida, $ID_Pregunta)
	{
		$partidaPregunta = Partida_Pregunta::AgregarPregunta($pdo, $ID_Partida, $ID_Pregunta);
		return $partidaPregunta;
	}
	public static function ObtenerPreguntasPorPartida ($pdo, $ID_Partida)
	{
		$preguntas = Pregunta::ObtenerPreguntasPorPartida($pdo, $ID_Partida);
		return $preguntas;
	}
}
?>