<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 # Configuracion para envio de mails, para pruebas puede utilizarse Gmail
require 'PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->Host = 'mail.ign.gob.ar';
$mail->Port = 25;
//$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "admin@idera.gob.ar";
$mail->Password = "xxxx";
$mail->setFrom('admin@idera.gob.ar', 'IDERA');
$mail->Subject = 'Servidor Inaccesible';


#
$path= './capabilities';

$aContext = array(
    'http' => array(
        'proxy' => '172.20.203.111:3128',
        'request_fulluri' => true,
    ),
);
$cxContext = stream_context_create($aContext);
# Consulta Servidores de los servicios.
$file = file_get_contents("http://servicios.idera.gob.ar/geoservicios/sources.json", 
			  False, $cxContext);
$services = json_decode($file, true);

$file_db = new PDO('sqlite:emails.sqlite');

$hoy = new DateTime();

// Prepare UPDATE statement to SQLite3 file db
$sql_update = "UPDATE emails SET fecha_envio = :fecha where provider = :provider";
$update = $file_db->prepare($sql_update);

// Bind parameters to statement variables
$update->bindParam(':provider', $_provider);
$update->bindParam(':fecha', $_fecha);



#Borra xml cacheados anteriormente
exec("rm $path/*.xml");

# Recorre los servidores y obtiene capacidades de cada servicio.
if ($services){
  foreach ($services as $service) {
    foreach ($service as $nodo)
    if (isset($nodo["wms"])) {

        exec("wget --tries=2 --timeout=60 -O $path/$nodo[id].xml "  . $nodo["wms"] . 
	     "'&service=WMS&version=1.1.1&request=GetCapabilities'");

        if (0 == filesize("$path/$nodo[id].xml")) {

            $_provider = $nodo['id'];
            $_url = $nodo["wms"];
            $sql_consulta = "SELECT * FROM emails where provider = '$_provider'";
            $consulta = $file_db->prepare($sql_consulta);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            if (!empty($resultado)) { //si tiene email entra
            	echo "Existe el email \n";
                $fecha_envio = $resultado["fecha_envio"];
                $ultimo_envio = DateTime::createFromFormat("Y-m-d H:i:s", $fecha_envio);

                $intervalo = $hoy->diff($ultimo_envio);
                $diferencia_dias = $intervalo->format("%R%a");
                if (abs($diferencia_dias) > 7) {

                    $para = $resultado["email"];
                    echo "Intento enviar email \n";
                    $mail->clearAddresses();
                    $mail->addAddress($para);
                    $mail->msgHTML("Informamos que su servicio " . $_url . 
				   		" se encuentra inaccesible. Por favor no responda este mensaje");
                    if (!$mail->send()) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    } else {
                        echo "envio un email porque el servidor de $_provider esta caído \n";
                        $_fecha = $hoy->format("Y-m-d H:i:s");
                        $update->execute();
                    }
                }
            }else{
		echo "No existe el mail \n";
	}
        }
    }
}
} else {
  $para = "admin@idera.gob.ar";
  echo "Intento enviar email \n";
  $mail->clearAddresses();
  $mail->addAddress($para);
  $mail->msgHTML("informamos que el script sources.php no devuelve datos. Por favor no responda este mensaje");
  if (!$mail->send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
      echo "envio un email porque el script sources.php no devuelve datos\n";
  }
}
