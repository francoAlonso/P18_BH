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
	
}
?>