<?php

require dirname(__DIR__) . '/vendor/autoload.php';

function SearchByPointRelais($cp, $NumPointRelais)
{
    $mondialrelay = new \MondialRelay\Webservice('BDTEST13', 'PrivateK');

    $parameters = [
        'Pays' => "ES",
        'CP' => $cp,
        'RayonRecherche' => "20",
        'NombreResultats' => "20",
    ];

    $searchParcelshop = $mondialrelay->searchParcelshop($parameters)->getResults();

    foreach ($searchParcelshop->PointsRelais->PointRelais_Details as $pointRelais) {
        if ($pointRelais->Num  == $NumPointRelais) {
            // Hacer algo con el objeto que contiene el número buscado
            //print_r($pointRelais);
            return $pointRelais;
        }
    }
    
    return null;
}