<?php

$path= '/var/www/mapa/ogc';
# Consulta Servidores de los servicios.
$file = file_get_contents("http://servicios.idera.gob.ar/mapa/sources.php?format=json");
$jSources=str_replace("var sources = ","", $file);
$services = json_decode($jSources,true);
$hoy = new DateTime();

$file_db = new PDO('sqlite:estadisticas.sqlite');

// Create table messages
$file_db->exec("CREATE TABLE IF NOT EXISTS estadisticas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                provider TEXT,
                fecha TEXT,
                cant_capas INTEGER)");

// Prepare INSERT statement to SQLite3 file db
$sql_insert = "INSERT INTO estadisticas (provider, fecha, cant_capas)
            VALUES (:provider, ':fecha', :cant_capas)";
$insert= $file_db->prepare($sql_insert);

// Bind parameters to statement variables
$insert->bindParam(':provider', $_provider);
$insert->bindParam(':cant_capas', $_capas);
$insert->bindParam(':fecha', $_fecha);


// Loop thru all messages and execute prepared insert statement
foreach ($services as $provider => $service) {
     if (file_exists("$path/$provider.xml")){
       $source = file_get_contents("$path/$provider.xml");
       $elem = new SimpleXMLElement($source);
       $_capas = $elem->Capability->Layer->Layer->count();
       $_provider = $provider;
       $_fecha = $hoy->format('d/m/yyyy');
       $insert->execute();
     }
}
