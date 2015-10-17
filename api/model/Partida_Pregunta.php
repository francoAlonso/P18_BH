<?php
class Partida_Pregunta
{
	public $ID;
	public $ID_Partida;
	public $ID_Pregunta;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM partida_pregunta
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Pregunta');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM partida_pregunta
				WHERE ID = :ID
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Pregunta');
		return $statement->fetch();
	}
	public static function ObtenerPorPartida($pdo, $ID_Partida)
	{
		$params = array(':ID_Partida' => $ID_Partida);
		$statement = $pdo->prepare('
				SELECT *
				FROM partida_pregunta
				WHERE ID_Partida = :ID_Partida');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Pregunta');
		return $statement->fetchAll();
	}
	
	public static function ObtenerPorPartidaPregunta($pdo, $ID_Partida, $ID_Pregunta)
	{
		$params = array(':ID_Partida' => $ID_Partida, ':ID_Pregunta' => $ID_Pregunta);
		$statement = $pdo->prepare('
				SELECT *
				FROM partida_pregunta
				WHERE ID_Partida = :ID_Partida AND Id_Pregunta = :ID_Pregunta
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Pregunta');
		return $statement->fetch();
	}
	
	public static function AgregarPregunta($pdo, $ID_Partida, $ID_Pregunta)
	{
		$pdo->beginTransaction();
		$params = array(':ID_Partida' => $ID_Partida, ':ID_Pregunta' => $ID_Pregunta);
		$statement = $pdo->prepare('
				INSERT INTO partida_pregunta (ID_Partida, ID_Pregunta)
				VALUES (:ID_Partida, :ID_Pregunta)');
		$statement->execute($params);
		$idPartidaPregunta = $pdo->lastInsertId();
		$partidaPregunta = Partida_Pregunta::ObtenerPorId($idPartidaPregunta, $pdo);
		$pdo->commit();
		return $partidaPregunta;
	}
	
	
}
?>