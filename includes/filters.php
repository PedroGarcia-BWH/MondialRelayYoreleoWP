<?php 

function my_custom_content_after_order_table() {
    echo '<p>Aquí puedes agregar tu contenido personalizado después de la tabla de detalles del pedido.</p>';
}
add_action( 'um_account_order_details_after_table', 'my_custom_content_after_order_table' );
/*add_filter( 'woocommerce_checkout_fields', 'agregar_nuevo_campo' );

function agregar_nuevo_campo( $fields ) {
    $fields['billing']['Nombre_Punto'] = array(
        'label'       => __( 'Lugar de recogida', 'woocommerce' ),
        'required'    => true,
        'class'       => array('my-custom-input'),
        'clear'       => true,
        'type'        => 'text',
        'custom_attributes' => array(
            'readonly' => 'readonly' // Añade el atributo readonly
        ),
    );

    $fields['billing']['Direccion_Punto'] = array(
        'label'       => __( 'Dirección de recogida', 'woocommerce' ),
        'required'    => true,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
        'type'        => 'text',
        'custom_attributes' => array(
            'readonly' => 'readonly' // Añade el atributo readonly
        )
    );

    $fields['billing']['Tiempo_Entrega'] = array(
        'label'       => __( 'Tiempo de entrega', 'woocommerce' ),
        'required'    => true,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
        'type'        => 'text',
        'placeholder' => '3-5 días laborables',
        'custom_attributes' => array(
            'readonly' => 'readonly' // Añade el atributo readonly
        )
    );
    return $fields;
}*/



add_filter( 'woocommerce_checkout_fields', 'InfoPointRelais' );

function InfoPointRelais( $fields ) {
    $fields['billing']['Nombre_Punto_Hidden'] = array(
        'label'       => __( 'Lugar de recogida', 'woocommerce' ),
        'required'    => true,
        'class'       => array('ocultar-campo'),
        'clear'       => true,
        'type'        => 'text',
        'custom_attributes' => array(
            'readonly' => 'readonly' // Añade el atributo readonly
        ),
    );

    $fields['billing']['Direccion_Punto_Hidden'] = array(
        'label'       => __( 'Dirección de recogida', 'woocommerce' ),
        'required'    => true,
        'class'       => array( 'ocultar-campo' ),
        'clear'       => true,
        'type'        => 'text',
        'custom_attributes' => array(
            'readonly' => 'readonly' // Añade el atributo readonly
        )
    );

    $fields['billing']['Punto_Pack_Hidden'] = array(
        'label'       => __( 'Punto Pack', 'woocommerce' ),
        'required'    => true,
        'class'       => array( 'ocultar-campo' ),
        'clear'       => true,
        'type'        => 'text',
        'custom_attributes' => array(
            'readonly' => 'readonly' // Añade el atributo readonly
        )
    );
    return $fields;
}


function mi_filtro_personalizado() {
    // Agrega una etiqueta <p> con un mensaje personalizado antes de la información del cliente en la página de pago de WooCommerce
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <p class="point-text">Lugar de recogida</p>
    <div class="point-container">
        <span class="material-symbols-outlined">storefront</span>
        <p id="Nombre_Punto" class="point-data"></p>
    </div>
    <p  class="point-text">Dirección del punto de recogida</p>
    <div class="point-container">
        <span class="material-symbols-outlined">location_on</span>
        <p id="Direccion_Punto" class="point-data"></p>
    </div> 
    <p  class="point-text">Tiempo de entrega</p>
    <div class="point-container">
        <span class="material-symbols-outlined">timer</span>
        <p id="Tiempo_Entrega" class="point-data">2-4 días laborables</p>
    </div>';
          /*Texto de los titulos mas grandes y negrita, separarlos por una linea finita añadir un div con dentro la info y
           un icono represativo, colores de la info asi medio gris */
}
add_filter( 'woocommerce_checkout_after_customer_details', 'mi_filtro_personalizado' );

?>