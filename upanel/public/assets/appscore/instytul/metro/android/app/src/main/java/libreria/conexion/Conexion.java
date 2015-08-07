package libreria.conexion;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.util.Log;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import libreria.complementos.Util;
import libreria.sistema.App;
import libreria.sistema.AppMeta;
import libreria.tipos_contenido.Noticias;

/**
 * Created by Jhon on 27/03/2015.
 */
public class Conexion {

    /**
     * Se conecta a la URL de un servidor indicado y obtiene un JSON con los datos de respuesta
     *
     * @param url         (String) URL de la conexcion
     * @param datosEnviar (String[][]) Un array dos dimensiones con los datos a enviar
     * @return (JSONObject) Obtiene un JSON con los datos
     */
    public static JSONObject conectar(String url, String[][] datosEnviar) {

        InputStream is = null;
        String result = "";
        JSONObject json = null;
        try {
            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost(url);

            if (datosEnviar != null) {
                List<NameValuePair> params = new ArrayList<NameValuePair>();
                for (int i = 0; i < datosEnviar.length; i++) {
                    params.add(new BasicNameValuePair(datosEnviar[i][0], datosEnviar[i][1]));
                }
                httppost.setEntity(new UrlEncodedFormEntity(params));
            }

            HttpResponse response = httpclient.execute(httppost);
            HttpEntity entity = response.getEntity();
            is = entity.getContent();
        } catch (Exception e) {
        }

        try {
            BufferedReader reader = new BufferedReader(new InputStreamReader(is, "iso-8859-1"), 8);
            StringBuilder sb = new StringBuilder();
            String line = null;
            while ((line = reader.readLine()) != null) {
                sb.append(line);
            }
            is.close();
            result = sb.toString();
        } catch (Exception e) {
        }

        try {
            if (result.length() > 0)
                json = new JSONObject(result);
            else
                json = null;
        } catch (JSONException e) {
        }

       //Log.e("JSON", result);

        return json;
    }


    public static Bitmap descargarImagen(String imageHttpAddress) {
        URL imageUrl = null;
        Bitmap imagen = null;
        try {
            imageUrl = new URL(imageHttpAddress);
            HttpURLConnection conn = (HttpURLConnection) imageUrl.openConnection();
            conn.connect();
            imagen = BitmapFactory.decodeStream(conn.getInputStream());
        } catch (IOException ex) {
            ex.printStackTrace();
        }

        return imagen;
    }


    /**
     * Verifica la conexion de internet de la aplicacion
     *
     * @param ctx La Actividad actual
     * @return
     */
    public static boolean verificar(Context ctx) {
        boolean bConectado = false;
        ConnectivityManager connec = (ConnectivityManager) ctx
                .getSystemService(Context.CONNECTIVITY_SERVICE);
        // No sólo wifi, también GPRS
        NetworkInfo[] redes = connec.getAllNetworkInfo();
        // este bucle debería no ser tan ñapa
        for (int i = 0; i < 2; i++) {
            // ¿Tenemos conexión? ponemos a true
            if (redes[i].getState() == NetworkInfo.State.CONNECTED) {
                bConectado = true;
            }
        }
        return bConectado;
    }


    public static void registrarActividad(Activity activity,String tipo_contenido){

        String regClave=App.obtenerIdDispositivo(activity)+"_"+tipo_contenido+"_"+Util.obtenerFechaActual("yyyyMMdd");

        if(AppMeta.findByClave(activity, regClave)!=null)
            return;

        String fecha= Util.obtenerFechaActual();;

        AppMeta meta=new AppMeta(activity);

        meta.setClave(regClave);
        meta.setValor(fecha);
        meta.save();

        String[][] datos = new String[3][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;
        datos[1][0] = "clave";
        datos[1][1] = regClave;
        datos[2][0] = "valor";
        datos[2][1] =  fecha;

        if(!Conexion.verificar(activity))
            return;

        Conexion.conectar(App.URL_META_REGISTRAR, datos);

    }


    public static void registrarMetaDato(Activity activity,String[][] meta){

        String[][] datos = new String[1+(meta.length*2)][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;

        int n=0;
        AppMeta appMeta;
        Log.e("META",meta.length+"");
        for(int i=0;i<meta.length;i++){
            appMeta=AppMeta.findByClave(activity, meta[i][0]);
            if(appMeta==null)
               appMeta=new AppMeta(activity);

            datos[n+1][0] = "clave"+i;
            datos[n+1][1] = meta[i][0];
            datos[n+2][0] = "valor"+i;
            datos[n+2][1] = meta[i][1];

            n+=2;
            appMeta.setClave(meta[i][0]);
            appMeta.setValor(meta[i][1]);
            appMeta.save();
        }

        if(!Conexion.verificar(activity))
          return;

        Conexion.conectar(App.URL_META_REGISTRAR, datos);
    }

    public static void registrarMetaDato(Activity activity,String clave,String valor){

        if(!Conexion.verificar(activity))
            return;

        String regClave=clave;

        if(AppMeta.findByClave(activity, regClave)!=null)
            return;

        AppMeta meta=new AppMeta(activity);

        meta.setClave(regClave);
        meta.setValor(valor);
        meta.save();

        String[][] datos = new String[3][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;
        datos[1][0] = "clave";
        datos[1][1] = regClave;
        datos[2][0] = "valor";
        datos[2][1] =  valor;

        Conexion.conectar(App.URL_META_REGISTRAR, datos);

    }

}
