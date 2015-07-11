package libreria.conexion;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;

import org.json.JSONException;
import org.json.JSONObject;

import libreria.servicios.ServicioNoticias;
import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;
import libreria.tipos_contenido.Noticias;

/**
 * Created by Jhon on 24/03/2015.
 */
public class DescargarNoticias extends AsyncTask<Void, Void, Void> {

    ProgressDialog progress = null;
    ViewGroup viewGroup = null;
    Activity activity;
    int layout;

    public DescargarNoticias(ProgressDialog progress, Activity act, int layout) {
        this.progress = progress;
        this.activity = act;
        this.layout = layout;
    }

    public DescargarNoticias(ViewGroup viewGroup, Activity act, int layout) {
        this.viewGroup = viewGroup;
        this.activity = act;
        this.layout = layout;
    }

    public void onPreExecute() {
        if (progress != null)
            progress.show();
    }

    public void onPostExecute(Void unused) {
        Noticias noticias = new Noticias(activity);
        noticias.cargar(layout);
        if (progress != null)
            progress.dismiss();

        if (viewGroup != null)
            viewGroup.setVisibility(View.GONE);

        //Indica que la descarga ya finalizo
        App.descarga_iniciada = false;
        App.noticias_descargadas = true;

        //Servicio de descarga de noticias
    }


    /**
     * SE CONECTA AL SERVIDOR PARA OBTENER DE LAS NOTICIAS
     * *
     */

    protected Void doInBackground(Void... params) {


        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getWritableDatabase();

        if (!App.noticias_descargadas)
            ControladorBaseDatos.vaciarTabla(db, ControladorBaseDatos.tabla_noticias);

        //Prepara los datos para enviar al servidor, como peticion envia la keyApp de la aplicacion
        String[][] datos = new String[2][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;
        datos[1][0] = "cant_om";
        datos[1][1] = String.valueOf(App.noticias_cargadas);

        //Se conecta al servidor para obtener los datos de las noticias
        JSONObject noticias = Conexion.conectar(App.URL_DESCARGAR_NOTICIAS, datos);

        if (noticias == null)
            return null;

        try {
            if (db != null) {
                //Ingresa los datos de las noticias localmente si no existen
                for (int i = 0; i < noticias.length() / 5; i++) {
                    JSONObject fecha = noticias.getJSONObject("fecha" + (i + 1));
                    if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_noticias, "id_noticia", noticias.getString("id" + (i + 1))))
                        db.execSQL("INSERT INTO " + ControladorBaseDatos.tabla_noticias + " (id_noticia,titulo,descripcion,imagen,fecha) VALUES (" + noticias.getString("id" + (i + 1)) + ",'" + noticias.getString("titulo" + (i + 1)) + "','" + noticias.getString("descripcion" + (i + 1)).replaceAll("'", "\"") + "','" + noticias.getString("imagen" + (i + 1)) + "','" + fecha.getString("date") + "')");
                }
                //Cerramos la base de datos
                db.close();
            }


        } catch (JSONException e) {
            e.printStackTrace();
        }

        return null;
    }


}
