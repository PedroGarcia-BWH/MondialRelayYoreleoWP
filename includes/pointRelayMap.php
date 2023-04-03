<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/mondialRelayAPI/wooComercceClient/Client.php';
require_once dirname(__DIR__) . '/private/config.php';

add_shortcode('PointRelayMap', 'PointRelayMap');

function PointRelayMap()
{
    $html = '<div id="Zone_Widget"></div>';
    $html .= '<input type="text" id="Target_Widget" />';
    $html .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
    $html .= '<script src="//unpkg.com/leaflet/dist/leaflet.js"></script>';
    $html .= '<script src="/public.js"></script>';
    $html .= '<link rel="stylesheet" type="text/css" href="//unpkg.com/leaflet/dist/leaflet.css" />';
    $html .= '<script src="//widget.mondialrelay.com/parcelshop-picker/jquery.plugin.mondialrelay.parcelshoppicker.min.js"></script>';
    
    $html .= '<script>
    function OnParcelShopSelected(selectedParcelShop) {
      var nombrePunto = document.getElementById("Nombre_Punto");
      var direccionPunto = document.getElementById("Direccion_Punto");
      var nombrePuntoHidden = document.getElementById("Nombre_Punto_Hidden");
      var direccionPuntoHidden = document.getElementById("Direccion_Punto_Hidden");
      var codigoPunto = document.getElementById("Punto_Pack_Hidden");

      nombrePunto.textContent = selectedParcelShop.Nom;
      nombrePuntoHidden.value = selectedParcelShop.Nom;
      codigoPunto.value = selectedParcelShop.ID;
      direccionPunto.textContent = selectedParcelShop.Adresse1 + ", " + selectedParcelShop.CP + ", " + selectedParcelShop.Ville;
      direccionPuntoHidden.value = selectedParcelShop.Adresse1 + ", " + selectedParcelShop.CP + ", " + selectedParcelShop.Ville;
    }

    $(document).ready(function() {

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          // Luego, obtenemos las coordenadas de latitud y longitud
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          // Utilizamos la API de geocodificación inversa de Google Maps para obtener la dirección postal
          $.getJSON("https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + lat + "&lon=" + lng, function(data) {
            var postal_code = data.address.postcode;
              if (postal_code == null) postal_code = "28001";
              
              $("#Zone_Widget").MR_ParcelShopPicker({
                Target: "",
                Brand: "BDTEST  ",
                Country: "ES",
                PostCode: postal_code,
                ColLivMod: "24R",
                NbResults: "7",
                Responsive: true,
                ShowResultsOnMap: true,
                OnParcelShopSelected: OnParcelShopSelected
            });
          });
        });
      }else {
        $("#Zone_Widget").MR_ParcelShopPicker({
          Target: "",
          Brand: "BDTEST  ",
          Country: "ES",
          PostCode: "28001",
          ColLivMod: "24R",
          NbResults: "7",
          Responsive: true,
          ShowResultsOnMap: true,
          OnParcelShopSelected: OnParcelShopSelected
      });
      }
    });
    </script>';
    return $html;
}

