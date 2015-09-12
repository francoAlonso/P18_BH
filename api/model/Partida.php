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
		return $usuario;
	}
	public static function ObtenerPartidaDisponible($ID_Usuario, $pdo){
		
	}
}
?>