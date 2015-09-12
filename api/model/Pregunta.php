<?php
class Pregunta
{
	public $ID;
	public $ID_Nivel;
	public $Texto;
	public $Habilitado;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM Pregunta
				WHERE Habilitado = 1
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Pregunta');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM Pregunta
				WHERE ID = :ID
				AND Habilitado = 1
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Pregunta');
		return $statement->fetch();
	}
	
	public static function ObtenerPreguntaRandom($pdo)
	{
		$statement = $pdo->prepare('SELECT * FROM Pregunta ORDER BY RAND() LIMIT 0,1;');
		$statement->execute();
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Pregunta');
		return $statement->fetch();
	}
}
?>