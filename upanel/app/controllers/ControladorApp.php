<?php

class ControladorApp extends \BaseController {

    function conectar() {
        return '{"c2dictionary":true,"data":{' .
                '"colorBarraApp":"c23",' .
                '"colorFondoMenuBt_1":"c40",' .
                '"colorFondoMenuBt_2":"c40",' .
                '"colorFondoMenuBt_3":"c40",' .
                '"colorFondoMenuBt_4":"c40",' .
                '"txt_menuBtn_1":"Universidad",' .
                '"txt_menuBtn_2":"Boletines",' .
                '"txt_menuBtn_3":"Preguntas",' .
                '"txt_menuBtn_4":"Quejas",' .
                '"colorNombreApp":"rgb(255,255,255)",' .
                '"txt_menuBtn_1_color":"rgb(255,255,255)",' .
                '"txt_menuBtn_2_color":"rgb(255,255,255)",' .
                '"txt_menuBtn_3_color":"rgb(255,255,255)",' .
                '"txt_menuBtn_4_color":"rgb(255,255,255)"' .
                '}}';
    }

}
