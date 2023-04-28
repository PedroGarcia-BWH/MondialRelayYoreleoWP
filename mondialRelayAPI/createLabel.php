<?php

require dirname(__DIR__) . '/vendor/autoload.php';
function createLabel($parameters){
    $mondialrelay = new \MondialRelay\Webservice(get_option('MONDIAL_ACCESS'), get_option('MONDIAL_PASS'));
    $base_url= "https://www.mondialrelay.com";

    $createLabel = $mondialrelay->createLabel($parameters)->getResults();
    $createLabel->URL_Etiquette = $base_url.$createLabel->URL_Etiquette;
    return  $createLabel;
}