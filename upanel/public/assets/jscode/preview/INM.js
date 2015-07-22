var id = "INM";

$(document).ready(function () {
    actualizarPreview();
});


function actualizarPreview() {
    setTimeout("actualizarPreview()", 1000);

    //Barra de aplicacion
    $("#barra-" + id).css("background", $("#colorBarraApp").val());
    $("#content-menu-" + id).css("background", $("#colorBarraApp").val());
    //Color nombreApp
    $("#nombreApp-" + id).css("color", $("#colorNombreApp").val());
    //Mostrar nombre
    switch ($('input:radio[name=mostrarNombre]:checked').val()) {
        case "soloTexto":
            $("#logoApp-" + id).hide();
            $("#nombreApp-" + id).show();
            break;
        case "textoLogo":
            $("#logoApp-" + id).show();
            $("#nombreApp-" + id).show();
            break;
        case "soloLogo":
            $("#logoApp-" + id).show();
            $("#nombreApp-" + id).hide();
            break;
    }
    //LOGO APP
    $("#logoApp-" + id + " img").attr("src", $("#logoApp-content .file-preview-image").attr("src"));

    //Textos del Menu
    $("#nombreBtnMenu1-" + id).html($("#txt_menuBtn_1").val());
    $("#nombreBtnMenu2-" + id).html($("#txt_menuBtn_2").val());
    $("#nombreBtnMenu3-" + id).html($("#txt_menuBtn_3").val());
    $("#nombreBtnMenu4-" + id).html($("#txt_menuBtn_4").val());
    //Colores de fondo
    $("#menu1-INM").css("background", $("#colorFondoMenuBt_1").val());
    $("#menu2-INM").css("background", $("#colorFondoMenuBt_2").val());
    $("#menu3-INM").css("background", $("#colorFondoMenuBt_3").val());
    $("#menu4-INM").css("background", $("#colorFondoMenuBt_4").val());
    //Colores del texto del menu
    $("#nombreBtnMenu1-INM").css("color", $("#txt_menuBtn_1_color").val())
    $("#nombreBtnMenu2-INM").css("color", $("#txt_menuBtn_2_color").val())
    $("#nombreBtnMenu3-INM").css("color", $("#txt_menuBtn_3_color").val())
    $("#nombreBtnMenu4-INM").css("color", $("#txt_menuBtn_4_color").val())


    if ($("#iconoMenu1-content .file-preview-image").length)
        $("#menu1-INM img").attr("src", $("#iconoMenu1-content .file-preview-image").attr("src"));
    else
        $("#menu1-INM img").attr("src", urlImagenBtnMenu1);

    //Imagen Menu 1
    if ($("#iconoMenu1-content .file-preview-image").length)
        $("#menu1-INM img").attr("src", $("#iconoMenu1-content .file-preview-image").attr("src"));
    else
        $("#menu1-INM img").attr("src", urlImagenBtnMenu1);

    //Imagen Menu 2
    if ($("#iconoMenu2-content .file-preview-image").length)
        $("#menu2-INM img").attr("src", $("#iconoMenu2-content .file-preview-image").attr("src"));
    else
        $("#menu2-INM img").attr("src", urlImagenBtnMenu2);

    //Imagen Menu 3
    if ($("#iconoMenu3-content .file-preview-image").length)
        $("#menu3-INM img").attr("src", $("#iconoMenu3-content .file-preview-image").attr("src"));
    else
        $("#menu3-INM img").attr("src", urlImagenBtnMenu3);

    //Imagen Menu 4
    if ($("#iconoMenu4-content .file-preview-image").length)
        $("#menu4-INM img").attr("src", $("#iconoMenu4-content .file-preview-image").attr("src"));
    else
        $("#menu4-INM img").attr("src", urlImagenBtnMenu4);
}