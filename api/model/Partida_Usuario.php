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
		return $statement->fetchAll(); // fetch trae uno sólo (o debe iterarse). fetchAll trae todos los registros.
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
		$params = array(':ID_Partida' => $ID_Partida->ID, ':ID_Usuario'=> $ID_Usuario->ID, ':Fecha_Inicio'=> $ID_Partida->Fecha_Inicio, ':Fecha_Fin'=>null);
		$statement = $pdo->prepare('INSERT INTO Partida_Usuario(ID_Partida, ID_Usuario, Fecha_Inicio, Fecha_Fin)
									VALUES :ID_Partida, :ID_Usuario, :Fecha_Inicio, :Fecha_Fin');
		$statement->execute($params);
		$pdo->commit();
	}
	
}
?>