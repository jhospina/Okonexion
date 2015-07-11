package com.appsthergo.instytul.metro.nombreapp;

import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.webkit.WebView;
import android.widget.ScrollView;
import android.widget.TextView;

import libreria.sistema.App;
import libreria.tipos_contenido.Institucional;


public class VerInstitucionalActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ver_institucional);
        Bundle datos=getIntent().getExtras();
        mostrar(datos.getInt("id_institucional"));
    }


    public void mostrar(int id_institucional){

        //Obtiene los datos de la institucional a mostar
        Institucional institucional=Institucional.buscar(this,id_institucional);
        ScrollView contenedor=(ScrollView) findViewById(R.id.scroll_contenedor_ver_institucional);
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_1)));

        if(institucional!=null) {

            App.establecerBarraAccion(this,institucional.titulo);

            TextView titulo = (TextView) findViewById(R.id.txt_titulo_institucional);

            titulo.setText(institucional.titulo);
            titulo.setTextColor(Color.parseColor(App.txt_menuBtn_1_color));

            TextView fecha = (TextView) findViewById(R.id.txt_fecha_institucional);
            fecha.setText(App.txt_info_fecha_publicacion +" "+institucional.fecha);
            fecha.setTextColor(Color.parseColor(App.txt_menuBtn_1_color));

            WebView descripcion = (WebView) findViewById(R.id.web_descripcion_institucional);
            descripcion.loadData(institucional.descripcion,"text/html", "ISO-8859-1");
            descripcion.setBackgroundColor(Color.parseColor(App.colorFondoMenuBt_1));
        }
    }
}
