package com.appsthergo.instytul.metro.appsthergoappname;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.Point;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.view.MotionEvent;
import android.view.View;
import android.widget.ScrollView;
import android.widget.TableLayout;
import android.widget.TableRow;

import libreria.complementos.Mensaje;
import libreria.complementos.Util;
import libreria.conexion.Conexion;
import libreria.conexion.DescargarNoticias;
import libreria.servicios.ServicioNoticias;
import libreria.sistema.App;
import libreria.tipos_contenido.Noticias;


public class NoticiasActivity extends ActionBarActivity {

   ScrollView scroll = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_noticias);

        scroll = (ScrollView) findViewById(R.id.scroll_noticias);

        App.establecerBarraAccion(this, App.txt_menuBtn_2);

        init();


        if (!App.noticias_descargadas) {

            if (!Conexion.verificar(this)) {
                Mensaje.alerta(this, App.txt_info_titulo_sin_conexion, App.txt_info_descripcion_sin_conexion);
                cargarNoticias();
                App.noticias_descargadas = true;
            } else {
                ProgressDialog progress = new ProgressDialog(this);
                progress.setMessage(App.txt_info_cargando);
                new DescargarNoticias(progress, this, R.id.tab_contenedor_noticias).execute();
            }

        } else {
            cargarNoticias();
            App.noticias_descargadas = true;
        }

        cargarMasNoticias();

        startService(new Intent(this,
                ServicioNoticias.class));


        //Envia información de actividad al servidor
        Thread hilo = new Thread(new Runnable() {
            @Override
            public void run() {
                Conexion.registrarActividad(NoticiasActivity.this, Noticias.iden);
            }
        });
        hilo.start();
    }


    public void init(){
        scroll.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));
        App.noticias_cargadas=0;
    }


    public void cargarMasNoticias() {
        final TableLayout tabla = (TableLayout) findViewById(R.id.tab_contenedor_noticias);
        final TableRow loading = (TableRow) findViewById(R.id.cargar_noticias);
        loading.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));

        final Point pantalla= Util.obtenerTamanoPantalla(this);

        scroll.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {

                int acerc=((tabla.getHeight() - scroll.getHeight())-(pantalla.y/App.FACTOR_ACERCAMIENTO_SCROLL));

                //Calcula si el scroll ha llegado al final y carga mas contenido si existe
                if (scroll.getScrollY() >= acerc) {

                    if (Conexion.verificar(NoticiasActivity.this)) {
                        if (!App.descarga_iniciada) {
                            App.descarga_iniciada = true;
                            tabla.removeView(loading);
                            tabla.addView(loading);
                            loading.setVisibility(View.VISIBLE);
                            new DescargarNoticias(loading, NoticiasActivity.this, R.id.tab_contenedor_noticias).execute();
                        }
                    }else{
                        cargarNoticias();
                    }
                }
                return false;
            }
        });
    }


    public void cargarNoticias(){
        Noticias noticias = new Noticias(this);
        noticias.cargar(R.id.tab_contenedor_noticias);
    }

}



