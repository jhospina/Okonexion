package libreria.conexion;

import android.app.Activity;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
import android.view.ViewGroup;

import org.json.JSONException;
import org.json.JSONObject;

import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;
import libreria.tipos_contenido.Encuesta;

/**
 * Created by Jhon on 31/03/2015.
 */
public class DescargarEncuesta extends AsyncTask<String, Void, Void> {

    Activity activity;
    ViewGroup indicadorCarga;
    int layout;
    Tipo tipo_descarga;


    public enum Tipo {vigente, historial}


    public DescargarEncuesta(Activity activity, int indicadorCarga, int layout, Tipo tipo_descarga) {
        this.activity = activity;
        this.indicadorCarga = (ViewGroup) activity.findViewById(indicadorCarga);
        this.layout = layout;
        this.tipo_descarga = tipo_descarga;
    }

    public DescargarEncuesta(Activity activity, ViewGroup indicadorCarga, int layout, Tipo tipo_descarga) {
        this.activity = activity;
        this.indicadorCarga = indicadorCarga;
        this.layout = layout;
        this.tipo_descarga = tipo_descarga;
    }

    @Override
    protected Void doInBackground(String... params) {

        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getWritableDatabase();

        //Determina el tipo de descarga
        switch (tipo_descarga) {
            case vigente:
                descargar_encuesta_vigente(db);
                break;
            case historial:
                descargar_encuestas_archivadas(db);
                break;
        }

        //Cerramos la base de datos
        db.close();

        return null;
    }


    public void onPreExecute() {

    }

    public void onPostExecute(Void unused) {

        if(indicadorCarga!=null)
        ((ViewGroup)indicadorCarga.getParent()).removeView(indicadorCarga);

        Encuesta encuesta = new Encuesta(activity);
        if (tipo_descarga == Tipo.vigente)
            encuesta.cargarEncuestaVigente(layout);


        if (tipo_descarga == Tipo.historial)
            encuesta.cargarEncuestasArchivadas(layout);

        App.descarga_iniciada = false;

    }


    private Void descargar_encuesta_vigente(SQLiteDatabase db) {

        //Prepara los datos para enviar al servidor, como peticion envia la keyApp de la aplicacion
        String[][] datos = new String[1][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;


        //Se conecta al servidor para obtener los datos de las encuestas
        JSONObject encuesta = Conexion.conectar(App.URL_DESCARGAR_ENCUESTA_VIGENTE, datos);

        if (encuesta == null) {
            //Establece en estado "Archivado" cualquiera todas las encuestas
            db.execSQL("UPDATE " + ControladorBaseDatos.tabla_encuestas + " SET estado='" + Encuesta.ESTADO_ARCHIVADO + "'");
            return null;
        }


        try {
            if (db != null) {
                JSONObject fecha = encuesta.getJSONObject("fecha");
                JSONObject respuestas = encuesta.getJSONObject("resp");
                //Si no existe la encuesta en la base de datos
                if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_encuestas, "id_encuesta", encuesta.getString("id"))) {

                    //Establece en estado "Archivado" cualquiera todas las encuestas
                    db.execSQL("UPDATE " + ControladorBaseDatos.tabla_encuestas + " SET estado='" + Encuesta.ESTADO_ARCHIVADO + "'");

                    //Ingresa la encuesta vigente
                    db.execSQL("INSERT INTO " + ControladorBaseDatos.tabla_encuestas + " (id_encuesta,titulo,descripcion,fecha,estado) VALUES (" + encuesta.getInt("id") + ",'" + encuesta.getString("titulo") + "','" + encuesta.getString("descripcion").replaceAll("'", "\"") + "','" + fecha.getString("date") + "','" + Encuesta.ESTADO_VIGENTE + "')");
                }

                //INSERTA O ACTUALIZA LAS RESPUESTAS DE LA ENCUESTA
                for (int i = 0; i < (respuestas.length() / Encuesta.NUMERO_PARAMETROS_RESPUESTA); i++) {

                    int num = i + 1;

                    if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_encuestas_respuestas, "id_respuesta", respuestas.getString("id" + num))) {
                        String sql = "INSERT INTO " + ControladorBaseDatos.tabla_encuestas_respuestas +
                                " (id_respuesta,id_encuesta,nombre,total,estado) VALUES (" + respuestas.getInt("id" + num) + "," + encuesta.getInt("id") + ",'" +
                                respuestas.getString("resp" + num).replaceAll("'", "\"") + "'," + respuestas.getInt("total_resp" + num) + "," + Encuesta.ESTADO_RESPUESTA_NO_CONTESTADO + ")";
                        db.execSQL(sql);
                    } else {
                        db.execSQL("UPDATE " + ControladorBaseDatos.tabla_encuestas_respuestas + " SET total='" + respuestas.getInt("total_resp" + num) + "' WHERE id_respuesta='" + respuestas.getInt("id" + num) + "'");
                    }
                }

            }

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }


    private Void descargar_encuestas_archivadas(SQLiteDatabase db) {

        //Prepara los datos para enviar al servidor, como peticion envia la keyApp de la aplicacion
        String[][] datos = new String[2][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;
        datos[1][0] = "cant_om";
        datos[1][1] = String.valueOf(App.encuestas_cargadas);

        //Se conecta al servidor para obtener los datos de las noticias
        JSONObject jsonEncuestas = Conexion.conectar(App.URL_DESCARGAR_ENCUESTAS_ARCHIVADAS, datos);

        if (jsonEncuestas == null)
            return null;


        for (int i = 0; i < jsonEncuestas.length(); i++) {
            try {
                //OBtiene el JSON de una encuesta individual del conjunto
                JSONObject encuesta = jsonEncuestas.getJSONObject(Encuesta.tipoDato + "" + i);
                //Obtiene los JSon de datos integrados dentro de una encuesta
                JSONObject fecha = encuesta.getJSONObject("fecha");
                JSONObject respuestas = encuesta.getJSONObject("resp");

                //Si no existe la encuesta en la base de datos
                if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_encuestas, "id_encuesta", encuesta.getString("id"))) {
                    //Ingresa la encuesta vigente
                    db.execSQL("INSERT INTO " + ControladorBaseDatos.tabla_encuestas + " (id_encuesta,titulo,descripcion,fecha,estado) VALUES (" + encuesta.getInt("id") + ",'" + encuesta.getString("titulo") + "','" + encuesta.getString("descripcion").replaceAll("'", "\"") + "','" + fecha.getString("date") + "','" + Encuesta.ESTADO_ARCHIVADO + "')");
                }


                for (int f = 0; f < (respuestas.length() / Encuesta.NUMERO_PARAMETROS_RESPUESTA); f++) {
                    int num = f + 1;
                    if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_encuestas_respuestas, "id_respuesta", respuestas.getString("id" + num))) {
                        String sql = "INSERT INTO " + ControladorBaseDatos.tabla_encuestas_respuestas +
                                " (id_respuesta,id_encuesta,nombre,total,estado) VALUES (" + respuestas.getInt("id" + num) + "," + encuesta.getInt("id") + ",'" +
                                respuestas.getString("resp" + num).replaceAll("'", "\"") + "'," + respuestas.getInt("total_resp" + num) + "," + Encuesta.ESTADO_RESPUESTA_NO_CONTESTADO + ")";
                        db.execSQL(sql);
                    }else{
                        db.execSQL("UPDATE " + ControladorBaseDatos.tabla_encuestas_respuestas + " SET total='" + respuestas.getInt("total_resp" + num) + "' WHERE id_respuesta='" + respuestas.getInt("id" + num) + "'");
                    }
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }


        return null;
    }

}
