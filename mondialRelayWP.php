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
 * Version:           alpha-1.0.0
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
    $theme_dir = get_stylesheet_directory();
    $new_folder = 'um-woocommerce';

    $folder_path = $theme_dir . '/ultimate-member/' . $new_folder;
    $file_path = $folder_path . '/order-popup.php';

    if (!extension_loaded('imap')) {
        echo 'La extensión IMAP no está habilitada';
        exit();
    }

    if (file_exists($folder_path)) {
        if(file_exists($file_path)) unlink($file_path);
    } else {
        wp_mkdir_p($folder_path);
    }

    $file_content = file_get_contents(plugin_dir_path(__FILE__)."public/templates/order-popup.php");

    file_put_contents($file_path, $file_content);

    //creación de estado de pedido personalizado delivered, dlivered-success y delivered-problem
    if ( ! get_post_status_object( 'wc-delivered' ) ) {
        // Registrar un nuevo estado de pedido personalizado
        register_post_status( 'wc-delivered', array(
            'label'                     => __( 'Delivered', 'woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Delivered <span class="count">(%s)</span>', 'Delivered <span class="count">(%s)</span>', 'woocommerce' )
        ) );
    }

    if ( ! get_post_status_object( 'wc-delivered-success' ) ) {
        // Registrar un nuevo estado de pedido personalizado
        register_post_status( 'wc-delivered-success', array(
            'label'                     => __( 'Delivered Success', 'woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Delivered Success <span class="count">(%s)</span>', 'Delivered Success <span class="count">(%s)</span>', 'woocommerce' )
        ) );
    }

    if ( ! get_post_status_object( 'wc-delivered-problem' ) ) {
        // Registrar un nuevo estado de pedido personalizado
        register_post_status( 'wc-delivered-problem', array(
            'label'                     => __( 'Delivered Problem', 'woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Delivered Problem <span class="count">(%s)</span>', 'Delivered Problem <span class="count">(%s)</span>', 'woocommerce' )
        ) );
    }

}


add_action( 'upgrader_process_complete', 'my_plugin_upgrade_action', 10, 2 );

function my_plugin_upgrade_action( $upgrader_object, $options ) {
    if ( $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
        foreach ( $options['plugins'] as $plugin ) {
            if ( $plugin == 'MondialRelayYoreleoWP/mondialRelayWP.php' ) { // Reemplaza "my-plugin/my-plugin.php" por la ruta del plugin que quieras comprobar
                my_plugin_activation();
            }
        }
    }
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


function my_account_page_default_tabs( $tabs ) {
    //$tabs['none'] = __( 'None', 'ultimate-member' );
    //unset( $tabs['orders'] ); // Remueve la opción de órdenes
    $tabs['default_tab'] = ''; // Cambia el nombre de la pestaña de órdenes
    print_r($tabs);
    return $tabs;
}
add_filter( 'um_account_page_default_tabs_hook', 'my_account_page_default_tabs', 999, 1 );


/*wp_schedule_event( time(), '5_minutes', 'check_email' );

// Agregar un nuevo intervalo de tiempo de 5 minutos
add_filter( 'cron_schedules', function( $schedules ) {
    $schedules['5_minutes'] = array(
        'interval' => 300,
        'display'  => esc_html__( 'Every 5 Minutes', 'textdomain' ),
    );
    return $schedules;
} );
*/