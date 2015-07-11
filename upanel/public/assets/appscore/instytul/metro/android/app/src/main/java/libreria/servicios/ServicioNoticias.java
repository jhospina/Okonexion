package libreria.servicios;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.ContentValues;
import android.content.Intent;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.drawable.BitmapDrawable;
import android.os.IBinder;
import android.support.v4.app.NotificationCompat;
import android.text.Html;
import android.util.Log;

import com.appsthergo.instytul.metro.nombreApp.R;
import com.appsthergo.instytul.metro.nombreApp.VerNoticiaActivity;

import org.json.JSONException;
import org.json.JSONObject;

import libreria.complementos.Util;
import libreria.conexion.Conexion;
import libreria.sistema.App;
import libreria.sistema.AppConfig;
import libreria.sistema.ControladorBaseDatos;

public class ServicioNoticias extends Service {

    @Override
    public void onCreate() {
        super.onCreate();
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {

        new Thread(new Runnable() {
            public void run() {
                // TODO Auto-generated method stub
                while (true) {
                    try {
                        Thread.sleep(1000 * 60);
                        if (!Conexion.verificar(getApplicationContext()))
                            continue;

                        //Descarga las noticias necesarias
                        ControladorBaseDatos dbc = new ControladorBaseDatos(getApplicationContext(), ControladorBaseDatos.nombreDB, null, 1);
                        SQLiteDatabase db = dbc.getWritableDatabase();
                        int cantidadNoticias = (db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_noticias, null)).getCount();

                        //Prepara los datos para enviar al servidor, como peticion envia la keyApp de la aplicacion
                        String[][] datos = new String[2][2];
                        datos[0][0] = "key_app";
                        datos[0][1] = App.keyApp;
                        datos[1][0] = "cant_om";
                        datos[1][1] = String.valueOf(cantidadNoticias);

                        //Se conecta al servidor para obtener los datos de las noticias
                        JSONObject noticias = Conexion.conectar(App.URL_DESCARGAR_NOTICIAS, datos);

                        if (noticias == null) {
                            continue;
                        }

                        try {
                            if (db != null) {
                                //Ingresa los datos de las noticias localmente si no existen
                                for (int i = 0; i < noticias.length() / 5; i++) {
                                    JSONObject fecha = noticias.getJSONObject("fecha" + (i + 1));
                                    if (!ControladorBaseDatos.existeRegistro(db, ControladorBaseDatos.tabla_noticias, "id_noticia", noticias.getString("id" + (i + 1)))) {

                                        String id = noticias.getString("id" + (i + 1));
                                        String titulo = noticias.getString("titulo" + (i + 1));
                                        String descripcion = noticias.getString("descripcion" + (i + 1)).replaceAll("'", "\"");

                                        ContentValues valores = new ContentValues();
                                        valores.put("id_noticia", id);
                                        valores.put("titulo", titulo);
                                        valores.put("descripcion", descripcion);
                                        valores.put("imagen", noticias.getString("imagen" + (i + 1)));
                                        valores.put("fecha", fecha.getString("date"));

                                        int id_noticia = (int) db.insert(ControladorBaseDatos.tabla_noticias, null, valores);

                                        //*************************************************************************
                                        //Genera una notificacion en la barra de estado del sistema operativo
                                        //*************************************************************************
                                        Intent showFullQuoteIntent = new Intent(getApplicationContext(), VerNoticiaActivity.class);

                                        showFullQuoteIntent.putExtra("id_noticia", id_noticia);
                                        int uniqueInt = (int) (System.currentTimeMillis() & 0xfffffff);
                                        PendingIntent pendingIntent = PendingIntent.getActivity(getApplicationContext(), uniqueInt, showFullQuoteIntent, PendingIntent.FLAG_UPDATE_CURRENT);

                                        Notification notification = new NotificationCompat.Builder(getApplicationContext())
                                                .setSmallIcon(R.mipmap.img_menu_btn_2)
                                                .setLargeIcon((((BitmapDrawable) getResources()
                                                        .getDrawable(R.mipmap.ic_launcher)).getBitmap()))
                                                .setContentTitle(titulo)
                                                .setContentText(Util.recortarTexto(Html.fromHtml(descripcion).toString(), 20))
                                                .setContentInfo(AppConfig.txt_menuBtn_2)
                                                .setTicker(titulo)
                                                .setContentIntent(pendingIntent)
                                                .setAutoCancel(true)
                                                .build();

                                        NotificationManager notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
                                        notificationManager.notify(0, notification);
                                    }
                                }
                                //Cerramos la base de datos
                                db.close();
                            }


                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    } catch (InterruptedException e) {
                        e.printStackTrace();
                    }
                }

            }
        }).start();

        return super.onStartCommand(intent, flags, startId);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
    }

    @Override
    public IBinder onBind(Intent intent) {
        throw new UnsupportedOperationException("Not yet implemented");
    }

}
