package libreria.sistema;

import android.app.Activity;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable;
import android.provider.Settings;
import android.support.v7.app.ActionBarActivity;
import android.text.Html;

import com.appsthergo.instytul.metro.appsthergoappname.R;

import libreria.complementos.Util;
import libreria.extensiones.ComponenteInterfaz;

/**
 * Created by Jhon on 24/03/2015.
 */
public class App extends AppConfig {
    /**
     * *******************************************************
     * URLS DE DESCARGAR DE INFORMACION (NO MODIFICAR)
     * ******************************************************
     */

    public static final String URL_DESCARGAR_NOTICIAS = "https://appsthergo.com/upanel/public/app/descargar/noticias";
    public static final String URL_DESCARGAR_INSTITUCIONAL = "https://appsthergo.com/upanel/public/app/descargar/institucional";
    public static final String URL_DESCARGAR_ENCUESTA_VIGENTE="https://appsthergo.com/upanel/public/app/descargar/encuestas/vigente";
    public static final String URL_DESCARGAR_ENCUESTAS_ARCHIVADAS="https://appsthergo.com/upanel/public/app/descargar/encuestas/archivadas";
    public static final String URL_ENVIAR_ENCUESTA_RESPUESTA="https://appsthergo.com/upanel/public/app/enviar/encuestas/respuesta";
    public static final String URL_ENVIAR_PQR="https://appsthergo.com/upanel/public/app/enviar/pqr";
    public static final String URL_RECIBIR_PQR="https://appsthergo.com/upanel/public/app/recibir/pqr";
    public static final String URL_META_REGISTRAR="https://appsthergo.com/upanel/public/app/enviar/meta/registrar";

    /**
     * *******************************************************
     * VARIABLES DE CONTROL (NO MODIFICAR)
     * ******************************************************
     */

    //****NOTICIAS*****//
    public static boolean noticias_descargadas = false; //Indica si las noticias ya fueron descargadas
    public static int noticias_cantidad_a_cargar = 16;//Indica el numero de noticias a cargar
    public static int noticias_cargadas = 0;
    //****INSTITUCIONAL*****//
    public static boolean institucional_descargadas = false; //Indica si la informacion institucional ya fueron descargadas
    public static int institucional_cargadas = 0;
    //****ENCUESTAS*****//
    public static boolean encuestas_descargadas = false; //Indica si las encuesta archivadas ya fueron descargadas
    public static int encuestas_cantidad_a_cargar = 8;//Indica el numero de encuestas archivadas a cargar
    public static int encuestas_cargadas = 0;

    public static boolean descarga_iniciada = false;//Indica si existe un descarga iniciada


    /**
     * *******************************************************
     * VARIABLES DE DEFINCION DE DATOS (NO MODIFICAR)
     * ******************************************************
     */

    public static final String CONFIG_MOSTRAR_NOMBRE_SOLOTEXTO = "soloTexto";
    public static final String CONFIG_MOSTRAR_NOMBRE_TEXTOLOGO = "textoLogo";
    public static final String CONFIG_MOSTRAR_NOMBRE_SOLOLOGO = "soloLogo";
    public static final int W_IMAGEN_NOTICIA = 621;
    public static final int H_IMAGEN_NOTICIA = 483;
    public static final int FACTOR_ACERCAMIENTO_SCROLL = 15; //Indica el factor de acercamiento para cargar mas contenido al bajar el scroll

    public static int id_mensaje_no_hay_contenido = 7738283;
    public static int id_base_respuestas_encuesta=999929;



    /**
     * Establece la configuraciòn de apariencia dada por la configuracion de las variables de la App
     *
     * @param activity
     */
    public static void establecerBarraAccion(ActionBarActivity activity, String titulo) {


        //*************************************************************
        // BARRA DE ACCIÒN DEL A APLICACION***************************
        //*************************************************************

        //Establece el color de fondo de la barra App
        activity.getSupportActionBar().setBackgroundDrawable((new ComponenteInterfaz(activity)).crear_Gradient(GradientDrawable.Orientation.TOP_BOTTOM,App.colorBarraApp, Util.oscurecerColor(App.colorBarraApp,30)));
        //Establece el color del texto de la barra
        activity.getSupportActionBar().setTitle(Html.fromHtml("<font color='" + App.colorNombreApp + "'>" + App.nombreApp + "</font>"));

        /**NOTA: Alineación del texto y el logo de barra aun no soportado**/


        //Indica que mostrar en la barra de accion
        if (App.mostrarNombre == App.CONFIG_MOSTRAR_NOMBRE_SOLOTEXTO) {
            activity.getSupportActionBar().setDisplayUseLogoEnabled(false);
            activity.getSupportActionBar().setDisplayShowHomeEnabled(false);
            if (titulo != null) {
                activity.getSupportActionBar().setSubtitle(Html.fromHtml("<font color='" + App.colorNombreApp + "'>" + titulo + "</font>"));
            }
        }

        if (App.mostrarNombre == App.CONFIG_MOSTRAR_NOMBRE_TEXTOLOGO) {
            activity.getSupportActionBar().setLogo(R.mipmap.ic_launcher);
            activity.getSupportActionBar().setDisplayUseLogoEnabled(true);
            activity.getSupportActionBar().setDisplayShowHomeEnabled(true);
            activity.getSupportActionBar().setDisplayShowTitleEnabled(true);
            if (titulo != null) {
                activity.getSupportActionBar().setSubtitle(Html.fromHtml("<font color='" + App.colorNombreApp + "'>" + titulo + "</font>"));
            }
        }

        if (App.mostrarNombre == App.CONFIG_MOSTRAR_NOMBRE_SOLOLOGO) {
            activity.getSupportActionBar().setLogo(R.mipmap.ic_launcher);
            activity.getSupportActionBar().setDisplayUseLogoEnabled(true);
            activity.getSupportActionBar().setDisplayShowHomeEnabled(true);
            activity.getSupportActionBar().setDisplayShowTitleEnabled(false);
        }


    }

    /** Obtiene un String con el ID del dispotivo actual
     *
     * @param activity Actividad del contexto
     * @return Id
     */
    public static String obtenerIdDispositivo(Activity activity){
        String android_id = Settings.Secure.getString(activity.getContentResolver(),
                Settings.Secure.ANDROID_ID);
        return android_id;
    }


}
