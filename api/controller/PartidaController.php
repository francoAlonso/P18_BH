<?php
Class PartidaController{
	// Buscar partida disponible (habilitado, y que cantidad de registros Partida_Usuario para esa partida 
	//sea menor que cantidad de usuarios de la partida
	public static function CrearPartida($pdo, $ID_Usuario){

		$array_pregunta = array();
		$i=0;
		while($i<=9){
			$preguntaObtenida = PreguntaController::GenerarPregunta($pdo);
			for($n=0; $n<=sizeof($array_pregunta);$n++){
				if($preguntaObtenida != $array_pregunta[$n]){
					array_push($array_pregunta, json_encode(array($preguntaObtenida)));
					$n = 10;
				}
			}
		}
		for ($i=0; $i <= 10; $i++){
			$preguntaObtenida = PreguntaController::GenerarPregunta($pdo);
			array_push($array_pregunta, json_encode(array($preguntaObtenida)));
			echo $array_pregunta[$i];
		}

		$partidaDisponible = Partida::ObtenerPartidaDisponible($pdo, $ID_Usuario);

		if($partidaDisponible == null){
			$crearPartida = Partida::CrearPartida($pdo);
			// Después de crear la partida, agregamos al usuario a la partida.
			$partidaUsuario = Partida_UsuarioController::AgregarUsuario($crearPartida->ID, $ID_Usuario, $pdo);
			// Falta agregar las preguntas.
			return $crearPartida; 
		}else{
			$agregarUsuario = Partida_UsuarioController::AgregarUsuario($partidaDisponible->ID, $ID_Usuario, $pdo);
			$partidaDisponible = Partida::DeshabilitarPartida($pdo, $partidaDisponible->ID); // Al agregar segundo jugador deshabilitamos para que no se pueda agregar otro.
			return $partidaDisponible;
		}
	}
		// Si está disponible, agregar Partida_Usuario y devolver la lista de preguntas y respuestas.
		// sino hacer lo que sigue (todo): Partida -> CrearPartida
		// Partida_UsuarioController -> AgregarUsuarioAPartida
		// Declarar variable array para contener las preguntas con sus respuestas
		// {partida: datosPartida, [Pregunta: [{"ID":"2","ID_Nivel":"1","Texto":"10?","Habilitado":"1"}], Respuestas: [[{"ID":"3","ID_Pregunta":"2","Texto":"10","Habilitado":"1"},{"ID":"4","ID_Pregunta":"2","Texto":"20","Habilitado":"1"},{"ID":"5","ID_Pregunta":"2","Texto":"30","Habilitado":"1"},{"ID":"6","ID_Pregunta":"2","Texto":"30","Habilitado":"1"}]]]
		// for/while hasta Partida->Cantidad_Preguntas, agregar las preguntas a Partida_Pregunta y guardar la preguntas con sus respuestas al array anterior. VERIFICAR QUE NO SE REPITAN
		// Devolver la partida con sus preguntas y respuestas
}
?>