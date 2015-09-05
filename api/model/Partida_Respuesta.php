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
				FROM Fecha_Respuesta
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Fecha_Respuesta');
		return $statement->fetchAll(); // fetch trae uno sólo (o debe iterarse). fetchAll trae todos los registros.
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
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Fecha_Respuesta');
		return $statement->fetch();
	}
	
}
?>