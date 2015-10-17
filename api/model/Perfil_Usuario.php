<?php
class Perfil_Usuario
{
	public $Nombre_Usuario;
	public $Puntaje_Usuario;
	public $Nombre_Gerencia;
	public $Puntaje_Gerencia;
	
	public static function ObtenerPorUsuario($idUsuario, $pdo)
	{
		$params = array(':ID_Usuario' => $idUsuario);
		$statement = $pdo->prepare('
					SELECT U.Nombre_Completo AS Nombre_Usuario
					    , ((U.Puntaje/G.Puntaje_Bruto)*G.Puntaje) AS Puntaje_Usuario
					    , G.Nombre AS Nombre_Gerencia
					    , G.Puntaje AS Puntaje_Gerencia
					FROM usuario U
					LEFT JOIN (SELECT G.ID
							, G.Nombre
							, ((100-(COUNT(U.ID) / (SELECT COUNT(ID) FROM usuario)*100)) * SUM(U.Puntaje)) AS Puntaje
					        , SUM(U.Puntaje) AS Puntaje_Bruto
						FROM gerencia G
						LEFT JOIN usuario U ON G.ID = U.ID_Gerencia
						GROUP BY G.ID, G.Nombre) G ON U.ID_Gerencia = G.ID
					WHERE U.ID = :ID_Usuario
					GROUP BY U.ID, U.Nombre, G.ID, G.Nombre
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Perfil_Usuario');
		return $statement->fetch();
	}
}
?>