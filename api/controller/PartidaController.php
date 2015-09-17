<?php
Class PartidaController{
	// Buscar partida disponible (habilitado, y que cantidad de registros Partida_Usuario para esa partida 
	//sea menor que cantidad de usuarios de la partida
	public static function CrearPartida($pdo, $ID_Usuario){
		$partidaDisponible = Partida::ObtenerPartidaDisponible($pdo);
		if($partidaDisponible == null){
			$crearPartida = Partida::CrearPartida($pdo);
			return $crearPartida; 
		}else{
			$agregarUsuario = Partida_UsuarioController::AgregarUsuario($partidaDisponible, $ID_Usuario, $pdo);
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