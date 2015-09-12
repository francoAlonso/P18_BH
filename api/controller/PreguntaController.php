<?php
Class PreguntaController{
	public static function GenerarPregunta($pdo){
		$pregunta = Pregunta::ObtenerPreguntaRandom($pdo);
		return $pregunta;
	}
}
?>