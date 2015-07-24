package libreria.tipos_contenido;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.Point;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable;
import android.os.AsyncTask;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.appsthergo.instytul.metro.appsthergoappname.VerEncuestaActivity;

import org.json.JSONObject;

import java.util.ArrayList;

import libreria.complementos.Util;
import libreria.conexion.Conexion;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;

/**
 * Created by Jhon on 31/03/2015.
 */
public class Encuesta extends TipoContenido {

    public static final String ESTADO_VIGENTE = "vigente";
    public static final String ESTADO_ARCHIVADO = "archivado";
    public static final int NUMERO_PARAMETROS_RESPUESTA = 3;
    public static final int ESTADO_RESPUESTA_NO_CONTESTADO = 0;
    public static final int ESTADO_RESPUESTA_CONTESTADO = 1;
    public static final int ESTADO_RESPUESTA_CONTESTADO_Y_ENVIADO = 2;
    public static final String iden = "encuestas";

    public static int respuesta_seleccionada = 0;


    public Encuesta(Activity activity) {
        super(activity);
    }


    public Cursor obtenerEncuestaVigente(SQLiteDatabase db) {
        Cursor encuesta = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_encuestas + " WHERE estado='" + Encuesta.ESTADO_VIGENTE + "' ORDER BY id DESC LIMIT 1", null);
        return encuesta;
    }

    public Cursor obtenerRespuesta(SQLiteDatabase db, int id_encuesta) {
        //Consulta las respuestas de la encuesta
        Cursor respuestas = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_encuestas_respuestas + " WHERE id_encuesta='" + id_encuesta + "' ORDER BY id ASC", null);
        return respuestas;
    }

    /**
     * Obtiene el total de votos de una encuesta
     *
     * @param db
     * @param id_encuesta
     * @return
     */
    public int totalVotos(SQLiteDatabase db, int id_encuesta) {
        Cursor respuestas = obtenerRespuesta(db, id_encuesta);
        int total = 0;
        if (respuestas.moveToFirst()) {

            do {
                total += respuestas.getInt(respuestas.getColumnIndex("total"));
            } while (respuestas.moveToNext());
        }
        return total;
    }


    /**
     * Verifica si el usuario ya ha contestado una encuesta
     *
     * @param db
     * @param id_encuesta
     * @return
     */
    public boolean verificarRespuestaUsuario(SQLiteDatabase db, int id_encuesta) {
        Cursor resps = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_encuestas_respuestas + " WHERE id_encuesta='" + id_encuesta + "' and estado>" + Encuesta.ESTADO_RESPUESTA_NO_CONTESTADO + " ORDER BY id ASC", null);
        return resps.moveToFirst();
    }

    public void cargarEncuestaVigente(final int layout) {

        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();
        final ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);

        final ViewGroup contenedor_principal = (ViewGroup) activity.findViewById(layout);

        Cursor encuesta = obtenerEncuestaVigente(db);

        verificarRepresamiento(db);

        if (encuesta.moveToFirst()) {

            final int id_encuesta = encuesta.getInt(1);

            if (verificarRespuestaUsuario(db, id_encuesta))  //SI EL USUARIO YA CONTESTO LA ENCUESTA
            {
                cargarResultados(layout, id_encuesta, App.txt_info_encabezado_encuesta_vigente);
            } else {
                /*SI EL USUARIO NO HA CONTESTADO LA ENCUESTA*/


                String titulo = encuesta.getString(2);
                String descripcion = encuesta.getString(3);

                /**ENCABEZADO DE SECCION**/

                LinearLayout layout_header = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.VERTICAL);
                layout_header.setPadding(10, 10, 10, 10);
                TextView txt_encabezado = interfaz.crear_TextView(App.txt_info_encabezado_responde_encuesta, App.colorFondoMenuBt_3, 20, Typeface.BOLD);
                txt_encabezado.setGravity(Gravity.CENTER);
                layout_header.addView(txt_encabezado, interfaz.parentContentAlign(Gravity.CENTER));

                contenedor_principal.addView(layout_header, interfaz.parentContentAlign(Gravity.CENTER));

                /***********************************************/

                LinearLayout contenedor_encuesta_vigente = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.VERTICAL);
                contenedor_principal.addView(contenedor_encuesta_vigente, interfaz.parentContent());


                TextView txt_titulo = interfaz.crear_TextView(titulo, App.txt_menuBtn_3_color, 18, Typeface.BOLD);

                contenedor_encuesta_vigente.addView(txt_titulo, interfaz.parentContent());
                interfaz.establecerMargenes(txt_titulo, 16, 16, 16, 5);
                //Descripcion de la encuesta
                if (descripcion.length() > 0) {
                    TextView txt_descripcion = interfaz.crear_TextView(descripcion, App.txt_menuBtn_3_color, 14);
                    txt_descripcion.setPadding(0, 0, 0, 5);
                    contenedor_encuesta_vigente.addView(txt_descripcion, interfaz.parentContent());
                    interfaz.establecerMargenes(txt_descripcion, 5, 5, 5, 5);
                }

                LinearLayout layout_msj_resultados = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.HORIZONTAL);
                layout_msj_resultados.setPadding(0, 10, 0, 0);
                TextView txt_mensaje_resultados = interfaz.crear_TextView(App.txt_info_selecciona_respuesta, App.colorFondoMenuBt_3, 14);
                txt_mensaje_resultados.setPadding(5, 5, 5, 5);
                txt_mensaje_resultados.setGravity(Gravity.RIGHT);
                txt_mensaje_resultados.setBackground(new ColorDrawable(Color.parseColor(App.txt_menuBtn_3_color)));

                layout_msj_resultados.addView(txt_mensaje_resultados, interfaz.parentContentAlign(Gravity.RIGHT));
                contenedor_encuesta_vigente.addView(layout_msj_resultados, interfaz.contentContentAlign(Gravity.RIGHT));

                Cursor respuestas = obtenerRespuesta(db, id_encuesta);

                final ArrayList<LinearLayout> respuestasLayouts = new ArrayList<LinearLayout>();
                final ArrayList<TextView> txt_respuestas = new ArrayList<TextView>();

                //Carga en pantalla las respuestas posibles de la encuesta
                if (respuestas.moveToFirst()) {

                    Point pantalla = Util.obtenerTamanoPantalla(activity);
                    int n = 0;
                    LinearLayout fila = null;
                    LinearLayout contenedor_respuestas = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.VERTICAL);
                    contenedor_respuestas.setPadding(0, 0, 0, 5);
                    do {

                        n++;
                        String nombre = respuestas.getString(3);

                        if (n % 2 != 0) {
                            fila = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.HORIZONTAL);
                            fila.setPadding(0, 5, 0, 0);
                        }

                        LinearLayout layout_respuesta = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.VERTICAL);
                        layout_respuesta.setGravity(Gravity.CENTER);
                        layout_respuesta.setMinimumHeight(100);
                        layout_respuesta.setId(App.id_base_respuestas_encuesta + n);
                        TextView nombre_resp = interfaz.crear_TextView(nombre, App.txt_menuBtn_3_color, 16);
                        layout_respuesta.addView(nombre_resp, interfaz.parentContentAlign(Gravity.CENTER));

                        txt_respuestas.add(nombre_resp);

                        layout_respuesta.setPadding(5, 5, 5, 5);


                        if (respuestas.getCount() % 2 != 0 && respuestas.isLast()) {
                            fila.addView(layout_respuesta, new LinearLayout.LayoutParams(pantalla.x, LinearLayout.LayoutParams.MATCH_PARENT, Gravity.CENTER));
                        } else
                            fila.addView(layout_respuesta, new LinearLayout.LayoutParams(pantalla.x / 2, LinearLayout.LayoutParams.MATCH_PARENT, Gravity.CENTER));

                        interfaz.establecerMargenes(layout_respuesta, 0, 0, 5, 0);


                        if (n % 2 != 0)
                            contenedor_respuestas.addView(fila, interfaz.parentContent());


                        layout_respuesta.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                Encuesta.respuesta_seleccionada = v.getId() - App.id_base_respuestas_encuesta;

                                //Cambia el color del fondo de la respuesta seleccionada y de su texto

                                for (int i = 0; i < respuestasLayouts.size(); i++) {
                                    respuestasLayouts.get(i).setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_3)));
                                    txt_respuestas.get(i).setTextColor(Color.parseColor(App.txt_menuBtn_3_color));
                                }

                                v.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));

                                ViewGroup group = (ViewGroup) v;
                                TextView txt = (TextView) group.getChildAt(0);
                                txt.setTextColor(Color.parseColor(App.colorNombreApp));

                            }
                        });

                        respuestasLayouts.add(layout_respuesta);

                    } while (respuestas.moveToNext());

                    contenedor_encuesta_vigente.addView(contenedor_respuestas, interfaz.parentContent());

                }


                final Button btn_enviar = interfaz.crear_Button(App.txt_info_boton_enviar, App.colorFondoMenuBt_3, App.txt_menuBtn_3_color);
                btn_enviar.setGravity(Gravity.CENTER);

                contenedor_principal.addView(btn_enviar, interfaz.contentContent());

                //Centra el boton de enviar en la pantalla
                LinearLayout.LayoutParams params = (LinearLayout.LayoutParams) btn_enviar.getLayoutParams();
                params.gravity = Gravity.CENTER;
                params.topMargin = 10;


                btn_enviar.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {

                        int seleccion = Encuesta.respuesta_seleccionada;
                        if (seleccion > 0) {

                            contenedor_principal.removeView(btn_enviar);

                            //Muestra en pantalla un mensaje de enviadno informacion
                            LinearLayout cargando = interfaz.crear_ProgressBarLinearLayout(interfaz.crear_TextView(App.txt_info_enviando, App.txt_menuBtn_3_color, 15), LinearLayout.HORIZONTAL);
                            contenedor_principal.addView(cargando, interfaz.parentContent());
                            //Envia la respuesta del usuario al servidor
                            new EnviarRespuesta(cargando, layout).execute((seleccion), id_encuesta);
                        }
                    }
                });


            } /*FIN - ELSE*/


        } /* FIN - IF*/
        else{
            contenedor_principal.setVisibility(View.GONE);
        }

        db.close();

    }


    public void cargarEncuestasArchivadas(int layout) {

        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();
        final ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);

        final ViewGroup contenedor_principal = (ViewGroup) activity.findViewById(layout);

        //Obtiene las encuestas archivadas
        Cursor encuestas = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_encuestas + " WHERE estado='" + Encuesta.ESTADO_ARCHIVADO + "'ORDER BY id DESC LIMIT " + (App.encuestas_cargadas) + "," + App.encuestas_cantidad_a_cargar, null);

        if (encuestas.moveToFirst()) {

            Point pantalla = Util.obtenerTamanoPantalla(activity);
            int n = 0;
            LinearLayout fila = null;

            LinearLayout contenedor = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.VERTICAL);
            contenedor.setPadding(0, 0, 0, 5);

            do {
                App.encuestas_cargadas++;
                n++;
                final String titulo = encuestas.getString(2);
                final int id_encuesta=encuestas.getInt(encuestas.getColumnIndex("id_encuesta"));
                final String fecha=encuestas.getString(encuestas.getColumnIndex("fecha"));

                if (n % 2 != 0) {
                    fila = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.HORIZONTAL);
                    fila.setPadding(0, 5, 0, 0);
                }

                LinearLayout layout_encuesta = interfaz.crear_LinearLayout(interfaz.crear_Gradient(GradientDrawable.Orientation.BOTTOM_TOP, App.colorBarraApp,Util.oscurecerColor(App.colorBarraApp,10)), LinearLayout.VERTICAL);
                layout_encuesta.setGravity(Gravity.CENTER);
                layout_encuesta.setMinimumHeight(100);
                TextView nombre_encuesta = interfaz.crear_TextView(titulo, App.colorFondoMenuBt_3, 16);
                layout_encuesta.addView(nombre_encuesta, interfaz.parentContentAlign(Gravity.CENTER));

                layout_encuesta.setPadding(5, 5, 5, 5);

                fila.addView(layout_encuesta, new LinearLayout.LayoutParams(pantalla.x / 2, LinearLayout.LayoutParams.MATCH_PARENT, Gravity.CENTER));

                interfaz.establecerMargenes(layout_encuesta, 0, 0, 5, 0);


                layout_encuesta.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent intent=new Intent(activity, VerEncuestaActivity.class);
                        intent.putExtra("id_encuesta",id_encuesta);
                        intent.putExtra("titulo",titulo);
                        intent.putExtra("fecha",fecha);
                        activity.startActivity(intent);
                    }
                });


                if (n % 2 != 0)
                    contenedor.addView(fila, interfaz.parentContent());

            } while (encuestas.moveToNext());

            contenedor_principal.addView(contenedor, interfaz.parentContent());

        }else{
            Cursor encuesta_vigente=obtenerEncuestaVigente(db);

            if(!encuesta_vigente.moveToFirst() && contenedor_principal.getChildCount()==1){
                contenedor_principal.removeAllViews();
                LinearLayout msj=interfaz.crear_mensajeLogo(App.txt_info_no_hay_contenido_vuelve_mas_tarde, App.txt_menuBtn_3_color);
                msj.setGravity(Gravity.CENTER);
                contenedor_principal.addView(msj,interfaz.parentContentAlign(Gravity.CENTER));

            }else{

                if(activity.findViewById(App.id_mensaje_no_hay_contenido)==null) {
                    TextView msj = interfaz.crear_TextView(App.txt_info_no_hay_contenido, App.txt_menuBtn_3_color, 15);
                    msj.setId(App.id_mensaje_no_hay_contenido);
                    msj.setGravity(Gravity.CENTER);
                    msj.setPadding(5, 5, 5, 5);
                    contenedor_principal.addView(msj, interfaz.parentContentAlign(Gravity.CENTER));
                }
            }
        }

        db.close();
    }

    public void cargarResultados(int layout, int id_encuesta, String titulo_encabezado) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();
        final ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);

        final ViewGroup contenedor_principal = (ViewGroup) activity.findViewById(layout);
        contenedor_principal.removeAllViews();

        Cursor encuesta = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_encuestas + " WHERE id_encuesta='" + id_encuesta + "'", null);

        if (encuesta.moveToFirst()) {

            String titulo = encuesta.getString(2);
            String descripcion = encuesta.getString(3);

            /**ENCABEZADO DE SECCION**/

            LinearLayout layout_header = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.VERTICAL);
            layout_header.setPadding(10, 10, 10, 10);
            TextView txt_encabezado = interfaz.crear_TextView(titulo_encabezado, App.colorFondoMenuBt_3, 20, Typeface.BOLD);
            txt_encabezado.setGravity(Gravity.CENTER);
            layout_header.addView(txt_encabezado, interfaz.parentContentAlign(Gravity.CENTER));

            contenedor_principal.addView(layout_header, interfaz.parentContentAlign(Gravity.CENTER));

            /***********************************************/

            LinearLayout contenedor_encuesta_vigente = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.VERTICAL);
            contenedor_principal.addView(contenedor_encuesta_vigente, interfaz.parentContent());


            TextView txt_titulo = interfaz.crear_TextView(titulo, App.txt_menuBtn_3_color, 18, Typeface.BOLD);

            contenedor_encuesta_vigente.addView(txt_titulo, interfaz.parentContent());
            interfaz.establecerMargenes(txt_titulo, 16, 16, 16, 5);
            //Descripcion de la encuesta
            if (descripcion.length() > 0) {
                TextView txt_descripcion = interfaz.crear_TextView(descripcion, App.txt_menuBtn_3_color, 14);
                txt_descripcion.setPadding(0, 0, 0, 5);
                contenedor_encuesta_vigente.addView(txt_descripcion, interfaz.parentContent());
                interfaz.establecerMargenes(txt_descripcion, 5, 5, 5, 5);
            }

            LinearLayout layout_msj_resultados = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.HORIZONTAL);

            TextView txt_mensaje_resultados = interfaz.crear_TextView(App.txt_info_resultados, App.colorFondoMenuBt_3, 14);
            txt_mensaje_resultados.setPadding(5, 5, 5, 5);
            txt_mensaje_resultados.setGravity(Gravity.RIGHT);
            txt_mensaje_resultados.setBackground(new ColorDrawable(Color.parseColor(App.txt_menuBtn_3_color)));

            layout_msj_resultados.addView(txt_mensaje_resultados, interfaz.parentContentAlign(Gravity.RIGHT));
            contenedor_encuesta_vigente.addView(layout_msj_resultados, interfaz.contentContentAlign(Gravity.RIGHT));

            Cursor respuestas = obtenerRespuesta(db, id_encuesta);

            //Carga en pantalla las respuestas posibles de la encuesta
            if (respuestas.moveToFirst()) {

                int totalVotos = totalVotos(db, id_encuesta);
                Point pantalla = Util.obtenerTamanoPantalla(activity);
                int n = 0;
                LinearLayout fila = null;
                LinearLayout contenedor_respuestas = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.VERTICAL);
                contenedor_respuestas.setPadding(0, 0, 0, 5);
                do {

                    n++;
                    String nombre = respuestas.getString(3);
                    int porcentaje=0;

                    if(totalVotos>0)
                    porcentaje = ((respuestas.getInt(respuestas.getColumnIndex("total")) * 100) / totalVotos);

                    if (n % 2 != 0) {
                        fila = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.HORIZONTAL);
                        fila.setPadding(0, 5, 0, 0);
                    }

                    LinearLayout layout_respuesta = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.VERTICAL);
                    layout_respuesta.setGravity(Gravity.CENTER);
                    layout_respuesta.addView(interfaz.crear_TextView(nombre, App.txt_menuBtn_3_color, 16), interfaz.parentContentAlign(Gravity.CENTER));

                    LinearLayout layout_barra = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3, LinearLayout.HORIZONTAL);
                    layout_barra.setPadding(5, 5, 5, 5);

                    //Texto que muestra el porcentaje de la respuesta
                    TextView txt_porcentaje = interfaz.crear_TextView(porcentaje + "%", App.txt_menuBtn_3_color, 12);
                    layout_barra.addView(txt_porcentaje, interfaz.contentContent());
                    interfaz.establecerMargenes(txt_porcentaje, 0, 0, 10, 0);
                    //Barra de progreso que indica el porcentaje de la respuesta
                    layout_barra.addView(interfaz.crear_ProgressBarHorizontal(100, porcentaje, App.colorBarraApp), interfaz.parentContent());

                    layout_respuesta.addView(layout_barra, interfaz.parentContent());

                    layout_respuesta.setPadding(5, 5, 5, 5);

                    if (respuestas.getCount() % 2 != 0 && respuestas.isLast()) {
                        fila.addView(layout_respuesta, new LinearLayout.LayoutParams(pantalla.x, LinearLayout.LayoutParams.MATCH_PARENT, Gravity.CENTER));
                    } else
                        fila.addView(layout_respuesta, new LinearLayout.LayoutParams(pantalla.x / 2, LinearLayout.LayoutParams.MATCH_PARENT, Gravity.CENTER));

                    interfaz.establecerMargenes(layout_respuesta, 0, 0, 5, 0);


                    if (n % 2 != 0)
                        contenedor_respuestas.addView(fila, interfaz.parentContent());

                } while (respuestas.moveToNext());

                contenedor_encuesta_vigente.addView(contenedor_respuestas, interfaz.parentContent());
            }
        }

        db.close();
    }

    //Verifica que si hay respuestas represedas por enviar al servidor, si es asi, envia la respuesta
    private void verificarRepresamiento(SQLiteDatabase db){
        Cursor respuesta=db.rawQuery("SELECT * FROM "+ControladorBaseDatos.tabla_encuestas_respuestas+" WHERE estado='"+Encuesta.ESTADO_RESPUESTA_CONTESTADO+"'",null);

        if(respuesta.moveToFirst()){
            Cursor respuestas_aux=db.rawQuery("SELECT * FROM "+ControladorBaseDatos.tabla_encuestas_respuestas+" WHERE id_encuesta='"+respuesta.getInt(respuesta.getColumnIndex("id_encuesta"))+"'",null);
            if(respuestas_aux.moveToFirst()){
                int n=0;
                do{
                    n++;
                    if (respuesta.getInt(0)==respuestas_aux.getInt(0)) {
                        if(Conexion.verificar(activity))
                        new EnviarRespuesta(null,0,false).execute(n,respuesta.getInt(respuesta.getColumnIndex("id_encuesta")));
                    }
                }while(respuestas_aux.moveToNext());
            }
        }
    }


    private class EnviarRespuesta extends AsyncTask<Integer, Void, Void> {

        LinearLayout layout;
        int contenedorPrincipal;
        private int id_encuesta;
        private boolean adicionarRespuesta = false;//Indica si la respuesta se debe adicional localmente

        EnviarRespuesta(LinearLayout layout, int contenedorPrincipal) {
            this.layout = layout;
            this.contenedorPrincipal = contenedorPrincipal;
            this.adicionarRespuesta = true;
        }

        EnviarRespuesta(LinearLayout layout, int contenedorPrincipal, boolean adicionarRespuesta) {
            this.layout = layout;
            this.contenedorPrincipal = contenedorPrincipal;
            this.adicionarRespuesta = adicionarRespuesta;
        }

        public void onPostExecute(Void unused) {

            //Muestra en pantalla un mensaje indicando que la respuesta fue enviada
            // y muestra un boton para ver los resultados actuales de la encuesta constestada

            if(layout!=null) {

                layout.removeAllViews();
                layout.setOrientation(LinearLayout.VERTICAL);
                layout.setPadding(0, 10, 0, 0);
                ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);
                TextView msj_enviado = interfaz.crear_TextView(App.txt_info_respuesta_enviada, App.txt_menuBtn_3_color, 18);
                msj_enviado.setGravity(Gravity.CENTER);
                layout.addView(msj_enviado, interfaz.parentContent());
                interfaz.establecerMargenes(msj_enviado, 0, 5, 0, 0);
                Button boton_ver = interfaz.crear_Button(App.txt_info_ver_resultados, App.colorNombreApp, App.colorBarraApp);
                boton_ver.setGravity(Gravity.CENTER);
                layout.addView(boton_ver, interfaz.contentContent());
                interfaz.establecerMargenes(boton_ver, 0, 10, 0, 0);

                boton_ver.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        cargarResultados(contenedorPrincipal, id_encuesta, App.txt_info_encabezado_encuesta_vigente);
                    }
                });

            }
        }

        @Override
        protected Void doInBackground(Integer... params) {

            int respuesta = params[0];

            int id_encuesta = params[1];

            this.id_encuesta = id_encuesta;

            String id_dispositivo = App.obtenerIdDispositivo(activity);

            String[][] datos = new String[4][2];

            //datos ordanos en un array para enviar al servidor
            datos[0][0] = "key_app";
            datos[0][1] = App.keyApp;
            datos[1][0] = "respuesta";
            datos[1][1] = String.valueOf(respuesta);
            datos[2][0] = "id_encuesta";
            datos[2][1] = String.valueOf(id_encuesta);
            datos[3][0] = "id_dispositivo";
            datos[3][1] = id_dispositivo;


            ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
            SQLiteDatabase db = dbc.getReadableDatabase();


            int estado_respuesta = 0;

            /* Si existe la conexion a internet se envia los datos la respuesta del usuario al servidor, si no,
            /*se almacena en la base de datos local del dispositivo para posteriormente enviarlo cuando se establesca
            /* una conexion a internet.
             */
            if (Conexion.verificar(activity)) {
                JSONObject enviar = Conexion.conectar(App.URL_ENVIAR_ENCUESTA_RESPUESTA, datos);
                estado_respuesta = Encuesta.ESTADO_RESPUESTA_CONTESTADO_Y_ENVIADO;
            } else {
                estado_respuesta = Encuesta.ESTADO_RESPUESTA_CONTESTADO;
            }

            //Obtiene las respuestas de la encuesta
            Cursor resp = obtenerRespuesta(db, id_encuesta);

            //Establece localmente la respuesta del usuario en la encuesta
            if (resp.moveToFirst()) {
                int n = 1;
                do {
                    if (n == respuesta) {
                        if (adicionarRespuesta)
                            db.execSQL("UPDATE " + ControladorBaseDatos.tabla_encuestas_respuestas + " SET estado='" + estado_respuesta + "', total='" + (resp.getInt(resp.getColumnIndex("total")) + 1) + "' WHERE id=" + resp.getInt(0) + "");
                        else
                            db.execSQL("UPDATE " + ControladorBaseDatos.tabla_encuestas_respuestas + " SET estado='" + estado_respuesta + "' WHERE id=" + resp.getInt(0) + "");
                    }
                    n++;
                } while (resp.moveToNext());
            }


            db.close();
            return null;
        }
    }

}
