# monitor-wms
Monitor de servicios WMS que alerta vía mail cuando alguno está caído.

1. Verificar que este instalado php5 o superior1. Instalar drivers para sqlite (Ej: `apt-get install php5-sqlite`)
1. Configurar en `config/config-mail.php` los parátros de phpmailer (host, puerto, smtpSecure, usuario, contraseñsetFrom) y la ruta completa donde estámplementado el servicio 
1. Debe existir el directorio `capabilities/` (donde guarda el doc capabilities de cada servidor)
1. Ejecutar la primera vez el script `monitor.php` mediante linea de comandos
1. Ejecutar la primera vez el script `mails.php` mediante linea de comandos
1. Ubicar archivos de la carpeta `crons/` (ver README)

## Prueba de mail

Para probar si se estan enviando las notificaciones via mail, cambiar la variable $test_mail = true en el archivo monitor.php
Al hacer esto no se comprobará ninguno de los servidores, sólo se envia un mail de prueba.
