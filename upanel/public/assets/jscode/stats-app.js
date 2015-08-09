var TG_LINEAL = "lineal";
var TG_BARRAS = "barras";

var dataInstalaciones;
var dataActividad;

//**************************************************************************
// ESTADISTICAS DE INSTALACIONES *******************************************
//**************************************************************************

switch (tgInstal)
{
    //GRAFICA LINEAL
    case TG_LINEAL:
        dataInstalaciones = {
            labels: dataEjeXInstal,
            datasets: [{fillColor: "#FF6666", strokeColor: "red", pointColor: "#FF0202", pointStrokeColor: "#0B02FF", pointHighlightFill: "#0B02FF", pointHighlightStroke: "#0B02FF", data: dataRegsInstal}]
        }
        break;
        //GRAFICA BARRAS
    case TG_BARRAS:
        dataInstalaciones = {
            labels: dataEjeXInstal,
            datasets: [{fillColor: "#FF6666", strokeColor: "red", highlightFill: "#FF6666", highlightStroke: "black", data: dataRegsInstal}]
        }
        break;
}


//**************************************************************************
// ESTADISTICAS DE ACTIVIDAD *******************************************
//**************************************************************************

switch (tgAct)
{
    //GRAFICA LINEAL
    case TG_LINEAL:
        dataActividad = {
            labels: dataEjeXActividad,
            datasets: [{fillColor: "#FF6666", strokeColor: "red", pointColor: "#FF0202", pointStrokeColor: "#0B02FF", pointHighlightFill: "#0B02FF", pointHighlightStroke: "#0B02FF", data: dataRegsActividad}]
        }
        break;
        //GRAFICA BARRAS
    case TG_BARRAS:
        dataActividad = {
            labels: dataEjeXActividad,
            datasets: [{fillColor: "#FF6666", strokeColor: "red", highlightFill: "#FF6666", highlightStroke: "black", data: dataRegsActividad}]
        }
        break;
}




window.onload = function () {
    var ctx_actividad = document.getElementById("canvas-actividad").getContext("2d");
    var ctx_instalaciones = document.getElementById("canvas-instalaciones").getContext("2d");


    //**************************************************************************
    // ESTADISTICAS DE INSTALACIONES *******************************************
    //**************************************************************************


    switch (tgInstal)
    {
        case TG_LINEAL:
            window.myBar = new Chart(ctx_instalaciones).Line(dataInstalaciones, {bezierCurve: true, responsive: true});
            break;
        case TG_BARRAS:
            window.myBar = new Chart(ctx_instalaciones).Bar(dataInstalaciones, {responsive: true, barStrokeWidth: 1, scaleGridLineWidth: 1});
            break;
    }


    //**************************************************************************
    // ESTADISTICAS DE ACTIVIDAD *******************************************
    //**************************************************************************


    switch (tgAct)
    {
        case TG_LINEAL:
            window.myBar = new Chart(ctx_actividad).Line(dataActividad, {bezierCurve: true, responsive: true});
            break;
        case TG_BARRAS:
            window.myBar = new Chart(ctx_actividad).Bar(dataActividad, {responsive: true, barStrokeWidth: 1, scaleGridLineWidth: 1});
            break;
    }


}
