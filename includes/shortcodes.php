<?php

add_shortcode('PointRelayMap', 'PointRelayMap');

function PointRelayMap()
{
    $html = '<div id="Zone_Widget"></div>';
    $html .= '<input type="text" id="Target_Widget" />';
    $html .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
    $html .= '<script src="//unpkg.com/leaflet/dist/leaflet.js"></script>';
    $html .= '<link rel="stylesheet" type="text/css" href="//unpkg.com/leaflet/dist/leaflet.css" />';
    $html .= '<script src="//widget.mondialrelay.com/parcelshop-picker/jquery.plugin.mondialrelay.parcelshoppicker.min.js"></script>';
    $html .= '<script>
    $(document).ready(function() {
        let postal_code = "28001"; // Código postal por defecto, en caso de que no se pueda obtener la ubicación del usuario
          // Primero, obtenemos la ubicación del usuario
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            // Luego, obtenemos las coordenadas de latitud y longitud
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            // Utilizamos la API de geocodificación inversa de Google Maps para obtener la dirección postal
            $.getJSON("https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + lat + "&lon=" + lng, function(data) {
              // Extraer el código postal de los datos de la ubicación
              postal_code = data.address.postcode;
              $("#Zone_Widget").MR_ParcelShopPicker({
                Target: "#Target_Widget",
                Brand: "BDTEST  ",
                Country: "ES",
                PostCode: postal_code,
                ColLivMod: "24R",
                NbResults: "7",
                Responsive: true,
                ShowResultsOnMap: true
              });
              // Mostrar el código postal en la consola
              console.log(postal_code);
            });
          });
        }else {
            console.log("La geolocalización no está disponible en este navegador");
  
            $("#Zone_Widget").MR_ParcelShopPicker({
              Target: "#Target_Widget",
              Brand: "BDTEST  ",
              Country: "ES",
              PostCode: postal_code,
              ColLivMod: "24R",
              NbResults: "7",
              Responsive: true,
              ShowResultsOnMap: true
            });
        }
      });
    </script>';
    return $html;
}