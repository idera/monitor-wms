<?php

header('Content-type: text/csv');
header('Content-disposition: attachment;filename=estadisticas.csv');
$file_db = new PDO('sqlite:estadisticas.sqlite');
  $sql = "SELECT * FROM estadisticas ";
   echo "NODO" ;
   echo "," ;
   echo "FECHA" ;
   echo "," ;
   echo "CANTIDAD" ;
   echo "\n" ;
  foreach ($file_db->query($sql) as $row) {
        echo $row['provider'] ;
        echo ',' ;
        echo $row['fecha'] ;
        echo ',' ;
        echo $row['cant_capas'] ;
        echo "\n" ;
       }
?>
