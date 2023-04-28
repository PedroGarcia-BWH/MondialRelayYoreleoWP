<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
//add_action('update_status_processing_hook', 'update_status_processing');
//add_action('update_status_completed', 'update_status_completed');
//add_action('update_status_delivered', 'update_status_delivered');
add_action( 'init', 'cronJob' );
function cronJob() {
    if ( ! wp_next_scheduled( 'update_status_process' ) ) {
        wp_schedule_event( time(), 'hourly', 'update_status_process' );
    }
    if ( ! wp_next_scheduled( 'update_status_completed' ) ) {
        wp_schedule_event( time(), 'hourly', 'update_status_completed' );
    }
    if ( ! wp_next_scheduled( 'update_status_delivered' ) ) {
        wp_schedule_event( time(), 'hourly', 'update_status_delivered' );
    }
}

add_action( 'update_status_process', 'update_status_processing' );
add_action( 'update_status_completed', 'update_status_completed' );
add_action( 'update_status_delivered', 'update_status_delivered' );
function update_status_transit (){
    $args = array(
        'status' => 'processing',
        'limit' => -1,
    );
    
    $orders = wc_get_orders( $args );

    foreach ( $orders as $order ) {
        $numPedido = $order->get_meta('Numero_Envio');
        //si num pedido no esta vacio
        //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email10', count($orders) . ' ' .$order->get_id() );
        if ( ! empty( $numPedido && $numPedido != 'Valor' ) ) {
            try{
                $status = statShipping($numPedido);
            }catch (Exception $e){
                $status = 99;
            }   
            //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email14', $status . ' ' .$order->get_id() );
            if($status == 81) {
                //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email7', $status . ' ' .$order->get_id() );
                $order->update_status( 'transit', __( 'Pedido recogido por Inpost', 'woocommerce' ) );
                //post meta data
                $order->update_meta_data('Status_Inpost', 'puntoPack');
                date_default_timezone_set('Europe/Madrid');
                $order->update_meta_data('Status_Updated', date('Y-m-d H:i:s'));
                $order->save();
            }
        }
    }
    
    //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email', $contenido);
}


function update_status_available () {
    $args = array(
        'status' => 'transit',
        'limit' => -1,
    );
    
    $orders = wc_get_orders( $args );

    foreach ( $orders as $order ) {
        $numPedido = $order->get_meta('Numero_Envio');
        //si num pedido no esta vacio
        //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email10', count($orders) . ' ' .$order->get_id() );
        if ( ! empty( $numPedido && $numPedido != 'Valor' ) ) {
            try{
                $status = statShipping($numPedido);
            }catch (Exception $e){
                $status = 99;
            }   
            if($status == 81 && $order->get_meta('Status_Inpost') == 'puntoPack' && !empty($order->get_meta('Status_Updated'))) {
                $status_updated = $order->get_meta('Status_Updated');
                $now = new DateTime();
                $updated_date = new DateTime($status_updated);
                $interval = $now->diff($updated_date);
                $days_since_updated = $interval->days;
                
                if ($days_since_updated > 3) {
                    $order->update_status( 'available', __( 'Pedido disponible para su recogida', 'woocommerce' ) );
                    $order->update_meta_data('Status_Inpost', 'disponible');
                    date_default_timezone_set('Europe/Madrid');
                    $order->update_meta_data('Status_Updated', date('Y-m-d H:i:s'));
                    $order->save();
                }
            }
        }
    }
}

function update_status_delivered() { 
    $args = array(
        'status' => 'available',
        'limit' => -1,
    );
    
    $orders = wc_get_orders( $args );

    foreach ( $orders as $order ) {
        $numPedido = $order->get_meta('Numero_Envio');
        //si num pedido no esta vacio
        //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email10', count($orders) . ' ' .$order->get_id() );
        if ( ! empty( $numPedido && $numPedido != 'Valor' ) ) {
            try{
                $status = statShipping($numPedido);
            }catch (Exception $e){
                $status = 99;
            }   
            //wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email14', $status . ' ' .$order->get_id() );
            if($status == 82) {
                wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email20', count($orders) . ' ' .$order->get_id() );
                $order->update_status( 'completed', __( 'Pedido entregado por Inpost', 'woocommerce' ) );
                //post meta data
                $order->update_meta_data('Status_Inpost', 'entregado');
                date_default_timezone_set('Europe/Madrid');
                $order->update_meta_data('Status_Updated', date('Y-m-d H:i:s'));
                $order->save();
            }
        }
    }
    
}

/*function update_status_completed() {
    $order_query = new WC_Order_Query();

    $args = array(
        'post_status' => 'completed',
    );

    $orders = $order_query->query( $args );

    foreach ( $orders as $order ) {
        $numPedido = $order->get_meta('Numero_Envio');
        $status = statShipping($numPedido);
        if($status == 81 && $order->get_meta('Status_Inpost') == 'puntoPack' && $order->get_meta('Status_Updated')) {
            $status_updated = $order->get_meta('Status_Updated');
            $now = new DateTime();
            $updated_date = new DateTime($status_updated);
            $interval = $now->diff($updated_date);
            $days_since_updated = $interval->days;
            
            if ($days_since_updated > 3) {
                $order->update_meta_data('Status_Inpost', 'disponible');
            }
        }
    }
}


function update_status_delivered() {
    $order_query = new WC_Order_Query();

    $args = array(
        'post_status' => 'completed',
    );

    $orders = $order_query->query( $args );

    foreach ( $orders as $order ) {
        $numPedido = $order->get_meta('Numero_Envio');
        $status = statShipping($numPedido);
        if($status == 82) {
            $order->update_status( 'delivered', __( 'Pedido entregado por Inpost', 'woocommerce' ) );
            //post meta data
            $order->update_meta_data('Status_Inpost', 'entregado');
            $order->update_meta_data('Status_Updated', date('Y-m-d H:i:s'));
        }
    }
}*/

?>