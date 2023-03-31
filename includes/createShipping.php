<?php
    require_once dirname(__DIR__) . '/mondialRelayAPI/createShipping.php';
    require_once dirname(__DIR__) . '/mondialRelayAPI/createLabel.php';
    require_once dirname(__DIR__) . '/sendiblue/sendEmail.php';
    

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
        'Poids' => '200', //placeholder
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

    if ($createShipping->STAT != '0') {
        $order->update_status('failed', 'Error al crear el envío');
        return;
    } else {
        $order->update_meta_data('Numero_Envio', $createShipping->ExpeditionNum);
        $order->update_meta_data('Etiqueta', $createShipping->URL_Etiquette);
        $order->save();

        sendEmail($createShipping->URL_Etiquette, 'pedro.garciaromera@alum.uca.es');

        //Envio de información al vendedor
        $vendor_id = $order->get_meta('_vendor_id');
        $vendor = get_user_by('ID', $vendor_id);
        $vendor_email = $vendor->user_email;
        sendEmail($createShipping->URL_Etiquette, $vendor_email);
    }
}

//guardamos los campos de informacion de mondial relay en el pedido
add_action('woocommerce_checkout_create_order', 'saveInfoPointRelais');
function saveInfoPointRelais($order) {
    $order->update_meta_data('Punto_Pack_Hidden', $_POST['Punto_Pack_Hidden']);

    $order->update_meta_data('Nombre_Punto_Hidden', $_POST['Nombre_Punto_Hidden']);

    $order->update_meta_data('Direccion_Punto_Hidden', $_POST['Direccion_Punto_Hidden']);
}