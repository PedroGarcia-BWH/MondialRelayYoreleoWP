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
 * Version:           alpha-0.0.4
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pedro José García Romera
 * Author URI:        https://github.com/PedroGarcia-BWH
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yoreleo-mondial-relay
 */


if (!defined('WPINC')){
	die;
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