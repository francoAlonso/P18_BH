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
			$partidaDisponible2 = Partida::DeshabilitarPartida($pdo, $partidaDisponible->ID); 
			// Al agregar segundo jugador deshabilitamos para que no se pueda agregar otro.
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
	
	public static function ResponderPregunta($pdo, $ID_Usuario, $ID_Partida, $ID_Pregunta, $ID_Respuesta)
	{
		$partidaUsuario = Partida_UsuarioController::Obtener($ID_Partida, $ID_Usuario, $pdo);
		//$partidaPregunta = Partida_PreguntaController::ObtenerPreguntasPorPartidaPregunta($pdo, $ID_Partida, $ID_Pregunta);
		$partidaPregunta = Partida_Pregunta::ObtenerPorPartidaPregunta($pdo, $ID_Partida, $ID_Pregunta);
		//$partidaRespuesta = Partida_RespuestaController::AgregarPartidaRespuesta($ID_Respuesta, $partidaPregunta->ID, $partidaUsuario->ID, $partidaUsuario, $partidaPregunta, $partidaRespuesta, $pdo);
		$partidaRespuesta = Partida_RespuestaController::AgregarPartidaRespuesta($ID_Respuesta, $partidaPregunta->ID, $partidaUsuario->ID, $pdo);
		$respuesta = RespuestaController::ObtenerPorId($ID_Respuesta, $pdo);
		
		$partidaUsuarioActualFinalizada = PartidaController::PartidaUsuarioActualFinalizada($ID_Partida, $ID_Usuario, $partidaUsuario, $partidaPregunta, $partidaRespuesta, $respuesta, $pdo);
		
		$arrayRespuesta = ['Respuesta_Correcta' => $respuesta->EsCorrecta(), 'Partida_Finalizada' => $partidaUsuarioActualFinalizada];
		return $arrayRespuesta;
	}
	
	public static function PartidaUsuarioActualFinalizada($ID_Partida, $ID_Usuario, $partidaUsuario, $partidaPregunta, $partidaRespuesta, $respuesta, $pdo)
	{
		$correcta = $respuesta->EsCorrecta();
		$finalizarPartidaUsuario = false;
		$finalizarPartida = false;
		if ($correcta == true) // Si la respuesta es incorrecta debe finalizar. Sino debe verificar que se hayan completado las preguntas para finalizar la partida
		{
			$cantidadPreguntasSinResponderUsuario = Partida::CantidadPreguntasSinResponderUsuario($ID_Partida, $ID_Usuario, $pdo);
			if ($cantidadPreguntasSinResponderUsuario < 1)
				$finalizarPartidaUsuario = true;
		}
		else
			$finalizarPartidaUsuario = true;
		if ($finalizarPartidaUsuario == true)
		{
			// Finalizamos la partida del usuario. Luego, verificamos si corresponde finalizar la partida
			$partidaUsuario = Partida_UsuarioController::Finalizar($partidaUsuario->ID, $pdo);
			
			/*$partida = Partida::ObtenerPorId($ID_Partida, $pdo);
			$cantidadUsuariosPartida = $partida->Cantidad_Usuario;*/
			$cantidadNoFinalizada = Partida::CantidadUsuariosNoFinalizados($ID_Partida, $pdo);
			if ($cantidadNoFinalizada > 0)
				$finalizarPartida = false;
			else
				$finalizarPartida = true;
		}
		if ($finalizarPartida == true)
			PartidaController::FinalizarPartida($ID_Partida, $pdo);
		
		return $finalizarPartidaUsuario;
	}
	
	public static function FinalizarPartida($ID_Partida, $pdo)
	{
		$partidaFinalizada = Partida::Finalizar($ID_Partida, $pdo);
		// Calcular puntajes
		$puntajes = Puntaje_Partida_Usuario::ObtenerPorPartida($ID_Partida, $pdo);
		// El primero es el ganador, el segundo el perdedor
		$puntajeGanador = $puntajes[0];
		$puntajePerdedor = $puntajes[1];
		
		$diferenciaPuntaje = $puntajeGanador->Puntaje_Partida - $puntajePerdedor->Puntaje_Partida;
		$puntajeGanadorFinal = $puntajeGanador->Puntaje_Partida + $diferenciaPuntaje;
		$puntajePerdedorFinal = $puntajePerdedor->PuntajePartida - $diferenciaPuntaje;
		
		$usuarioGanador = UsuarioController::ActualizarPuntaje($puntajeGanador->ID_Usuairo, $puntajeGanadorFinal, $pdo);
		$usuarioPerdedor = UsuarioController::ActualizarPuntaje($puntajePerdedorFinal->ID_Usuario, $puntajePerdedorFinal, $pdo);		
		
	}
}
?>