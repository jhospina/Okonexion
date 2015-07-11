package libreria.conexion;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;

import org.json.JSONException;
import org.json.JSONObject;

import libreria.complementos.Mensaje;
import libreria.complementos.Util;
import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;
import libreria.tipos_contenido.PQR;

/**
 * Created by Jhon on 16/04/2015.
 */
public class ComunicacionPQR extends AsyncTask<String, Void, Void> {

    Activity activity;
    ProgressDialog dialog;
    Dir dir;
    String tipo;
    int layoutCarga;

    public enum Dir {enviar, recibir}

    public ComunicacionPQR(Activity activity, ProgressDialog dialog, Dir dir, String tipo, int layoutCarga) {
        this.activity = activity;
        this.dialog = dialog;
        this.dir = dir;
        this.tipo = tipo;
        this.layoutCarga = layoutCarga;
    }

    public ComunicacionPQR(Activity activity, ProgressDialog dialog, Dir dir, String tipo) {
        this.activity = activity;
        this.dialog = dialog;
        this.dir = dir;
        this.tipo = tipo;
        this.layoutCarga = 0;
    }

    public ComunicacionPQR(Activity activity) {
        this.activity = activity;
        this.dialog = null;
        this.dir = Dir.recibir;
        this.tipo = null;
        this.layoutCarga = 0;
    }

    @Override
    public void onPostExecute(Void unused) {
        if (tipo == null)
            return;

        dialog.dismiss();
        PQR pqr = new PQR(activity);

        if (layoutCarga > 0)
            pqr.cargarContenido(layoutCarga, tipo);

        if(layoutCarga==0 && Dir.enviar == this.dir) {
            activity.finish();
            Intent intent=activity.getIntent();
            intent.putExtra("msj",App.txt_info_respuesta_enviada);
            activity.startActivity(intent);
            return;
        }

        switch (tipo) {
            case PQR.TIPO_PETICION:
                if (Dir.enviar == this.dir)
                    Mensaje.alerta(activity, App.txt_info_informacion_enviada, App.txt_info_peticion_enviada, Mensaje.Icono.dialert);
                break;
            case PQR.TIPO_QUEJA:
                if (Dir.enviar == this.dir)
                    Mensaje.alerta(activity, App.txt_info_informacion_enviada, App.txt_info_queja_enviada, Mensaje.Icono.dialert);
                break;
            case PQR.TIPO_RECLAMO:
                if (Dir.enviar == this.dir)
                    Mensaje.alerta(activity, App.txt_info_informacion_enviada, App.txt_info_reclamo_enviada, Mensaje.Icono.dialert);
                break;
            case PQR.TIPO_SUGERENCIA:
                if (Dir.enviar == this.dir)
                    Mensaje.alerta(activity, App.txt_info_informacion_enviada, App.txt_info_sugerencia_enviada, Mensaje.Icono.dialert);
                break;
        }


    }


    @Override
    protected Void doInBackground(String... params) {

        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getWritableDatabase();

        if (Dir.enviar == this.dir)
            enviarPqr(params, db);
        if (Dir.recibir == this.dir)
            recibirPqrs(db);

        return null;
    }

    private void recibirPqrs(SQLiteDatabase db) {
        String dispositivo = App.obtenerIdDispositivo(activity);

        Cursor pqrs = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_pqr + " WHERE id_padre=0 ORDER BY id DESC ", null);

        String[][] datos = new String[pqrs.getCount()][2];

        if (pqrs.moveToFirst()) {
            do {
                datos[pqrs.getPosition()][0] = "id_pqr" + pqrs.getString(pqrs.getColumnIndex("id_pqr"));
                datos[pqrs.getPosition()][1] = pqrs.getString(pqrs.getColumnIndex("id_pqr"));
            } while (pqrs.moveToNext());
        }

        //Prepara los datos para enviar al servidor
        JSONObject respuesta = Conexion.conectar(App.URL_RECIBIR_PQR, datos);

        for (int i = 0; i < datos.length; i++) {
            int id_pqr = Integer.parseInt(datos[i][1]);
            try {
                if (respuesta.getString("id_pqr" + id_pqr) != "null"){

                    JSONObject datosPqr=respuesta.getJSONObject("id_pqr" + id_pqr);

                    for(int f=0;f<datosPqr.length()/4;f++) {

                        int id = datosPqr.getInt("id"+f);
                        String usuario = datosPqr.getString("usuario"+f);
                        String mensaje = datosPqr.getString("mensaje"+f);
                        String fecha = (datosPqr.getJSONObject("fecha"+f)).getString("date");

                        if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_pqr, "id_pqr", String.valueOf(id))) {
                            db.execSQL("INSERT INTO " + ControladorBaseDatos.tabla_pqr + " (id_pqr,id_padre,usuario,nombre,email,asunto,descripcion,tipo,fecha) VALUES (" + id + "," + id_pqr + ",'" + usuario + "',null,null,null,'" + Util.preparar(mensaje) + "',null,'" + fecha + "')");
                        }
                    }
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }

    }

    //Envia la peticion del usuario al servidor
    private void enviarPqr(String[] params, SQLiteDatabase db) {

        String dispositivo = App.obtenerIdDispositivo(activity);
        String nombre = params[0];
        String email = params[1];
        String asunto = params[2];
        String descripcion = params[3];
        String id_padre = params[4];

        //Prepara los datos para enviar al servidor, como peticion envia la keyApp de la aplicacion
        String[][] datos = new String[8][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;
        datos[1][0] = "dispositivo";
        datos[1][1] = dispositivo;
        datos[2][0] = "nombre";
        datos[2][1] = nombre;
        datos[3][0] = "email";
        datos[3][1] = email;
        datos[4][0] = "asunto";
        datos[4][1] = asunto;
        datos[5][0] = "descripcion";
        datos[5][1] = descripcion;
        datos[6][0] = "tipo_pqr";
        datos[6][1] = tipo;
        datos[7][0]="id_padre";
        datos[7][1]=id_padre;

        //Envia los datos del PQR al servidor y obtiene el id del seguimiento de la peticion
        JSONObject respuesta = Conexion.conectar(App.URL_ENVIAR_PQR, datos);

        if (db != null) {
            try {
                db.execSQL("INSERT INTO " + ControladorBaseDatos.tabla_pqr + " (id_pqr,id_padre,usuario,nombre,email,asunto,descripcion,tipo,fecha) VALUES (" + respuesta.getInt("id_pqr") + ","+id_padre+",'" + dispositivo + "','" + Util.preparar(nombre) + "','" + email + "','" + Util.preparar(asunto) + "','" + Util.preparar(descripcion) + "','" + tipo + "','" + respuesta.getString("fecha") + "')");
            } catch (JSONException e) {
                e.printStackTrace();
            }

            //Cerramos la base de datos
            db.close();

        }
    }


}
