<?php
require 'DatabaseConfig.php';
require 'Database.php';
require 'model/Usuario.php';
require 'controller/UsuarioController.php';
$dbConfig = new DatabaseConfig();
$pdo = new Database("mysql:host=" . $dbConfig->host . ";dbname=" . $dbConfig->dbname . ";charset=utf8", $dbConfig->username, $dbConfig->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

$codigoVerificador = urldecode($_GET["token"]);
UsuarioController::ConfirmarCambioContrasena($codigoVerificador, $pdo);
header("Location: http://p18bh.ml/");
die;
?>