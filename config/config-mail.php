<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$ROOT_PATH='/var/www/monitor-wms/';
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
$mail->Password = "info*00";
$mail->setFrom('admin@idera.gob.ar', 'IDERA');
$mail->Subject = 'Servidor Inaccesible';


