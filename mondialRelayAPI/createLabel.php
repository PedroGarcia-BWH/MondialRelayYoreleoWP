<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/private/config.php';
function createLabel($parameters){
    $mondialrelay = new \MondialRelay\Webservice(MONDIAL_ACCESS, MONDIAL_PASS);
    $base_url= "https://www.mondialrelay.com";

    $createLabel = $mondialrelay->createLabel($parameters)->getResults();
    $createLabel->URL_Etiquette = $base_url.$createLabel->URL_Etiquette;
    return  $createLabel;
}