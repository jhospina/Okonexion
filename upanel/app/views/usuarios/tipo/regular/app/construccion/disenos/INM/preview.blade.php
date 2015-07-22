<?php
$urlImagen1 = URL::to("assets/img/desing/INM/mipmap-mdpi/img_menu_btn_1.png");
$urlImagen2 = URL::to("assets/img/desing/INM/mipmap-mdpi/img_menu_btn_2.png");
$urlImagen3 = URL::to("assets/img/desing/INM/mipmap-mdpi/img_menu_btn_3.png");
$urlImagen4 = URL::to("assets/img/desing/INM/mipmap-mdpi/img_menu_btn_4.png");
?>

<div id="barra-INM">
    <span id="logoApp-INM"><img src="{{URL::to('assets/img/icons/logoApp_preview.png')}}"/></span><span id="nombreApp-INM">{{$app->nombre}}</span>
</div>
<div id="content-menu-INM"> 
    <div id="menu1-INM" class="btn-menu">
        <img src="{{$urlImagen1}}"/>
        <div id="nombreBtnMenu1-INM"></div>
    </div>
    <div id="menu2-INM" class="btn-menu">
        <img src="{{$urlImagen2}}"/>
        <div id="nombreBtnMenu2-INM"></div>
    </div>
    <div id="menu3-INM" class="btn-menu">
        <img src="{{$urlImagen3}}"/>
        <div id="nombreBtnMenu3-INM"></div>
    </div>
    <div id="menu4-INM" class="btn-menu">
        <img src="{{$urlImagen4}}"/>
        <div id="nombreBtnMenu4-INM"></div>
    </div>
</div>


<script>

    var urlImagenBtnMenu1 = "{{$urlImagen1}}";
    var urlImagenBtnMenu2 = "{{$urlImagen2}}";
    var urlImagenBtnMenu3 = "{{$urlImagen3}}";
    var urlImagenBtnMenu4 = "{{$urlImagen4}}";

</script>