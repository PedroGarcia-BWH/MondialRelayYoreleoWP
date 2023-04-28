<?php

require_once 'createShipping.php';
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
        $order->update_status( 'processing', __( 'Pedido en estado de procesando', 'woocommerce' ) );
        $order->save();
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
    echo '<p>Correo electrónico actual: '.get_option('EMAIL_ADMIN').'</p>';
    echo '<form method="post">';
    echo '<label for="email">Correo electrónico</label>';
    echo '<input type="text" name="email" id="email" style="width: 300px;"><br>';
    echo '<button type="submit" name="submit">Modificar</button>';
    echo '</form>';

    echo '<h2>Modificar credenciales de Mondial Relay</h2>';
    echo '<form method="post">';
    echo '<label for="acceso">Acceso de Mondial</label>';
    echo '<input type="text" name="access" id="access" placeholder="'.get_option('MONDIAL_ACCESS').'"><br>';
    echo '<label for="password">Contraseña a Mondial</label>';
    echo '<input type="text" name="pass" id="pass" placeholder="'.get_option('MONDIAL_PASS').'"><br>';
    echo '<button type="submit" name="mondial">Modificar</button>';
    echo '</form>';

    echo '<h2>Modificar clave de Acceso de Sendiblue</h2>';
    echo '<form method="post">';
    echo '<label for="acceso">Api key de Sendiblue</label>';
    echo '<input type="text" name="key" id="key" placeholder="'.get_option('BLUE_KEY').'" style="width: 500px;"><br>';
    echo '<button type="submit" name="sendiblue">Modificar</button>';
    echo '</form>';

    echo '<h2>Modificar configuración del servidor IMAP</h2>';
    echo '<form method="post">';
    echo '<label for="acceso">Url de servidor IMAP</label>';
    echo '<input type="text" name="server" id="server" placeholder="'.get_option('IMAP_SERVER').'" style="width: 300px;"><br>';
    echo '<label for="acceso">Email de acceso</label>';
    echo '<input type="text" name="imap_email" id="imap_email" placeholder="'.get_option('IMAP_EMAIL').'" style="width: 300px;"><br>';
    echo '<label for="acceso">Contraseña de acceso</label>';
    echo '<input type="text" name="pass_server" id="pass_server" placeholder="'.get_option('IMAP_PASS').'"><br>';
    echo '<button type="submit" name="IMAP">Modificar</button>';
    echo '</form>';



    if (isset($_POST['submit'])) {
      $email = $_POST['email'];
      update_option('EMAIL_ADMIN', $email);
      echo 'Correo electrónico modificado a: '.$email;
    }

    if (isset($_POST['mondial'])) {
      $access = $_POST['access'];
      $pass = $_POST['pass'];
      update_option('MONDIAL_ACCESS', $access);
      update_option('MONDIAL_PASS', $pass);
      echo 'Credenciales modificadas';
    }

    if (isset($_POST['sendiblue'])) {
      $key = $_POST['key'];
      update_option('BLUE_KEY', $key);
      echo 'Clave modificada';
    }

    if (isset($_POST['IMAP'])) {
      $server = $_POST['server'];
      $email = $_POST['imap_email'];
      $pass = $_POST['pass_server'];
      update_option('IMAP_SERVER', $server);
      update_option('IMAP_EMAIL', $email);
      update_option('IMAP_PASS', $pass);
      echo 'Configuración modificada';
    }
}