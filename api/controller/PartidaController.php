<?php
Class PartidaController
{
	public static function CrearPartida($pdo, $ID_Usuario)
	{
		$partidaDisponible = Partida::ObtenerPartidaDisponible($pdo, $ID_Usuario);
		if($partidaDisponible == null)
		{
			$partidaDisponible = Partida::CrearPartida($pdo);
			// Después de crear la partida, agregamos al usuario a la partida.
			$partidaUsuario = Partida_UsuarioController::AgregarUsuario($partidaDisponible->ID, $ID_Usuario, $pdo);
			// Agregar las preguntas
			$preguntas = PreguntaController::ObtenerPreguntasRandom($pdo, $partidaDisponible->Cantidad_Preguntas);
			for ($i = 0; $i < sizeof($preguntas); $i++)
			{
				$pregunta = $preguntas[$i];
				$partida_pregunta = Partida_PreguntaController::AgregarPregunta($pdo, $partidaDisponible->ID, $pregunta->ID);
			}
		}
		else
		{
			$agregarUsuario = Partida_UsuarioController::AgregarUsuario($partidaDisponible->ID, $ID_Usuario, $pdo);
			$partidaDisponible2 = Partida::DeshabilitarPartida($pdo, $partidaDisponible->ID); // Al agregar segundo jugador deshabilitamos para que no se pueda agregar otro.
		}
		$preguntas = Partida_PreguntaController::ObtenerPreguntasPorPartida($pdo, $partidaDisponible->ID);
		$preguntasReturn = array();
		if (sizeof($preguntas) < 1)
			throw new Exception("Menos de 1 pregunta");
		for ($i = 0; $i < sizeof($preguntas); $i++)
		{
			$pregunta = $preguntas[$i];
			$respuestas = RespuestaController::ObtenerRespuestas(intval($pregunta->ID_Pregunta), $pdo);
			array_push($preguntasReturn, array('Pregunta' => $pregunta, 'Respuestas' => $respuestas));
		}
		$arrayReturn = array('Partida' => $partidaDisponible, 'Preguntas' => $preguntasReturn);
		return $arrayReturn;
	}
}
?>