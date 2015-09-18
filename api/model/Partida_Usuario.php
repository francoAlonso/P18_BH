<?php
class Partida_Usuario
{
	public $ID;
	public $ID_Partida;
	public $ID_Usuario;
	public $Fecha_Inicio;
	public $Fecha_Fin;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM Partida_Usuario
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Usuario');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM Partida_Usuario
				WHERE ID = :ID
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Partida_Usuario');
		return $statement->fetch();
	}

	public static function AgregarUsuarioAPartida($ID_Partida, $ID_Usuario, $pdo){
		$pdo->beginTransaction();
		$now = new DateTime(); // Fecha de cuando el usuario inicia la partida, no la fecha de inicio de la partida
		// Las variables $ID_Partida y $ID_Usuario no son los objetos de partida o de usuario. Son solamente los id (variables numéricas),
		$params = array(':ID_Partida' => $ID_Partida, ':ID_Usuario'=> $ID_Usuario, ':Fecha_Inicio'=> $now->format('Y-m-d H:i:s'), ':Fecha_Fin'=>null);
		$statement = $pdo->prepare('INSERT INTO Partida_Usuario(ID_Partida, ID_Usuario, Fecha_Inicio, Fecha_Fin)
									VALUES (:ID_Partida, :ID_Usuario, :Fecha_Inicio, :Fecha_Fin)');
		$statement->execute($params);
		$idPartidaUsuario = $pdo->lastInsertId();
		$partidaUsuario = Partida_Usuario::ObtenerPorId($idPartidaUsuario, $pdo);
		$pdo->commit();
		return $partidaUsuario;
	}
	
}
?>