<?php
class Puntaje_Gerencia
{
	public $ID;
	public $Nombre;
	public $Puntaje;

	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT G.ID, G.Nombre, SUM(COALESCE(U.Puntaje, 0)) AS Puntaje
				FROM Gerencia G
				LEFT JOIN Usuario U ON G.ID = U.ID_Gerencia
				GROUP BY G.ID, G.Nombre
				ORDER BY Puntaje DESC
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Puntaje_Gerencia');
		return $statement->fetchAll();
	}
}
?>