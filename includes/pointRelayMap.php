<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
add_shortcode('PointRelayMap', 'PointRelayMap');

function PointRelayMap()
{
    $html = '<div id="Zone_Widget"></div>';
    $html .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>';
    $html .= '<script src="//unpkg.com/leaflet/dist/leaflet.js"></script>';
    $html .= '<script src="/public.js"></script>';
    $html .= '<link rel="stylesheet" type="text/css" href="//unpkg.com/leaflet/dist/leaflet.css" />';
    $html .= '<script src="//widget.mondialrelay.com/parcelshop-picker/jquery.plugin.mondialrelay.parcelshoppicker.min.js"></script>';
    
    $html .= '<script>
    function OnParcelShopSelected(selectedParcelShop) {
      var nombrePunto = document.getElementById("Nombre_Punto");
      var direccionPunto = document.getElementById("Direccion_Punto");
      var nombrePuntoHidden = document.getElementById("nombre_punto_hidden");
      var direccionPuntoHidden = document.getElementById("direccion_punto_hidden");
      var codigoPunto = document.getElementById("punto_pack_hidden");

      nombrePunto.textContent = selectedParcelShop.Nom;
      nombrePuntoHidden.value = selectedParcelShop.Nom;
      codigoPunto.value = selectedParcelShop.ID;
      direccionPunto.textContent = selectedParcelShop.Adresse1 + ", " + selectedParcelShop.CP + ", " + selectedParcelShop.Ville;
      direccionPuntoHidden.value = selectedParcelShop.Adresse1 + ", " + selectedParcelShop.CP + ", " + selectedParcelShop.Ville;
    }

      window.onload = function() {
        var billing_postcode = document.getElementById("billing_postcode").value;

      if (billing_postcode === "") {
        document.getElementById("billing_postcode").value = "28001";
      }

        $("#Zone_Widget").MR_ParcelShopPicker({
          Target: "",
          Brand: "BDTEST  ",
          Country: "ES",
          PostCode: billing_postcode,
          ColLivMod: "24R",
          NbResults: "7",
          Responsive: true,
          ShowResultsOnMap: true,
          Theme: "inpost",
          OnParcelShopSelected: OnParcelShopSelected
      });
    };
    </script>';
    return $html;
}

