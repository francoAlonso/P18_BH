<?php
class Partida_Respuesta
{
	public $ID;
	public $ID_Partida_Pregunta;
	public $ID_Partida_Usuario;
	public $ID_Respuesta;
	public $Fecha_Respuesta;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM Partida_Respuesta
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Respuesta');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM Fecha_Respuesta
				WHERE ID = :ID
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Respuesta');
		return $statement->fetch();
	}

	public static function AgregarRespuesta($ID_Respuesta, $ID_Partida_Pregunta, $ID_Partida_Usuario, $pdo){
		$pdo->beginTransaction();
		$now = new DateTime();
		$params = array(':ID_Partida_Pregunta' => $ID_Partida_Pregunta, 
						':ID_Partida_Usuario' => $ID_Partida_Usuario,
						':ID_Respuesta' => $ID_Respuesta, 
						':Fecha_Respuesta' => $now->format('Y-m-d H:i:s'));
		$statement = $pdo->prepare('
				INSERT INTO Partida_Respuesta (ID_Partida_Pregunta, ID_Partida_Usuario, ID_Respuesta, Fecha_Respuesta)
				VALUES (:ID_Partida_Pregunta, :ID_Partida_Usuario, :ID_Respuesta, :Fecha_Respuesta)');
		$statement->execute($params);
		$idPartidaRespuesta = $pdo->lastInsertId();
		$partidaRespuesta = Partida_Respuesta::ObtenerPorId($idPartidaRespuesta, $pdo);
		$pdo->commit();
		return $partidaRespuesta;
	}
	
	public static function VerificarRespuesta($ID_Respuesta, $pdo){
		$params = array(':ID_Respuesta' => $ID_Respuesta);
		$statement = $pdo->prepare('SELECT * FROM Partida_Respuesta WHERE ID_Respuesta = :ID_Respuesta LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Respuesta');
		return $statement->fetch();
	}
	
	public static function ObtenerPorPartidaUsuario($ID_Partida_Usuario, $pdo)
	{
		$params = array(':ID_Partida_Usuario' => $ID_Partida_Usuario);
		$statement = $pdo->prepare('
				SELECT *
				FROM Partida_Respuesta
				WHERE ID_Partida_Usuario = :ID_Partida_Usuario
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Respuesta');
		return $statement->fetchAll();
	}

}
?>