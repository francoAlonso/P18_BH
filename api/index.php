<?php
require 'Slim-2.6.2/Slim/Slim.php';
require 'DatabaseConfig.php';
require 'Database.php';
require 'model/Usuario.php';
require 'model/Respuesta.php';
require 'model/Pregunta.php';
require 'controller/UsuarioController.php';

// Permite el acceso desde otros dominios (CORS) - INICIO
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
// Permite el acceso desde otros dominios (CORS) - FIN

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$dbConfig = new DatabaseConfig();
$pdo = new Database("mysql:host=" . $dbConfig->host . ";dbname=" . $dbConfig->dbname, $dbConfig->username/*, $dbConfig->password*/);

$app->get('/pregunta/:id',function($id) use ($app, $pdo){
	try{
	    $_pregunta = Pregunta::ObtenerPorId($id,$pdo);
		$_respuestas = Respuesta::ObtenerRespuestas($id, $pdo);
		if ($_pregunta == null || $_respuestas == null)
			throw new Exception("ERROR, no se encontro la pregunta o las respuestas");
		else
			$response = json_encode(array("Pregunta" => $_pregunta, "Respuestas" => $_respuestas));
		echo $response;
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});

$app->get('/usuario/:id', function ($id) use ($app, $pdo){
	try{
		$usuario = UsuarioController::ObtenerPorId($id, $pdo);
		echo '{usuarios: ' . json_encode(array($usuario)) . '}';
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->get('/usuario', function () use ($app, $pdo){
	try{
		$listaUsuario = UsuarioController::ObtenerTodos($pdo);
		echo json_encode($listaUsuario);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
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
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});

$app->run();
?>