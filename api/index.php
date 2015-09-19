<?php
require 'Slim-2.6.2/Slim/Slim.php';
require 'DatabaseConfig.php';
require 'Database.php';
require 'model/Usuario.php';
require 'model/Respuesta.php';
require 'model/Pregunta.php';
require 'model/Partida.php';
require 'model/Empresa.php';
require 'model/Gerencia.php';
require 'model/Nivel.php';
require 'model/Partida_Pregunta.php';
require 'model/Partida_Respuesta.php';
require 'model/Partida_Usuario.php';
require 'model/Sede.php';
require 'controller/EmpresaController.php';
require 'controller/GerenciaController.php';
require 'controller/NivelController.php';
require 'controller/Partida_PreguntaController.php';
require 'controller/Partida_RespuestaController.php';
require 'controller/Partida_UsuarioController.php';
require 'controller/PartidaController.php';
require 'controller/UsuarioController.php';
require 'controller/PreguntaController.php';
require 'controller/RespuestaController.php';
require 'controller/SedeController.php';

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

$app->get('/partida/generar/:id', function($id) use ($app, $pdo){
	try{
		$pdo->beginTransaction();
		$partida = PartidaController::CrearPartida($pdo,$id);
		$pdo->commit();
		echo json_encode($partida);
	}catch (Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
		$pdo->rollBack();
	}
});

$app->get('/pregunta/generar', function() use ($app, $pdo){
	try{
		$pregunta = PreguntaController::GenerarPregunta($pdo);
		$respuestas = RespuestaController::ObtenerRespuestas($pregunta->ID, $pdo);
		echo '{Pregunta: ' . json_encode(array($pregunta)) . ', Respuestas: ' . json_encode(array($respuestas)) . '}';
	}catch (Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}

	$array_pregunta = array();
		$i=0;
		while($i<=9){
			$preguntaObtenida = PreguntaController::GenerarPregunta($pdo);
			for($n=0; $n<=sizeof($array_pregunta);$n++){
				if($preguntaObtenida != $array_pregunta[$n]){
					array_push($array_pregunta, json_encode(array($preguntaObtenida)));
					$n = 10;
					$i++;
				}
			}
		}

});
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
$app->post('/usuario/login', function() use ($app, $pdo) {
	try{
		$respuesta = false;
		$datosRecibidos = json_decode($app->request->getBody());
		$usuario = UsuarioController::Logeo($datosRecibidos->Nombre, $datosRecibidos->Contrasena, $datosRecibidos->DNI, $pdo);
		if ($usuario == null){
			$respuesta = false;
		}else{
			$respuesta = true;
		}
		echo ('Validado:' . var_export($respuesta, true));
	}catch (Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});



$app->run();
?>