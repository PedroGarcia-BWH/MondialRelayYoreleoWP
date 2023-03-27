<?php 


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

function mi_filtro_personalizado() {
    // Agrega una etiqueta <p> con un mensaje personalizado antes de la información del cliente en la página de pago de WooCommerce
    echo '<p>Lugar de recogida</p>
          <p id="Nombre_Punto"></p>
          <p>Dirección del punto de recogida</p>
          <p id="Direccion_Punto"></p>
          <p>Tiempo de entrega</p>
          <p id="Tiempo_Entrega">3-5 días laborables</p>';

          /*Texto de los titulos mas grandes y negrita, separarlos por una linea finita añadir un div con dentro la info y
           un icono represativo, colores de la info asi medio gris */

}
add_filter( 'woocommerce_checkout_after_customer_details', 'mi_filtro_personalizado' );