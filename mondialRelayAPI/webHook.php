<?php
    require_once dirname(__DIR__) . '/private/config.php';

    
function check_email() {
    
    // Conectarse al servidor IMAP
    $inbox = imap_open(IMAP_SERVER, IMAP_EMAIL, IMAP_PASS) or die('No se pudo conectar: ' . imap_last_error());
  
    // Buscar correos electrónicos nuevos
    $emails = imap_search($inbox, 'UNSEEN');

    wp_mail( 'pedrogarciaromera970@gmail.com', 'IMAP CONECTADO', 'Automatic scheduled email from WordPress to test cron');
  
    if ($emails) {
      foreach ($emails as $email_number) {
        // Obtener una vista previa del correo electrónico
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $subject = $overview[0]->subject;
        $from = $overview[0]->from;
        $message = imap_fetchbody($inbox, $email_number, 1);
  
        // Llamar a una función específica para manejar el correo electrónico
        //handle_new_email($subject, $from, $message);
      }
    }
  
    // Cerrar la conexión IMAP
    imap_close($inbox);
  }
  
  // Función para manejar un correo electrónico nuevo
  function handle_new_email($subject, $from, $message) {
   if($from == 'noreply@mondialrelay.com' || $from == 'mondialrelay@notifications.com'){

   }
  }