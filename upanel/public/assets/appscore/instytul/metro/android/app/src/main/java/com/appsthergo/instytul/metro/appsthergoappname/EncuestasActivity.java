package com.appsthergo.instytul.metro.appsthergoappname;

import android.graphics.Color;
import android.graphics.Point;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Gravity;
import android.view.MotionEvent;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import libreria.complementos.Mensaje;
import libreria.complementos.Util;
import libreria.conexion.Conexion;
import libreria.conexion.DescargarEncuesta;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.tipos_contenido.Encuesta;


public class EncuestasActivity extends ActionBarActivity {


    ScrollView scroll;
    ComponenteInterfaz interfaz;
    Encuesta encuesta;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_encuestas);

        init();

        if (!Conexion.verificar(this)) {
            ((LinearLayout)findViewById(R.id.lay_indicadorCarga_encuesta_vigente)).setVisibility(View.GONE);
            Mensaje.toast(this,App.txt_info_titulo_sin_conexion);
            Encuesta encuesta=new Encuesta(this);
            encuesta.cargarEncuestaVigente(R.id.lay_encuesta_vigente);
            encuesta.cargarEncuestasArchivadas(R.id.lay_encuestas_archivadas);
        } else {
            App.descarga_iniciada = true;
            new DescargarEncuesta(this,R.id.lay_indicadorCarga_encuesta_vigente,R.id.lay_encuesta_vigente, DescargarEncuesta.Tipo.vigente).execute();

            LinearLayout indicador=interfaz.crear_ProgressBarLinearLayout(null, LinearLayout.HORIZONTAL);
            //Muestra en pantalla el indicador de carga
            ((LinearLayout)EncuestasActivity.this.findViewById(R.id.lay_encuestas_archivadas)).addView(indicador,interfaz.parentContentAlign(Gravity.CENTER));

            new DescargarEncuesta(EncuestasActivity.this,indicador,R.id.lay_encuestas_archivadas,DescargarEncuesta.Tipo.historial).execute();
          }

        cargarMas();

        //Envia información de actividad al servidor
        Thread hilo = new Thread(new Runnable() {
            @Override
            public void run() {
                Conexion.registrarActividad(EncuestasActivity.this, Encuesta.iden);
            }
        });
        hilo.start();
    }

    public void init(){

        scroll=(ScrollView)findViewById(R.id.scroll_encuestas);
        scroll.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_3)));

        App.establecerBarraAccion(this, App.txt_menuBtn_3);
        App.encuestas_cargadas=0;
        interfaz=new ComponenteInterfaz(this);

        TextView txt_cargando=(TextView) findViewById(R.id.txt_buscando_encuesta_vigente);
        txt_cargando.setText(App.txt_info_buscando_encuesta_vigente);
        txt_cargando.setTextColor(Color.parseColor(App.txt_menuBtn_3_color));

        encuesta=new Encuesta(this);

        LinearLayout layout_header = interfaz.crear_LinearLayout(App.txt_menuBtn_3_color, LinearLayout.VERTICAL);
        layout_header.setPadding(10, 10, 10, 10);
        TextView txt_encabezado = interfaz.crear_TextView(App.txt_info_historial_encuestas, App.colorFondoMenuBt_3, 20, Typeface.BOLD);
        txt_encabezado.setGravity(Gravity.CENTER);
        layout_header.addView(txt_encabezado, interfaz.parentContentAlign(Gravity.CENTER));

        ((LinearLayout)findViewById(R.id.lay_encuestas_archivadas)).addView(layout_header, interfaz.parentContentAlign(Gravity.CENTER));

    }


    public void cargarMas() {

        final LinearLayout contenedor = (LinearLayout) findViewById(R.id.contenedor_encuestas);
        final Point pantalla= Util.obtenerTamanoPantalla(this);

        final LinearLayout indicador=interfaz.crear_ProgressBarLinearLayout(null, LinearLayout.HORIZONTAL);

        scroll.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {

                int acerc=((contenedor.getHeight() - scroll.getHeight())-(pantalla.y/App.FACTOR_ACERCAMIENTO_SCROLL));

                //Calcula si el scroll ha llegado al final y carga mas contenido si existe
                if (scroll.getScrollY() >= acerc) {

                    if (Conexion.verificar(EncuestasActivity.this)) {
                        if (!App.descarga_iniciada) {
                            App.descarga_iniciada = true;
                            //Muestra en pantalla el indicador de carga
                            ((LinearLayout)EncuestasActivity.this.findViewById(R.id.lay_encuestas_archivadas)).addView(indicador,interfaz.parentContentAlign(Gravity.CENTER));
                            new DescargarEncuesta(EncuestasActivity.this,indicador,R.id.lay_encuestas_archivadas,DescargarEncuesta.Tipo.historial).execute();
                        }
                    }else{
                        encuesta.cargarEncuestasArchivadas(R.id.lay_encuestas_archivadas);
                    }
                }
                return false;
            }
        });
    }


}
