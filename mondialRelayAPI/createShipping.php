<?php
require dirname(__DIR__) . '/vendor/autoload.php';

function createShipping($parameters) {

    $mondialrelay = new \MondialRelay\Webservice(get_option('MONDIAL_ACCESS'), get_option('MONDIAL_PASS'));

    $createShipping = $mondialrelay->createShipping($parameters)->getResults();

    return $createShipping;

}