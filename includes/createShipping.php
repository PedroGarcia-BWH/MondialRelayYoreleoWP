<?php
    require_once dirname(__DIR__) . '/mondialRelayAPI/createShipping.php';
    require_once dirname(__DIR__) . '/mondialRelayAPI/createLabel.php';
    require_once dirname(__DIR__) . '/sendiblue/sendEmail.php';
    require_once dirname(__DIR__) . '/sendiblue/sendErrorEmail.php';
    

add_action('woocommerce_order_status_processing', 'createShippingAction');

//creamos el envio en mondial relay y guardamos el numero de envio en la orden
function createShippingAction($order_id) {
    $order = wc_get_order($order_id);
    
    $shipping_first_name = eliminar_tildes($order->get_shipping_first_name());
    $shipping_last_name =  eliminar_tildes($order->get_shipping_last_name());
    $shipping_address_1 = eliminar_tildes($order->get_shipping_address_1());
    //$shipping_address_1 = preg_replace("/[^0-9A-Z_\-'., \/]/", "", $shipping_address_1);
    $shipping_address_1 = preg_replace("/[^0-9A-Za-z_\-'., \/]/", "", $shipping_address_1);
    
    $shipping_city = eliminar_tildes($order->get_shipping_city());
    $items = $order->get_items(); // Obtiene todos los artículos del pedido
    $book = reset($items);
    
    $product = wc_get_product($book->get_product_id());
    $weight = '900';
    if ( $product ) {
        if ( !empty($product->get_meta('_weight')) ) {
          $weight = $product->get_meta('_weight');
          if (is_float($weight) || !is_numeric($weight)) {
            // Si el peso es decimal o no es un número, asignar el valor 900
            $weight = 900;
          }
        }
      }
    $parameters = [
        'ModeCol' => 'REL',
        'ModeLiv' => '24R',
        'Expe_Langage' => 'ES',
        'Expe_Ad1' => 'Yoreleo',
        'Expe_Ad3' => 'Av. de la Ilustracion',
        'Expe_Ville' => 'Cadiz',
        'Expe_CP' => '11011',
        'Expe_Pays' => 'ES',
        'Expe_Tel1' => '+34611223344', 
        'Dest_Langage' => 'ES',
        'Dest_Ad1' => $shipping_first_name . ' ' . $shipping_last_name, //Nombre y apellidos
        'Dest_Ad3' => $shipping_address_1, //Domicilio del comprador
        'Dest_Ville' => $shipping_city, //Ciudad del comprador
        'Dest_CP' => $order->get_shipping_postcode(), //Código postal del comprador
        'Dest_Pays' => 'ES',
        'Dest_Tel1' => '+34'.$order->get_billing_phone(), //Teléfono del comprador
        'Poids' => $weight, //Peso del libro 
        'NbColis' => '1',
        'COL_Rel_Pays' => 'ES',
        'COL_Rel' => 'AUTO', //El vendendor puede llevarlo donde quiera
        'CRT_Valeur' => '0',
        'LIV_Rel_Pays' => 'ES',
        'LIV_Rel' => $order->get_meta('punto_pack_hidden') //Punto de recogida elegido por el comprador

    ];
    $order->update_meta_data('Numero_Envio', 'Valor');
    $order->update_meta_data('Etiqueta', 'Valor');
    $order->save();

    foreach ( $order->get_items() as $item_id => $item ) {

        $product_id = $item->get_product_id();
 
     }

    /*$vendor_id = $order->get_meta('_dokan_vendor_id'); //----------------------------CUIDADO ESTO------------------------------
    $vendor = get_user_by('ID', $vendor_id);*/
    $vendor_id = get_post_meta($product_id, 'vendor', true);

    $vendor = get_user_by('id',$vendor_id);

    try {
        $createShipping = createLabel($parameters);
    }catch(Exception $e) {
        $order->update_status('failed', 'Error al crear el envío(bad Parameters)');
        sendErrorEmail(get_option('EMAIL_ADMIN'), $order, $vendor, 'Error desconocido', $e->getMessage());
        $order->update_meta_data('nombre_error', $shipping_first_name . ' ' . $shipping_last_name);
        $order->update_meta_data('direccion_envio_error', $shipping_address_1);
        $order->update_meta_data('ciudad_envio_error', $shipping_city);
        $order->update_meta_data('codigo_postal_envio_error', $order->get_shipping_postcode());
        $order->update_meta_data('telefono_envio_error', $order->get_billing_phone());
        $order->update_meta_data('numero_envio_error', $createShipping->NUM);
        $order->update_meta_data('punto_pack_error', $order->get_meta('punto_pack_hidden'));
        $order->update_meta_data('peso_error', $weight);
        $order->update_meta_data('mensaje_error', $e->getMessage());
        $order->save();
        return;
    }
    

    if ($createShipping->STAT != '0') {
        sendErrorEmail(get_option('EMAIL_ADMIN'), $order, $vendor, 'Error desconocid3o', 'Error desconocid3o');
        $order->update_status('failed', 'Error al crear el envío(STAT != 0)');
        switch($createShipping->STAT) {
            case '1' || '2' || '3' || '5':
                $error = 'Identificiación con Mondial Inválida';
                break;
            case '11':
                $error = 'Número de retransmisión de recogida no válido';
                break;
            case '12':
                $error = 'País de retransmisión de colección no válido';
                break;
            case '13':
                $error = 'Tipo de envío no válido';
                break;
            case '14':
                $error = 'Número de relé de entrega no válido';
                break;
            case '15': 
                $error = 'País de retransmisión de entrega no válido';
                break;
            case '20':
                $error = 'Peso no válido';
                break;
            case '21':
                $error = 'Número de paquetes no válido';
                break;
            case '30':
                $error = 'Nombre de remitente no válido';
                break;
            case '31':
                $error = 'Dirección de remitente no válida';
                break;
            case '33':
                $error = 'Nombre de destinatario no válido';
                break;
            case '34':
                $error = 'Dirección de destinatario no válida';
                break;
            case '36':
                $error = 'Código postal de destinatario no válido';
                break;
            case '38':
                $error = 'Número de telefono de destinatario no válido';
                break;
            case '99':
                $error = 'Error propio de Mondial Relay(Problemas técnicos)';
                break;
            default:
                $error = 'Error desconocido';
                break;

        }
        sendErrorEmail(get_option('EMAIL_ADMIN'), $order, $vendor, $createShipping->STAT, $error);
        return;
    } else {
        $order->update_meta_data('Numero_Envio', $createShipping->ExpeditionNum);
        $order->update_meta_data('Etiqueta', $createShipping->URL_Etiquette);
        $order->save();
         //Envio de información al vendedor
        $vendor_email = $vendor->user_email;

        sendEmail(get_option('EMAIL_ADMIN'), $order, $vendor);

        sendEmail($vendor_email, $order, $vendor);
    }
}

//guardamos los campos de informacion de mondial relay en el pedido
add_action('woocommerce_checkout_create_order', 'saveInfoPointRelais');
function saveInfoPointRelais($order) {
    $order->update_meta_data('punto_pack_hidden', $_POST['punto_pack_hidden']);

    $order->update_meta_data('nombre_punto_hidden', $_POST['nombre_punto_hidden']);

    $order->update_meta_data('direccion_punto_hidden', $_POST['direccion_punto_hidden']);
}


function eliminar_tildes($cadena){

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}