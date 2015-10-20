<?php
class Puntaje_Partida_Usuario
{
	public $ID_Partida;
	public $ID_Usuario;
	public $Puntaje_Partida;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT P.ID AS ID_Partida
					, U.ID AS ID_Usuario
					, SUM( DISTINCT CASE WHEN R.Correcta =1 THEN COALESCE( N.Puntaje, 0 ) ELSE 0 END ) AS Puntaje_Partida
				FROM partida P
				LEFT JOIN partida_usuario PU ON P.ID = PU.ID_Partida
				LEFT JOIN usuario U ON PU.ID_Usuario = U.ID
				LEFT JOIN partida_respuesta PR ON PU.ID = PR.ID_Partida_Usuario
				LEFT JOIN respuesta R ON PR.ID_Respuesta = R.ID
				LEFT JOIN partida_pregunta PP ON P.ID = PP.ID_Partida AND PR.ID_Partida_Pregunta = PP.ID
				LEFT JOIN pregunta Preg ON PP.ID_Pregunta = Preg.ID
				LEFT JOIN nivel N ON Preg.ID_Nivel = N.ID
				WHERE (P.Fecha_Fin IS NOT NULL)
				GROUP BY P.ID, U.ID
				ORDER BY Puntaje_Partida DESC 
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Puntaje_Partida_Usuario');
		return $statement->fetchAll();
	}
	
	public static function ObtenerPorPartida($ID_Partida, $pdo)
	{
		$params = array(':ID_Partida' => $ID_Partida);
		$statement = $pdo->prepare('
				SELECT P.ID AS ID_Partida
					, U.ID AS ID_Usuario
					, SUM( DISTINCT CASE WHEN R.Correcta =1 THEN COALESCE( N.Puntaje, 0 ) ELSE 0 END ) AS Puntaje_Partida
				FROM partida P
				LEFT JOIN partida_usuario PU ON P.ID = PU.ID_Partida
				LEFT JOIN usuario U ON PU.ID_Usuario = U.ID
				LEFT JOIN partida_respuesta PR ON PU.ID = PR.ID_Partida_Usuario
				LEFT JOIN respuesta R ON PR.ID_Respuesta = R.ID
				LEFT JOIN partida_pregunta PP ON P.ID = PP.ID_Partida AND PR.ID_Partida_Pregunta = PP.ID
				LEFT JOIN pregunta Preg ON PP.ID_Pregunta = Preg.ID
				LEFT JOIN nivel N ON Preg.ID_Nivel = N.ID
				WHERE (P.Fecha_Fin IS NOT NULL)
					AND P.ID = :ID_Partida
				GROUP BY P.ID, U.ID
				ORDER BY Puntaje_Partida DESC 
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Puntaje_Partida_Usuario');
		return $statement->fetchAll();
	}
}
?>