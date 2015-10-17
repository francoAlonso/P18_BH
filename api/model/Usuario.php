<?php

class Usuario
{
	/*
	 * Las propiedades privadas son para evitar mostrarlas por error (por ejemplo haciendo un 'echo').
	 * De esta forma evitamos pasar la contrase�a (m�s all� de que vaya a estar encriptada) a frontend.
	 * Para poder acceder a �stos datos vamos a necesitar funciones especiales (para modificarlos o leerlos).
	 * Es importante que las propiedades se llamen igual a como est�n en la base de datos.
	 */
	
	public $ID;
	public $ID_Gerencia;
	public $ID_Sede;
	public $DNI;
	public $Nombre;
	private $Contrasena;
	private $Mail;
	public $Puntaje;
	private $Habilitado;
	private $ContrasenaNueva;
	private $CodigoVerificacion;
	
	// Funciones para acceder a propiedades privadas
	public function ObtenerContrasena()
	{
		return $this->Contrasena;
	}
	public function ObtenerMail()
	{
		return $this->Mail;
	}
	public function ObtenerHabilitado()
	{
		return $this->Habilitado;
	}
	public function ObtenerContrasenaNueva()
	{
		return $this->ContrasenaNueva;
	}
	public function ObtenerCodigoVerificacion()
	{
		return $this->CodigoVerificacion;
	}
	public function SetearContrasena($valor)
	{
		$this->Contrasena = $valor;
	}
	public function SetearContrasenaNueva($valor)
	{
		$this->ContrasenaNueva = $valor;
	}
	public function SetearCodigoVerificacion($valor)
	{
		$this->CodigoVerificacion = $valor;
	}

	// Funciones para acceso a base de datos
	public static function ObtenerTodos($pdo)
	{
		$params = array();
		$statement = $pdo->prepare('
				SELECT *
				FROM usuario
				WHERE Habilitado = 1
				');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}
	public static function ObtenerPorId($id, $pdo)
	{
		$params = array(':ID' => $id);
		$statement = $pdo->prepare('
				SELECT *
				FROM usuario
				WHERE ID = :ID
				AND Habilitado = 1
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}
	public static function CrearUsuario($idGerencia, $idSede, $dni, $nombre, $contrasena, $mail, $puntaje, $pdo)
	{
		$pdo->beginTransaction();
		$params = array(':ID_Gerencia' => $idGerencia, ':ID_Sede' => $idSede, ':DNI' => $dni, ':Nombre' => $nombre,
						':Contrasena' => $contrasena, ':Mail' => $mail, ':Puntaje' => $puntaje, ':Habilitado' => true,
						':ContrasenaNueva' => null, ':CodigoVerificacion' => null);
		$statement = $pdo->prepare('
				INSERT INTO usuario (ID_Gerencia, ID_Sede, DNI, Nombre, Contrasena, Mail, Puntaje, Habilitado, ContrasenaNueva, CodigoVerificacion)
				VALUES (:ID_Gerencia, :ID_Sede, :DNI, :Nombre, :Contrasena, :Mail, :Puntaje, :Habilitado, :ContrasenaNueva, :CodigoVerificacion)
				');
		$statement->execute($params);
		$idUsuario = $pdo->lastInsertId();
		$usuario = Usuario::ObtenerPorId($idUsuario, $pdo);
		$pdo->commit();
		return $usuario;
	}

	public static function Login($nombre, $contrasena, $pdo){
		$params = array(':Nombre' => $nombre, ':Contrasena' => $contrasena);
		$statement = $pdo->prepare('
				SELECT *
				FROM usuario
				WHERE Nombre = :Nombre
				AND Contrasena = :Contrasena
				AND Habilitado = 1
				LIMIT 0,1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}
	
	public static function ActualizarPuntaje($ID_Usuario, $puntaje, $pdo)
	{
		$pdo->beginTransaction();
		$params = array(':ID_Usuario' => $ID_Usuario, ':Puntaje' => $puntaje);
		$statement = $pdo->prepare('
				UPDATE usuario
				SET Puntaje = :Puntaje
				WHERE ID = :ID_Usuario
				');
		$statement->execute($params);
		$usuario = Usuario::ObtenerPorId($ID_Usuario, $pdo);
		$pdo->commit();
		return $usuario;
	}
	
	public static function ActualizarContrasena($idUsuario, $contrasenaNueva, $pdo)
	{
		$pdo->beginTransaction();
		$params = array(':ID' => $idUsuario, ':ContrasenaNueva' => $contrasenaNueva);
		$statement = $pdo->prepare('
				UPDATE usuario
				SET ContrasenaNueva = :ContrasenaNueva, CodigoVerificacion = UUID()
				WHERE ID = :ID');
		$statement->execute($params);
		$usuario = Usuario::ObtenerPorId($idUsuario, $pdo);
		$pdo->commit();
		return $usuario;
	}
	
	public static function ConfirmarCambioContrasena($codigoVerificador, $pdo)
	{
		$pdo->beginTransaction();
		$params = array(':CodigoVerificador' => $codigoVerificador);
		$statement = $pdo->prepare('
				UPDATE usuario
				SET Contrasena = ContrasenaNueva,
					ContrasenaNueva = NULL,
					CodigoVerificacion = NULL
				WHERE CodigoVerificacion = :CodigoVerificador');
		$statement->execute($params);
		$pdo->commit();
	}
}

?>