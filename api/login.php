<html>
	<head>
	</head>
	<body>
		<?php
		require 'Slim-2.6.2/Slim/Slim.php';
		require 'DatabaseConfig.php';
		require 'Database.php';
		require 'controller/UsuarioController.php';
		require 'model/Usuario.php';

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
		?>

		<form method="post">
            <input type="text" name="nombre">
            <br>
            <input type="text" name="contrasenia">
            <br>
            <input type="submit" name="conexion" value="Conectar">
        </form>

        <?php
			\Slim\Slim::registerAutoloader();

			$app = new \Slim\Slim();

			$dbConfig = new DatabaseConfig();
			$pdo = new Database("mysql:host=" . $dbConfig->host . ";dbname=" . $dbConfig->dbname, $dbConfig->username/*, $dbConfig->password*/);

			if(isset ($_POST['conexion'])){	
              //  if(logeo()==true){
              	try{
            		$usuario = UsuarioController::Logeo($_POST[nombre],$_POST[contrasenia],$pdo);
            		if ($usuario == null){
            			throw new Exception("No existe tal usuario");
            		}else{
            			echo ("logueado");
            		}
            	}catch (Exception $ex){
            		$app->response->setStatus(500);
					echo $ex->getMessage();
            	}
               //     session_start();
                    //$comando = mysql_query("SELECT * FROM usuarios WHERE nombre = '$_POST[nombre]'") or die ("Problema al cargar comando");
                   // $reg=mysql_fetch_array($comando);
              //      $_SESSION['id'] = $reg['id'];
              //      $_SESSION['clase'] = $reg['clase'];
              //      header("location:index2.php");
              //  }else{
              //      echo "Usuario o contraseña incorrectos";   
              //  }
            }

           /* function logeo(){
                $comando = "SELECT * FROM usuarios WHERE nombre='$_POST[nombre]' and contrasenia='$_POST[contrasenia]'";
                $rec = mysql_query($comando) or die ("problema en seleccion");
      
                $count = 0;
                while($row=mysql_fetch_row($rec)){
                    $count++;
                }

                if($count==1){
                    return true;
                }else{
                    return false;   
                }
            } 
			*/
        ?>

	</body>
</html>