<?php

/**
 * @package           mondialRelayWP
 * @author            Pedro José García Romera
 * @copyright         2022 Acceso Web
 * @license           GPL-2.0-or-later
 * @link              https://github.com/PedroGarcia-BWH/MondialRelayYoreleoWP
 * @since             alpha-0.0.1
 *
 * @wordpress-plugin
 * Plugin Name:       Mondial Relay by Yoreleo
 * Plugin URI:        https://github.com/PedroGarcia-BWH/MondialRelayYoreleoWP
 * Description:       Conexión con la API de Mondial Relay para la gestión de envíos y datos de los clientes de Yoreleo
 * Version:           alpha-1.0.1
 * Requires at least: 5.2
 * Requires PHP:      8.1
 * Author:            Pedro José García Romera
 * Author URI:        https://github.com/PedroGarcia-BWH
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yoreleo-mondial-relay
 */

if (!defined('WPINC')){
	die;
}

register_activation_hook( __FILE__, 'my_plugin_activation' );

function my_plugin_activation() {
    if (!extension_loaded('imap')) {
        echo 'La extensión IMAP no está habilitada';
        exit();
    }

    add_option('MONDIAL_ACCESS', 'BDTEST13');
    add_option('MONDIAL_PASS', 'PrivateK');
    add_option('BLUE_KEY', 'api_key');
    add_option('EMAIL_ADMIN', 'example@mondial.com');
    add_option('IMAP_SERVER', '{imap.mondial.com:993/imap/ssl}INBOX');
    add_option('IMAP_EMAIL', 'example@yoreleo.es');
    add_option('IMAP_PASS', 'password!');
}


//Include includes/
foreach (glob(plugin_dir_path(__FILE__) . "includes/*.php") as $filename) {
    include_once $filename;
}

include_once plugin_dir_path(__FILE__) . "mondialRelayAPI/updateStatus.php";


function my_custom_styles() {
    wp_enqueue_style( 'mondialStyles', plugin_dir_url( __FILE__ ) . 'public/css/public.css' );
    wp_enqueue_style( 'statusShipping', plugin_dir_url( __FILE__ ) . 'public/css/statusShipping.css' );
}
add_action( 'wp_enqueue_scripts', 'my_custom_styles' );


/*function my_account_page_default_tabs( $tabs ) {
    //$tabs['none'] = __( 'None', 'ultimate-member' );
    //unset( $tabs['orders'] ); // Remueve la opción de órdenes
    $tabs['default_tab'] = ''; // Cambia el nombre de la pestaña de órdenes
    //print_r($tabs);
    return $tabs;
}
add_filter( 'um_account_page_default_tabs_hook', 'my_account_page_default_tabs', 999, 1 );*/


