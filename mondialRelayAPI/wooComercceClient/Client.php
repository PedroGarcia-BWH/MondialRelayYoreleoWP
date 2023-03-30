<?php

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class Client {
    private $client;

    public function __construct($store_url, $consumer_key, $consumer_secret) {
        $options = array(
            'ssl_verify' => false,
        );

        try {
            $this->client = new WC_API_Client($store_url, $consumer_key, $consumer_secret, $options);
        } catch (WC_API_Client_Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;

            if ($e instanceof WC_API_Client_HTTP_Exception) {
                print_r($e->get_request());
                print_r($e->get_response());
            }
        }
    }

    public function getLastOrderByUser(){
        $current_User = wp_get_current_user();
        $user_email = $current_User->user_email;
        echo "<p>user_email: $user_email</p>";
        try {
            $customer =  $this->client->customers->get_by_email($user_email);
            print_r($customer);
            $customer_id = $customer->customer->id;
            echo "<p>customer_id: $customer_id</p>";
        } catch ( WC_API_Client_Exception $e ) {

            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;
    
        if ( $e instanceof WC_API_Client_HTTP_Exception ) {
    
            print_r( $e->get_request() );
            print_r( $e->get_response() );
        }
    }
        //
        //echo "<p>customer: $customer</p>";
        //$customer_id = $customer->id;
        //
        //return last order of orders
        echo"<p>prueba<p>";
        //return $orders;*/
        return "hola";
    }
}