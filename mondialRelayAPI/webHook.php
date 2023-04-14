<?php
    require_once dirname(__DIR__) . '/private/config.php';

    
function check_email() {
    
    // Conectarse al servidor IMAP
    $inbox = imap_open(IMAP_SERVER, IMAP_EMAIL, IMAP_PASS) or die('No se pudo conectar: ' . imap_last_error());
  
    // Buscar correos electrónicos nuevos
    $emails = imap_search($inbox, 'UNSEEN');

    //wp_mail( 'pedrogarciaromera970@gmail.com', 'IMAP CONECTADO', "Automatic scheduled email from WordPress to test cron ");
  
    if ($emails) {
      foreach ($emails as $email_number) {
        // Obtener una vista previa del correo electrónico
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $subject = $overview[0]->subject;
        $from = $overview[0]->from;
        $message = imap_fetchbody($inbox, $email_number, 1);
        handle_new_email($subject, $from, $message);
      }
    }
  
    // Cerrar la conexión IMAP
    imap_close($inbox);
  }
  
  // Función para manejar un correo electrónico nuevo
  function handle_new_email($subject, $from, $message) {
    preg_match('/<([^>]+)>/', $from, $matches);
    if(isset($matches[1])) {
      // Mostramos el correo electrónico
      $email = $matches[1];
      if($email == 'noreply@mondialrelay.com' || $email == 'mondialrelay@notifications.com' || $email == 'pedro.garciaromera@alum.uca.es'){
        $patron = "/\b\d{8}\b/"; 
        $busquedaPuntoPack = "Envio admitido en Punto Pack";
        $busquedaRecogido = "Envio recogido";
  
        //wp_mail( 'pedrogarciaromera970@gmail.com', 'EMAIL DEL USUARIO',"Email de pedro $email" );
  
        if (preg_match($patron, $message, $numeros_envio)) {
          $numero_envio = $numeros_envio[0];
              $args = array(
              'meta_key'      => 'Numero_Envio',
              'meta_value'    => $numero_envio,
              'post_type'     => 'shop_order',
              'post_status'   => array( 'wc-processing', 'wc-completed' ),
          );
  
          $pedidos = wc_get_orders( $args );
  
          // Verificar si se encontraron pedidos
          if ( $pedidos ) {
              // Recorrer los pedidos encontrados
              foreach ( $pedidos as $pedido ) {
                  // Hacer algo con cada pedido
                  $pedido_id = $pedido->get_id();
                  if((strpos($message, $busquedaPuntoPack) !== false) && $pedido->get_status() == 'processing'){
                    $pedido->update_status( 'completed', __( 'Pedido enviado por el vendedor', 'woocommerce' ) );
                    $pedido->save();
                  }else if((strpos($message, $busquedaRecogido) !== false) && $pedido->get_status() == 'completed'){
                    $pedido->update_status( 'wc-delivered', __( 'Pedido recogido por Mondial Relay', 'woocommerce' ) );
                    $pedido->update_meta_data( 'fecha_recogida', date("Y-m-d") );
                    $pedido->save();
                  }else{
                    wp_mail( EMAIL_ADMIN, "NOTIFICACIÓN DE MONDIAL DE PEDIDO $pedido_id anómala", "Se ha detectado un correo de notificación de mondial relay para el pedido $pedido_id que no corresponde ni a pedido en sus instalaciones ni recogido por el usuario, compruebe que este todo correctamente
                    ". $pedido->get_status() .' ' .$message );
                  }
              }
          } else {
              wp_mail( EMAIL_ADMIN, 'NOTIFICACIÓN DE MONDIAL RELAY DE PEDIDO NO ENCONTRADO', "No se ha encontrado el pedido con el número de seguimiento: $numero_envio, compruebe que la información de yoreleo este correctamente.");
          } 
        } else {
          wp_mail( EMAIL_ADMIN, 'EXPEDICION NO ENCONTRADO',"Identificación de numero de expedición no encontrado en el mensaje, por favor comprueba el correo con el siguiente mensaje: $message" );
        }
     }else {
      wp_mail( EMAIL_ADMIN, 'EMAIL NO DEL USUARIO',"Email no de pedro $email" );
     }
    }
  }