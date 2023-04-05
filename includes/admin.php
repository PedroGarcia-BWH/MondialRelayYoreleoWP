<?php

require_once 'createShipping.php';
require_once dirname(__DIR__) . '/private/config.php';
// Agrega una sección en el menú de administración
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
    // Agrega la sección principal en el menú
    add_menu_page(
        'Mondial Relay Yoreleo', // Título de la página
        'Mondial Relay Yoreleo', // Título del menú
        'manage_options', // Capacidad requerida para acceder a la página
        'my-plugin', // Identificador único de la página
        'my_plugin_page', // Función que muestra el contenido de la página
        'dashicons-email', // Icono que se mostrará en el menú
        85 // Posición en el menú
    );

    // Agrega una subsección en el menú
    add_submenu_page(
        'my-plugin', // Identificador único de la sección principal
        'Configuración', // Título de la subsección
        'Configuración', // Título del menú
        'manage_options', // Capacidad requerida para acceder a la página
        'my-plugin-settings', // Identificador único de la subsección
        'my_plugin_settings_page' // Función que muestra el contenido de la subsección
    );
}

// Función que muestra el contenido de la sección principal
function my_plugin_page() {
    echo '<h1>Mondial Relay Yoreleo</h1>';

    if (isset($_POST['submit'])) {
      // Recupera los datos del formulario
      $order_id = $_POST['order_id'];

      $order = wc_get_order($order_id); // Obtiene la orden
      
      if($order) {
        createShippingAction($order_id);
        echo "<p>Proceso completado para el pedido $order_id, compruebe el email del administrador para comprobar si se ha realizado correctamente </p>";
      }else {
        echo '<p>No existe el pedido <p>';
      }
    }

  echo '<h2>Crear etiqueta</h2>';

  echo '<form method="post">';

  echo '<label for="order">Número de pedido</label>';
  echo '<input type="text" name="order_id" id="order_id"><br>';

  echo '<button type="submit">Crear etiqueta</button>';

  echo '</form>';

}

// Función que muestra el contenido de la subsección
function my_plugin_settings_page() {
    echo '<h1>Configuración de Mondial Relay Yoreleo</h1>';

    echo '<h2>Modificar correo electrónico de notificación a administrador</h2>';
    echo '<p>Correo electrónico actual: '.EMAIL_ADMIN.'</p>';
    echo '<form method="post">';
    echo '<label for="email">Correo electrónico</label>';
    echo '<input type="text" name="email" id="email"><br>';
    echo '<button type="submit">Modificar</button>';


    if (isset($_POST['submit'])) {
      // Recupera los datos del formulario
      $email = $_POST['email'];

      echo 'Correo electrónico modificado a: '.$email;
    }
}