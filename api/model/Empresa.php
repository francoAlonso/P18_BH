<?php
class Empresa
{
	public $ID;
	public $Nombre;
	public $Habilitado;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM empresa
				WHERE Habilitado = 1
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Empresa');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM empresa
				WHERE ID = :ID
				AND Habilitado = 1
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Empresa');
		return $statement->fetch();
	}
}
?>