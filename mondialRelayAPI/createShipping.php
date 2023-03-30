<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/private/config.php';

function createShipping($parameters) {

    $mondialrelay = new \MondialRelay\Webservice(MONDIAL_ACCESS, MONDIAL_PASS);

    $createShipping = $mondialrelay->createShipping($parameters)->getResults();

    return $createShipping;

}