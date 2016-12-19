# monitor-wms
Monitor de servicios WMS que alerta vía mail cuando alguno está caído.


1. Instalar drivers para sqlite (Ej: apt-get install php5-sqlite)
1. Configurar en extrenal/monitor.php los parámetros de phpmailer (host, puerto, usuario, contraseña)
1. Debe existir el directorio /var/www/mapa/ogc (donde guarda el doc capabilities de cada servidor)
1. Ejecutar la primera vez el script monitor.php mediante linea de comandos
1. Ejecutar la primera vez el script mails.php mediante linea de comandos
