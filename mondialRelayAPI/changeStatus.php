<?php

// Programar la tarea para que se ejecute diariamente a las 3:00am
wp_schedule_event( strtotime('3:30am'), 'daily', 'change_status_recogido_sucess' );

function change_status_recogido_sucess() {
    $order_query = new WC_Order_Query();

    /*Preguntar esto = processing->completed->delivered -> delivered-success o delivered-problem*/
    $args = array(
        'post_status' => 'wc-delivered', //placeholder
    );


    $orders = $order_query->query( $args );

    foreach ( $orders as $order ) {
        //fecha de recogida del pedido
        //Hay que meter un campo en el meta que sea fecha de recogida y que se actualice cuando se cambie el estado a recogido
        $pickup_date = strtotime( $order->get_date_created()->date('Y-m-d') );
    
        // Obtiene la fecha actual
        $current_date = strtotime( 'now' );
    
        // Calcula la diferencia de tiempo en segundos
        $time_diff = $current_date - $pickup_date;
    
        // Calcula la cantidad de segundos en 2 días
        $two_days_in_seconds = 2 * 24 * 60 * 60;
    
        // Si han pasado más de 2 días desde que se recogió el pedido
        if ( $time_diff >= $two_days_in_seconds ) {
            // Actualiza el estado del pedido a "wc-delivered-success"
            $order->update_status( 'wc-delivered-success', __( 'Pedido entregado correctamente después de 2 días', 'woocommerce' ) );
        }
    }
}


?>