<html>
  <head>
    <title>IDERA - Reporte</title>
  </head>
  <body>
    <div>
      <h1>REPORTE DE CANTIDAD DE CAPAS HISTORICO</h1>
    </div>
    <div>
      <button onclick="window.open('exportCSV.php')">DESCARGAR CSV</button>
    </div>
    <div>
      <table>
        <tr>
          <td>
            NODO
          </td>
          <td>
            FECHA
          </td>
          <td>
            CANTIDAD
          </td>
        </tr>
<?php
try {
  $file_db = new PDO('sqlite:estadisticas.sqlite');
  $sql = "SELECT * FROM estadisticas ";
  foreach ($file_db->query($sql) as $row) {
   ?>
   <tr>
        <td><?php
        echo $row['provider'] . "\t";?></td>
        <td><?php
        echo $row['fecha'] . "\t";?></td><td><?php
        echo $row['cant_capas'] . "\n";
       ?></td></tr><?php
       }
}
catch (Exception $e) {
  echo 'Caught exception: ',  $e->getMessage(), "\n";
  }
?>
      </table>
    </div>
  </body>
</html>
