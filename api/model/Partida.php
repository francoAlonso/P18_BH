<?php
class Partida
{
	public $ID;
	public $Habilitado;
	public $Fecha_Inicio;
	public $Fecha_Fin;
	public $Cantidad_Usuario;
	public $Cantidad_Preguntas;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM Partida
				WHERE Habilitado = 1
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM Partida
				WHERE ID = :ID
				AND Habilitado = 1
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida');
		return $statement->fetch();
	}
	public static function CrearPartida($pdo){
		$pdo->beginTransaction();
		$now = new DateTime();
		$params = array(':Habilitado' => 1, ':Fecha_Inicio' => $now->format('Y-m-d H:i:s'), ':Fecha_Fin' => null, ':Cantidad_Usuario' => 2, ':Cantidad_Preguntas' => 10);
		$statement = $pdo->prepare('INSERT INTO Partida (Habilitado, Fecha_Inicio, Fecha_Fin, Cantidad_Usuario, Cantidad_Preguntas)
									VALUES (:Habilitado, :Fecha_Inicio, :Fecha_Fin, :Cantidad_Usuario, :Cantidad_Preguntas)');
		$statement->execute($params);
		$idPartida = $pdo->lastInsertId();
		$partida = Partida::ObtenerPorId($idPartida, $pdo);
		$pdo->commit();
		return $partida;
	}
	public static function ObtenerPartidaDisponible($pdo, $ID_Usuario){
		// Agregado el parámetro $ID_Usuario para que no tome como partida válida una iniciada por el mismo usuario.
		$params = array(':ID_Usuario' => $ID_Usuario);
		$statement = $pdo->prepare('SELECT * FROM Partida 
									WHERE Habilitado = 1
									AND ID NOT IN (SELECT ID_Partida FROM Partida_Usuario WHERE ID_Usuario = :ID_Usuario)
									ORDER BY RAND() LIMIT 0,1;');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida');
		return $statement->fetch();
	}
	public static function DeshabilitarPartida($pdo, $ID_Partida)
	{
		$params = array(':ID' => $ID_Partida);
		$statement = $pdo->prepare('UPDATE Partida
									SET Habilitado = 0
									WHERE ID = :ID');
		$statement->execute($params);
		$partida = Partida::ObtenerPorId($ID_Partida, $pdo);
		return $partida;
	}
	public static function CantidadPreguntasSinResponderUsuario($ID_Partida, $ID_Usuario, $pdo)
	{
		$params = array(':ID_Partida' => $ID_Partida, ':ID_Usuario' => $ID_Usuario);
		$statement = $pdo->prepare('SELECT COUNT(*)
										FROM Partida P
										LEFT JOIN Partida_Usuario PU ON P.ID = PU.ID_Partida
										LEFT JOIN Partida_Pregunta PP ON P.ID = PP.ID_Partida
										LEFT JOIN Partida_Respuesta PR ON PU.ID = PR.ID_Partida_Usuario AND PP.ID = PR.ID_Partida_Pregunta
										WHERE (PR.ID IS NULL)
											AND PU.ID_Usuario = :ID_Usuario
											AND P.ID = :ID_Partida');
		$statement->execute($params);
		$cantidad = $statement->fetch(PDO::FETCH_NUM);
		return $cantidad;
	}
	public static function CantidadUsuariosNoFinalizados($ID_Partida, $pdo)
	{
		$params = array(':ID_Partida' => $ID_Partida);
		$statement = $pdo->prepare('SELECT COUNT(*)
										FROM Partida P
										LEFT JOIN Partida_Usuario PU ON P.ID = PU.ID_Partida
										WHERE (PU.Fecha_Fin IS NULL)
											AND P.ID = :ID_Partida');
		$statement->execute($params);
		$cantidad = $statement->fetch(PDO::FETCH_NUM);
		return $cantidad;
	}
	public static function Finalizar($ID_Partida, $pdo)
	{
		$pdo->beginTransaction();
		$now = new DateTime();
		$params = array(':ID' => $ID_Partida, ':Fecha_Fin'=>$now->format('Y-m-d H:i:s'));
		$statement = $pdo->prepare('UPDATE Partida
									SET Fecha_Fin = :Fecha_Fin
									WHERE ID = :ID');
		$statement->execute($params);
		$idPartida = $pdo->lastInsertId();
		$partida = Partida_Usuario::ObtenerPorId($idPartida, $pdo);
		$pdo->commit();
		return $partida;
	}
}
?>