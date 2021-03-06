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
require 'model/Puntaje_Partida_Usuario.php';
require 'model/Puntaje_Gerencia.php';
require 'model/Sede.php';
require 'model/Perfil_Usuario.php';
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

date_default_timezone_set('America/Argentina/Buenos_Aires');

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$dbConfig = new DatabaseConfig();
$pdo = new Database("mysql:host=" . $dbConfig->host . ";dbname=" . $dbConfig->dbname . ";charset=utf8", $dbConfig->username, $dbConfig->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

$app->get('/partida/generar/:id', function($id) use ($app, $pdo){
	try{
		$pdo->beginTransaction();
		$partida = PartidaController::CrearPartida($pdo,$id);
		$pdo->commit();
		echo json_encode($partida, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}catch (Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
		echo $ex->getTraceAsString();
		$pdo->rollBack();
	}
});

$app->get('/pregunta/responder/:id', function ($id) use ($app, $pdo){
	try{
		$respuesta = Partida_RespuestaController::VerificarRespuesta($id, $pdo);
		echo json_encode($respuesta);
		$generarPartida_Respuesta = json_decode($app->request->getBody());
		$Partida_Respuesta = Partida_RespuestaController::AgregarPartidaRespuesta($generarPartida_Respuesta->ID_Respuesta, 
																				$generarPartida_Respuesta->ID_Partida_Pregunta,
																				$generarPartida_Respuesta->ID_Partida_Usuario, $pdo);
		echo json_encode($Partida_Respuesta, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}catch(Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});

$app->post('/partida/responder', function () use ($app, $pdo){
	try
	{
		$pdo->beginTransaction();
		$recibido = json_decode($app->request->getBody());
		$ID_Usuario = $recibido->ID_Usuario;
		$ID_Partida = $recibido->ID_Partida;
		$ID_Pregunta = $recibido->ID_Pregunta;
		$ID_Respuesta = $recibido->ID_Respuesta;
		
		$resultado = PartidaController::ResponderPregunta($pdo, $ID_Usuario, $ID_Partida, $ID_Pregunta, $ID_Respuesta);		
		
		$pdo->commit();
		echo json_encode(array("Resultado" => $resultado), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
		echo $ex->getTraceAsString();
		$pdo->rollBack();
	}
});
$app->get('/pregunta/generar', function() use ($app, $pdo){
	try{
		$pregunta = PreguntaController::GenerarPregunta($pdo);
		$respuestas = RespuestaController::ObtenerRespuestas($pregunta->ID, $pdo);
		echo '{Pregunta: ' . json_encode(array($pregunta), JSON_UNESCAPED_UNICODE) . ', Respuestas: ' . json_encode(array($respuestas), JSON_UNESCAPED_UNICODE) . '}';
	}catch (Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->get('/pregunta/:id',function($id) use ($app, $pdo){
	try{
		$_pregunta = Pregunta::ObtenerPorId($id,$pdo);
		$_respuestas = Respuesta::ObtenerRespuestas($id, $pdo);
		if ($_pregunta == null || $_respuestas == null)
			throw new Exception("ERROR, no se encontro la pregunta o las respuestas");
		else
			$response = json_encode(array("Pregunta" => $_pregunta, "Respuestas" => $_respuestas), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
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
		echo '{usuarios: ' . json_encode(array($usuario), JSON_UNESCAPED_UNICODE) . '}';
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
		echo json_encode($listaUsuario, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
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
		echo json_encode($usuarioCreado, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->post('/usuario/login', function() use ($app, $pdo) {
	try{
		$usuario = null;
		$respuesta = false;
		$datosRecibidos = json_decode($app->request->getBody());
		$usuario = UsuarioController::Logeo($datosRecibidos->Nombre, $datosRecibidos->Contrasena, $pdo);
		if ($usuario == null){
			$respuesta = false;
		}else{
			$respuesta = true;
		}
		
		echo json_encode(array("Validado" => $respuesta, "Usuario" => $usuario), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->post('/usuario/cambiarContrasena', function () use ($app, $pdo)
{
	try{
		$usuario = null;
		$respuesta = false;
		$datosRecibidos = json_decode($app->request->getBody());
		$return = UsuarioController::CambiarContrasena($datosRecibidos->ID_Usuario, $datosRecibidos->ContrasenaActual, $datosRecibidos->ContrasenaNueva, $pdo);
	
		echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->get('/gerencias/puntajes', function () use ($app, $pdo)
{
	try
	{
		$gerencias = Puntaje_Gerencia::ObtenerTodos($pdo);
		echo json_encode(array('Gerencias' => $gerencias), JSON_UNESCAPED_UNICODE);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->get('/perfil/:id', function ($id) use ($app, $pdo){
	try{
		$array = Perfil_Usuario::ObtenerPorUsuario($id, $pdo);
		echo json_encode($array, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});
$app->get('/partida/sinFinalizar/:id', function ($id) use ($app, $pdo)
{
	$var = Partida::CantidadUsuariosFinalizados($id, $pdo);
	var_dump($var);
	var_dump(intval($var[0]));
});
$app->get('/partida/puntajes/:id', function ($id) use ($app, $pdo)
{
	$var = Puntaje_Partida_Usuario::ObtenerPorPartida($id, $pdo);
	var_dump($var);
});
$app->run();
?>