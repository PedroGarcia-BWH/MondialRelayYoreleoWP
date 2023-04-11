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
 * Version:           alpha-0.0.5
 * Requires at least: 8.1
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
    $theme_dir = get_stylesheet_directory();
    $new_folder = 'um-woocommerce';

    $folder_path = $theme_dir . '/ultimate-member/' . $new_folder;
    $file_path = $folder_path . '/order-popup.php';

    if (file_exists($folder_path)) {
        if(file_exists($file_path)) unlink($file_path);
    } else {
        wp_mkdir_p($folder_path);
    }

    $file_content = file_get_contents(plugin_dir_path(__FILE__)."public/templates/order-popup.php");

    file_put_contents($file_path, $file_content);
}


//Include includes/
foreach (glob(plugin_dir_path(__FILE__) . "includes/*.php") as $filename) {
    include_once $filename;
}


function my_custom_styles() {
    wp_enqueue_style( 'mondialStyles', plugin_dir_url( __FILE__ ) . 'public/css/public.css' );
    wp_enqueue_style( 'statusShipping', plugin_dir_url( __FILE__ ) . 'public/css/statusShipping.css' );
}
add_action( 'wp_enqueue_scripts', 'my_custom_styles' );


