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
            $html = '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <div>
            <h2>Estado del pedido</h2>
            <div class="paquete">
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
            <script>
            
            jQuery.ajax({
                url: "",
                type: "POST",
                data: {
                    accion: "statShipping",
                    //numPedido: numPedido
                    numPedido: '.$order->get_meta('Numero_Envio').'
                },
                success: function(response) {
                    console.log(response.codStatus);
                    // código de seguimiento del paquete
                    //const codigo = 84;
        
                    switch (parseInt(response.codStatus)) {
                        case 82 || 87: 
                            document.getElementById("Entregado").checked = true;
                            break;
                        case 81:
                            document.getElementById("PuntoPack").checked = true;
                            break
                        case 85:
                            document.getElementById("Disponible").checked = true;
                            break;
                        case 88:
                            document.getElementById("Transito").checked = true;
                            break
        
                        default:
                            console.log("No se ha encontrado el código de seguimiento");
                            break;
                    }
                },
                error: function() {
                    console.log("Ha habido un error en la comunicación con el servidor");
                }
            });
            </script>';
        } 
    }
    return $html;
}


function statShipping($numPedido) {

    // Creamos una instancia del servicio web de Mondial Relay y le pasamos nuestras credenciales
    //$mondialrelay = new \MondialRelay\Webservice('BDTEST13', 'PrivateK');
    $mondialrelay = new \MondialRelay\Webservice(get_option( 'MONDIAL_ACCESS'), get_option( 'MONDIAL_PASS'));
    

    // Creamos un array con los parámetros de búsqueda
    $parameters = [
        'Expedition' => $numPedido,
        'Langue' => 'ES'
    ];

    $trackParcel = $mondialrelay->trackParcel($parameters)->getResults();

    //echo $trackParcel->STAT;
    return $trackParcel->STAT;
}
    

    // Comprobamos si se ha enviado el formulario
if (isset($_POST['accion']) == 'statShipping') {
    // Obtenemos los valores de los números enviados por AJAX
    $numPedido = $_POST['numPedido'];
        
    $codStatus = statShipping($numPedido);

    header('Content-Type: application/json');
    echo json_encode(array('codStatus' => $codStatus)); 
    exit;
}


?>
