<?php
require 'Slim-2.6.2/Slim/Slim.php';
require 'DatabaseConfig.php';
require 'Database.php';
require 'model/Usuario.php';
require 'controller/UsuarioController.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dbConfig = new DatabaseConfig();
$pdo = new Database("mysql:host=" . $dbConfig->host . ";dbname=" . $dbConfig->dbname, $dbConfig->username/*, $dbConfig->password*/);

$app->get('/usuario/:id', function ($id) use ($pdo){
	$usuario = UsuarioController::ObtenerPorId($id, $pdo);
	echo '{usuarios: ' . json_encode(array($usuario)) . '}';
	echo $usuario->ID;
});
$app->get('/usuario', function () use ($pdo){
	$listaUsuario = UsuarioController::ObtenerTodos($pdo);
	echo json_encode($listaUsuario);
});
$app->post('/usuario/agregar', function() use ($app, $pdo) {
	try 
	{
		$usuarioRecibido = json_decode($app->request->getBody());
		$usuarioCreado = UsuarioController::CrearUsuario($usuarioRecibido->ID_Gerencia, $usuarioRecibido->ID_Sede, $usuarioRecibido->DNI, $usuarioRecibido->Nombre, $usuarioRecibido->Contrasena, $usuarioRecibido->Mail, $usuarioRecibido->Puntaje, $pdo);
		echo json_encode($usuarioCreado);
	}
	catch (Exception $ex)
	{
		echo $ex->getMessage();
	}
});

$app->run();
?>