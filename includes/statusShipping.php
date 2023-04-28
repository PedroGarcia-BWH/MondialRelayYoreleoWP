<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

add_shortcode('StatusShipping', 'StatusShipping');

function StatusShipping($atts) {
    $atts = shortcode_atts( array(
        'order_id' => ''
    ), $atts );

    

    $html = '';

    if ( $atts['order_id'] ) {
        $order = wc_get_order( $atts['order_id'] );
        if ( $order ) {
            //$now = new DateTime();
            //$interval = $now->diff(get_post_meta( $atts['order_id'], '_order_date_created', true ) );
            //$days_difference = $interval->days;
            $days_difference = 0;
            $html = '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
            <div>
            <h2>Estado del pedido</h2>
            <div class="paquete">
                <div class="estado">
                    <span class="material-symbols-outlined">shopping_cart_checkout</span>
                    <p>Su pedido ha sido creado</p>
                    <input id="Created" type="checkbox" disabled style="border-radius: 50%;">
                </div>
                <div class="estado">
                    <span class="material-symbols-outlined">home_pin</span>
                    <p>Su paquete ha sido entregado a un Punto Pack </p>
                    <input id="PuntoPack" type="checkbox" disabled style="border-radius: 50%;">
                </div>
        
                <div class="estado">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <p>Su paquete esta en tránsito</p>
                    <input id="Transito" type="checkbox" disabled style="border-radius: 50%;">
                </div>
                <div class="estado">
                    <span class="material-symbols-outlined">storefront</span>
                    <p>Su paquete está disponible para su recogida</p>
                    <input id="Disponible" type="checkbox" disabled style="border-radius: 50%;">
                </div>
        
                <div class="estado">
                    <span class="material-symbols-outlined">check_circle</span>
                    <p>Su paquete ha sido entregado</p>
                    <input id="Entregado"type="checkbox" disabled style="border-radius: 50%;">
                </div>
            </div>
            </div>
            <p>Haga clic en el siguiente enlace para obtener más información en Inpost: https://www.inpost.es/seguimiento-del-envio/</p>  
            <script>
            
            cod = '.statShipping($order->get_meta('Numero_Envio')).';
        
                    switch (cod) {
                        case 80:
                            document.getElementById("Created").checked = true;
                            break
                        case 81:
                            if("'.$order->get_status().'" == "processing") {
                                document.getElementById("PuntoPack").checked = true;
                            } else if("'.$order->get_status().'" == "completed" ) {
                                document.getElementById("Disponible").checked = true;
                            }
                            break
                        case 82 : 
                            document.getElementById("Entregado").checked = true;
                            break
                        
                        case 88:
                            document.getElementById("Transito").checked = true;
                            break
        
                        default:
                            console.log("No se ha encontrado el código de seguimiento");
                            break
                    }
                
                console.log("Purbecta: '.wp_next_scheduled( 'update_status_delivered' ).'");
            </script>';
        } 
    }
    return $html;
}


function statShipping($numPedido) {

    // Creamos una instancia del servicio web de Mondial Relay y le pasamos nuestras credenciales
    //$mondialrelay = new \MondialRelay\Webservice('BDTEST13', 'PrivateK');
    $mondialrelay = new \MondialRelay\Webservice(get_option('MONDIAL_ACCESS'), get_option('MONDIAL_PASS'));

    // Creamos un array con los parámetros de búsqueda
    $parameters = [
        'Expedition' => $numPedido,
        'Langue' => 'ES'
    ];

    $trackParcel = $mondialrelay->trackParcel($parameters)->getResults();

    //echo $trackParcel->STAT;
    return $trackParcel->STAT;
}
    


?>
