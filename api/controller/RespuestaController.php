<?php
class RespuestaController{
	public static function ObtenerRespuestas($ID_Pregunta,$pdo){
		$respuestas = Respuesta::ObtenerRespuestas($ID_Pregunta, $pdo);
		return $respuestas;
	}
	
	public static function ObtenerPorId($ID_Respuesta, $pdo)
	{
		$respuesta = Respuesta::ObtenerPorId($ID_Respuesta, $pdo);
		return $respuesta;
	}
	
	public static function ObtenerCorrectaPorPregunta($ID_Pregunta, $pdo)
	{
		$respuesta = Respuesta::ObtenerRespuestaCorrecta($ID_Pregunta, $pdo);
		return $respuesta;
	}
}
?>