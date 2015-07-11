package libreria.conexion;

import android.app.Activity;
import android.app.ProgressDialog;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
import android.view.View;
import android.view.ViewGroup;

import org.json.JSONException;
import org.json.JSONObject;

import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;
import libreria.tipos_contenido.Institucional;

/**
 * Created by Jhon on 30/03/2015.
 */
public class DescargarInstitucional extends AsyncTask<Void, Void, Void> {

    ProgressDialog progress = null;
    ViewGroup viewGroup = null;
    Activity activity;
    int layout;

    public DescargarInstitucional(ProgressDialog progress, Activity act, int layout) {
        this.progress = progress;
        this.activity = act;
        this.layout = layout;
    }

    public DescargarInstitucional(ViewGroup viewGroup, Activity act, int layout) {
        this.viewGroup = viewGroup;
        this.activity = act;
        this.layout = layout;
    }

    public void onPreExecute() {
        if (progress != null)
            progress.show();
    }

    public void onPostExecute(Void unused) {
        Institucional inst = new Institucional(activity);
        inst.cargar(layout);
        if (progress != null)
            progress.dismiss();

        if (viewGroup != null)
            viewGroup.setVisibility(View.GONE);

        //Indica que la descarga ya finalizo
        App.descarga_iniciada = false;
        App.institucional_descargadas = true;
    }


    /**
     * SE CONECTA AL SERVIDOR PARA OBTENER DE LAS institucional
     * *
     */


    protected Void doInBackground(Void... params) {

        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getWritableDatabase();

        if (!App.institucional_descargadas)
            ControladorBaseDatos.vaciarTabla(db, ControladorBaseDatos.tabla_institucional);

        //Prepara los datos para enviar al servidor, como peticion envia la keyApp de la aplicacion
        String[][] datos = new String[1][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;


        //Se conecta al servidor para obtener los datos de las institucional
        JSONObject institucional = Conexion.conectar(App.URL_DESCARGAR_INSTITUCIONAL, datos);

        if (institucional == null)
            return null;

        try {
            if (db != null) {
                //Ingresa los datos de las institucional localmente si no existen
                for (int i = 0; i < institucional.length() / 4; i++) {
                    JSONObject fecha = institucional.getJSONObject("fecha" + (i + 1));
                    if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_institucional, "id_institucional", institucional.getString("id" + (i + 1))))
                        db.execSQL("INSERT INTO " + ControladorBaseDatos.tabla_institucional + " (id_institucional,titulo,descripcion,fecha) VALUES (" + institucional.getString("id" + (i + 1)) + ",'" + institucional.getString("titulo" + (i + 1)) + "','" + institucional.getString("descripcion" + (i + 1)).replaceAll("'", "\"") + "','" + fecha.getString("date") + "')");
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
