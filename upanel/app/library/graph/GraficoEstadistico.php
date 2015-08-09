<?php

class GraficoEstadistico {

    //Tipos de graficos
    const tg_lineal = "lineal";
    const tg_barras = "barras";
    const tg_radar = "radar";
    const tg_polar = "polar";
    const tg_pie = "pie"; //Torta
    const tg_dona = "dona";

    static function pie($ejeX, $ejeY, $id) {

        $colores = array();

        $html = "<canvas id='$id'></canvas>";

        $html.="<script>";
        $html.="var data_$id=[";

        for ($i = 0; $i < count($ejeX); $i++) {

            //Evita que un color se repita
            while (in_array($color = Colores::obtenerAleatorio(), $colores))
                $colores[] = $color;

            $html.="{value:" . $ejeY[$i] . " ,color:'" . $color . "',label: '" . $ejeX[$i] . "'},";
        }

        $html.="];";
        $html.="window.onload = function () {";
        $html.="var ctx_$id = document.getElementById('$id').getContext('2d');";
        $html.="window.myBar = new Chart(ctx_$id).Pie(data_$id, {segmentShowStroke : false,animateRotate : true});";
        $html.="}</script>";

        return $html;
    }

}
