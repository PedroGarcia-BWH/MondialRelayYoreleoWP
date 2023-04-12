<?php
require_once dirname(__DIR__) . '/mondialRelayAPI/webHook.php';

add_action( 'webHook_Mondial_Relay', 'check_email' );
function hostinger_custom_cron_func() {
    wp_mail( 'pedrogarciaromera970@gmail.com', 'Automatic email', 'Automatic scheduled email from WordPress to test cron');
}