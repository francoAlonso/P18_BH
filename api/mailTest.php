<?php
/*include ('Mail-1.2.0/Mail.php');
//Envio mail
$recipients = 'leiboleo@gmail.com';
$headers['From']    = 'p18bh';
$headers['To']      = 'leiboleo@gmail.com';
$headers['Subject'] = 'Cambio de contraseña - Buho Trivia';
	
$body = 'Para aceptar el cambio de su contraseña, ingrese a la siguiente página web: http://p18bh.ml/api/confirmarCambioContrasena?token=';
	
$smtpinfo["host"] = "smtp-relay.gmail.com"; //Server de SMTP (x ejemplo hotmail)
$smtpinfo["port"] = "25";
$smtpinfo["auth"] = true;
$smtpinfo["username"] = "puerta18bh@gmail.com"; // Cuenta de mail
$smtpinfo["password"] = "Puerta18"; // Contraseña del mail
	
// Create the mail object using the Mail::factory method
$mail_object =& Mail::factory("smtp", $smtpinfo);

$mail_object =& Mail::factory("sendmail", array("sendmail_path" => "/usr/sbin/sendmail", "sendmail_args" => "-t -i"));
	
echo $mail_object->send($recipients, $headers, $body);

echo mail("leiboleo@gmail.com", "Test", "test", "From: FirstName LastName <SomeEmailAddress@Domain.com>");

$mbox = imap_open("{imap.gmail.com:993/imap/ssl/novalidate-cert}", "puerta18bh@gmail.com", "Puerta18");
if ($mbox=imap_open( $authhost, $user, $pass ))
{
	echo "<h1>Connected</h1>\n";
	imap_close($mbox);
} else
{
	echo "<h1>FAIL!</h1>\n";
}*/
	//require_once('PHPMailer-master/PHPMailerAutoload.php');
	/*$mail = new PHPMailer();
	//indico a la clase que use SMTP
	$mail­>IsSMTP();
	//permite modo debug para ver mensajes de las cosas que van ocurriendo
	$mail->SMTPDebug = 2;
	//Debo de hacer autenticación SMTP
	$mail­>SMTPAuth = true;
	$mail­>SMTPSecure = "ssl";
	//indico el servidor de Gmail para SMTP
	$mail­>Host = "smtp.gmail.com";
	//indico el puerto que usa Gmail
	$mail­>Port = 465;
	//indico un usuario / clave de un usuario de gmail
	$mail­>Username = "puerta18bh@gmail.com";
	$mail­>Password = "Puerta18";
	$mail­>SetFrom('puerta18bh@gmail.com', 'Puerta18BH');
	//$mail­>AddReplyTo("tu_correo_electronico_gmail@gmail.com","Nombre completo");
	$mail­>Subject = "Envío de email usando SMTP de Gmail";
	$mail­>MsgHTML("Hola que tal, esto es el cuerpo del mensaje!");
	//indico destinatario
	$address = "leiboleo@gmail.com";
	$mail­>AddAddress($address, "Nombre completo");
	if(!$mail­>Send()) {
		echo "Error al enviar: " . $mail­>ErrorInfo;
	} else {
		echo "Mensaje enviado!";
	}
	*/

	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	date_default_timezone_set('Etc/UTC');
	
	require 'PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 2;
	
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	
	//Set the hostname of the mail server
	$mail->Host = gethostbyname('smtp.gmail.com');
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6
	
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "puerta18bh@gmail.com";
	
	//Password to use for SMTP authentication
	$mail->Password = "Puerta18";
	
	//Set who the message is to be sent from
	$mail->setFrom('puerta18bh@gmail.com', 'Puerta 18');
	
	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	
	//Set who the message is to be sent to
	$mail->addAddress('leiboleo@gmail.com', 'John Doe');
	
	//Set the subject line
	$mail->Subject = 'PHPMailer GMail SMTP test';
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	$mail->msgHTML("Mail de puerba");
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
?>