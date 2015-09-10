<?php
class Respuesta
{
	public $ID;
	public $ID_Pregunta;
	public $Texto;
	private $Correcta;
	public $Habilitado;
	
	public function EsCorrecta()
	{
		return $this->Correcta;
	}
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM Respuesta
				WHERE Habilitado = 1
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Respuesta');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM Respuesta
				WHERE ID = :ID
				AND Habilitado = 1
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Respuesta');
		return $statement->fetch();
	}
	//____________________________
	public static function ObtenerRespuestas($ID_Pregunta,$pdo){
		$params = array(':Pregunta_ID'=>$ID_Pregunta);
		$statement = $pdo->prepare('SELECT Texto FROM Respuesta
			WHERE ID_Pregunta = :Pregunta_ID 
			AND Habilitado = 1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Respuesta');
		return $statement->fetchAll();
	}
	//____________________________
}
?>