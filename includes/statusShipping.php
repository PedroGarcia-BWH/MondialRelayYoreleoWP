<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/mondialRelayAPI/wooComercceClient/Client.php';
require_once dirname(__DIR__) . '/private/config.php';

add_shortcode('StatusShipping', 'StatusShipping');

function StatusShipping() {
    $html = '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <h1 style="text-align: center;">Estado del pedido NUM:123456</h1>
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

    <div class="line"></div>
    <script>
	
    jQuery.ajax({
        url: "",
        type: "POST",
        data: {
            accion: "statShipping",
            //numPedido: numPedido
            numPedido: "99500649"
        },
        success: function(response) {
            console.log(response.codStatus);
            // código de seguimiento del paquete
            //const codigo = 84;

            switch (parseInt(response.codStatus)) {
                case 82:
                    document.getElementById("Entregado").checked = true;
                    break;
                case 84:
                    document.getElementById("PuntoPack").checked = true;
                    break
                case 85:
                    document.getElementById("Disponible").checked = true;
                    break;
                case 87:
                    document.getElementById("Entregado").checked = true;
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
        return $html;
}


function statShipping($numPedido) {

    // Creamos una instancia del servicio web de Mondial Relay y le pasamos nuestras credenciales
    //$mondialrelay = new \MondialRelay\Webservice('BDTEST13', 'PrivateK');
    $mondialrelay = new \MondialRelay\Webservice(MONDIAL_ACCESS, MONDIAL_PASS);
    

    // Creamos un array con los parámetros de búsqueda
    $parameters = [
        //'Expedition' => $numPedido,
        'Expedition' => '99500649',
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
        
    // Llamamos a la función sumar y devolvemos el resultado
    $codStatus = statShipping($numPedido);
    //echo $resultado;
    header('Content-Type: application/json');
    echo json_encode(array('codStatus' => $codStatus)); 
    exit;
    }	
    
?>