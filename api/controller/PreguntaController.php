<?php
Class PreguntaController{
	public static function GenerarPregunta($pdo){
		$pregunta = Pregunta::ObtenerPreguntaRandom($pdo);
		return $pregunta;
	}
	
	public static function ObtenerPreguntasRandom($pdo, $cantidad)
	{
		$preguntas = Pregunta::ObtenerPreguntasRandom($pdo, $cantidad);
		return $preguntas;
	}
}
?>