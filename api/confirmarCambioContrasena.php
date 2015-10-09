<?php
require 'DatabaseConfig.php';
require 'Database.php';
require 'model/Usuario.php';
require 'controller/UsuarioController.php';
$dbConfig = new DatabaseConfig();
$pdo = new Database("mysql:host=" . $dbConfig->host . ";dbname=" . $dbConfig->dbname, $dbConfig->username/*, $dbConfig->password*/);

$codigoVerificador = urldecode($_GET["token"]);
UsuarioController::ConfirmarCambioContrasena($codigoVerificador, $pdo);
header("Location: http://localhost:8082/index.php");
die;
?>