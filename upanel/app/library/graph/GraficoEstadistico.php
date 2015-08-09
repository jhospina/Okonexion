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
            do {
                $color = Colores::obtenerAleatorio();
            } while (in_array($color, $colores));

            $colores[] = $color;

            $html.="{value:" . $ejeY[$i] . " ,color:'" . $color . "',label: '" . $ejeX[$i] . "'},";
        }

        $html.="];";
        $html.="jQuery(document).ready(function(){";
        $html.="var ctx_$id = document.getElementById('$id').getContext('2d');";
        $html.="new Chart(ctx_$id).Pie(data_$id, {segmentShowStroke : false,animateRotate : true});";
        $html.="});</script>";

        return $html;
    }

    /** Imprime un grafico de barras
     * 
     * @param Array $ejeX Los valores del eje X de la grafica
     * @param Array $ejeY Los valores de frecuencia del eje Y
     * @param String $id El identificador de la grafica
     * @param int|String $width El ancho de la grafica
     * @param int|String $height La Altura de la grafica
     * @param String $css Css personalizado
     * @param boolean $responsive Indica si es responsivo
     * @return string
     */
    static function barras($ejeX, $ejeY, $id, $width = null, $height = null,$css=null,$responsive=false) {
        $html = "<canvas id='$id' style='$css;width:" . $width . ";height:" . $height . "' width='" . $width . "' height='" . $height . "'></canvas>";

        $html.="<script>";
        $html.="var data_$id={labels:[" . Util::formatearResultadosArray($ejeX, ",", "'", "'") . "],";
        $html.='datasets: [{fillColor: "#FF6666", strokeColor: "red", highlightFill: "#FF6666", highlightStroke: "black", data: [' . Util::formatearResultadosArray($ejeY, ",") . ']}]';
        $html.="};";
        $html.="jQuery(document).ready(function(){";
        $html.="var ctx_$id = document.getElementById('$id').getContext('2d');";
        $html.="new Chart(ctx_$id).Bar(data_$id, {responsive: ".HtmlControl::setBoolean($responsive).", barStrokeWidth: 1, scaleGridLineWidth: 1,maintainAspectRatio: false});";
        $html.="});</script>";

        return $html;
    }

}
