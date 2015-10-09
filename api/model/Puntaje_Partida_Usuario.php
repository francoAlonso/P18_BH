<?php
class Puntaje_Partida_Usuario
{
	public $ID_Partida;
	public $ID_Usuario;
	public $ID_Gerencia;
	public $Puntaje_Partida;
	
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT
					P.ID AS ID_Partida
					, U.ID AS ID_Usuario
					, SUM(COALESCE(N.Puntaje, 0)) AS Puntaje_Partida
				FROM Partida P
				LEFT JOIN Partida_Usuario PU ON P.ID = PU.ID_Partida
				LEFT JOIN Usuario U ON PU.ID_Usuario = U.ID
				LEFT JOIN Gerencia G ON U.ID_Gerencia = G.ID
				LEFT JOIN Partida_Respuesta PR ON PU.ID = PR.ID_Partida_Usuario
				LEFT JOIN Respuesta R ON PR.ID_Respuesta = R.ID 
				LEFT JOIN Partida_Pregunta PP ON P.ID = PP.ID_Partida
				LEFT JOIN Pregunta Preg ON PP.ID_Pregunta = Preg.ID
				LEFT JOIN Nivel N ON Preg.ID_Nivel = N.ID
				WHERE (P.Fecha_Fin IS NULL)
					AND (R.Correcta = 1)
				GROUP BY
					P.ID, U.ID, G.Nombre
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
				SELECT
					P.ID AS ID_Partida
					, U.ID AS ID_Usuario
					, SUM(COALESCE(N.Puntaje, 0)) AS Puntaje_Partida
				FROM Partida P
				LEFT JOIN Partida_Usuario PU ON P.ID = PU.ID_Partida
				LEFT JOIN Usuario U ON PU.ID_Usuario = U.ID
				LEFT JOIN Partida_Respuesta PR ON PU.ID = PR.ID_Partida_Usuario
				LEFT JOIN Respuesta R ON PR.ID_Respuesta = R.ID
				LEFT JOIN Partida_Pregunta PP ON P.ID = PP.ID_Partida
				LEFT JOIN Pregunta Preg ON PP.ID_Pregunta = Preg.ID
				LEFT JOIN Nivel N ON Preg.ID_Nivel = N.ID
				WHERE (P.Fecha_Fin IS NULL)
					AND (R.Correcta = 1)
					AND P.ID = :ID_Partida
				GROUP BY
					P.ID, U.ID
				ORDER BY Puntaje_Partida DESC
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Puntaje_Partida_Usuario');
		return $statement->fetchAll();
	}
}
?>