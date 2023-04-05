<?php
    require_once dirname(__DIR__) . '/mondialRelayAPI/createShipping.php';
    require_once dirname(__DIR__) . '/mondialRelayAPI/createLabel.php';
    require_once dirname(__DIR__) . '/sendiblue/sendEmail.php';
    require_once dirname(__DIR__) . '/sendiblue/sendErrorEmail.php';
    

add_action('woocommerce_order_status_processing', 'createShippingAction');

//creamos el envio en mondial relay y guardamos el numero de envio en la orden
function createShippingAction($order_id) {
    $order = wc_get_order($order_id);
    $tildes = "áéíóúüñ";
    $sin_tildes = "aeiouun";
    $shipping_first_name = strtr($order->get_shipping_first_name(), $tildes, $sin_tildes);
    $shipping_last_name = strtr($order->get_shipping_last_name(), $tildes, $sin_tildes);
    $shipping_address_1 = strtr($order->get_shipping_address_1(), $tildes, $sin_tildes);
    $shipping_city = strtr($order->get_shipping_city(), $tildes, $sin_tildes);
    $items = $order->get_items(); // Obtiene todos los artículos del pedido
    $book = reset($items);
    $product = wc_get_product($book->get_product_id());
    if ( $product ) {
        $weight = $product->get_meta('_weight');
    } else {
        $weight = '900';
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
        'Expe_Tel1' => '+34611223344', //estos datos mñn preguntar
        'Dest_Langage' => 'ES',
        'Dest_Ad1' => $shipping_first_name . ' ' . $shipping_last_name,
        'Dest_Ad3' => $shipping_address_1,
        'Dest_Ville' => $shipping_city,
        'Dest_CP' => $order->get_shipping_postcode(),
        'Dest_Pays' => 'ES',
        'Dest_Tel1' => '+34'.$order->get_billing_phone(),
        'Poids' => $weight, //placeholder
        'NbColis' => '1',
        'COL_Rel_Pays' => 'ES',
        'COL_Rel' => 'AUTO', //El vendendor puede llevarlo donde quiera
        'CRT_Valeur' => '0',
        'LIV_Rel_Pays' => 'ES',
        'LIV_Rel' => $order->get_meta('Punto_Pack_Hidden') //Recogida del comprador

    ];
    $order->update_meta_data('Numero_Envio', 'Valor');
    $order->update_meta_data('Etiqueta', 'Valor');
    $order->save();

    $createShipping = createLabel($parameters);

    $vendor_id = $order->get_meta('_dokan_vendor_id'); //----------------------------CUIDADO ESTO------------------------------
    $vendor = get_user_by('ID', $vendor_id);

    if ($createShipping->STAT != '0') {
        $order->update_status('failed', 'Error al crear el envío');
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
        sendErrorEmail(EMAIL_ADMIN, $order, $vendor, $createShipping->STAT, $error);
        return;
    } else {
        $order->update_meta_data('Numero_Envio', $createShipping->ExpeditionNum);
        $order->update_meta_data('Etiqueta', $createShipping->URL_Etiquette);
        $order->save();
         //Envio de información al vendedor
        
        $vendor_email = $vendor->user_email;

        sendEmail(EMAIL_ADMIN, $order, $vendor);

        sendEmail($vendor_email, $order, $vendor);
    }
}

//guardamos los campos de informacion de mondial relay en el pedido
add_action('woocommerce_checkout_create_order', 'saveInfoPointRelais');
function saveInfoPointRelais($order) {
    $order->update_meta_data('Punto_Pack_Hidden', $_POST['Punto_Pack_Hidden']);

    $order->update_meta_data('Nombre_Punto_Hidden', $_POST['Nombre_Punto_Hidden']);

    $order->update_meta_data('Direccion_Punto_Hidden', $_POST['Direccion_Punto_Hidden']);
}