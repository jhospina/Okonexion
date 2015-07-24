package com.appsthergo.instytul.metro.appsthergoappname;

import android.app.ProgressDialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.widget.ScrollView;

import libreria.complementos.Mensaje;
import libreria.conexion.Conexion;
import libreria.conexion.DescargarInstitucional;
import libreria.sistema.App;
import libreria.tipos_contenido.Encuesta;
import libreria.tipos_contenido.Institucional;


public class InstitucionalActivity extends ActionBarActivity {

   ScrollView scroll=null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_institucional);

        scroll = (ScrollView) findViewById(R.id.scroll_institucional);
        scroll.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_1)));

        App.establecerBarraAccion(this, App.txt_menuBtn_1);

        init();

        if (!App.institucional_descargadas) {

            if (!Conexion.verificar(this)) {
                Mensaje.alerta(this, App.txt_info_titulo_sin_conexion, App.txt_info_descripcion_sin_conexion);
                cargarInstitucional();
                App.institucional_descargadas = true;
            } else {
                ProgressDialog progress = new ProgressDialog(this);
                progress.setMessage(App.txt_info_cargando);
                new DescargarInstitucional(progress, this, R.id.tab_contenedor_institucional).execute();
            }

        } else {
            cargarInstitucional();
            App.institucional_descargadas = true;
        }

        //Envia información de actividad al servidor
        Thread hilo = new Thread(new Runnable() {
            @Override
            public void run() {
                Conexion.registrarActividad(InstitucionalActivity.this, Institucional.iden);
            }
        });
        hilo.start();

    }


    public void init(){
        App.institucional_cargadas=0;
    }




    public void cargarInstitucional(){
        Institucional institucional = new Institucional(this);
        institucional.cargar(R.id.tab_contenedor_institucional);
    }

}



