<?php
class RespuestaController{
	public static function ObtenerRespuestas($ID_Pregunta,$pdo){
		$respuestas = Respuesta::ObtenerRespuestas($ID_Pregunta, $pdo);
		return $respuestas;
	}
}
?>