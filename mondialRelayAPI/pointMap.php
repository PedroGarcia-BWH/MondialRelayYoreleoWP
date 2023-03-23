<?php 
function _modper_obtener_usuario() {
    ?>
    <!-- JQuery required (>1.6.4) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    
    <!-- Leaflet dependency is not required since it is loaded by the plugin -->
    <script src="//unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" type="text/css" href="//unpkg.com/leaflet/dist/leaflet.css" />

    <!-- JS plugin for the Parcelshop Picker Widget MR using JQuery -->
    <script src="//widget.mondialrelay.com/parcelshop-picker/jquery.plugin.mondialrelay.parcelshoppicker.min.js"></script>
    
      <!-- HTML Element in which the Parcelshop Picker Widget is loaded -->
      <div id="Zone_Widget"></div>

    <!-- HTML element to display the parcelshop selected, for demo only. Should use hidden instead. -->
    <input type="text" id="Target_Widget" />

    <script>
    $(document).ready(function() {
      $("#Zone_Widget").MR_ParcelShopPicker({
        Target: "#Target_Widget",
        Brand: "BDTEST  ",
        Country: "ES",
        PostCode: "11100",
        ColLivMod: "24R",
        NbResults: "7",
        Responsive: true,
        ShowResultsOnMap: true
      });
    });
    </script>
    <?php
}