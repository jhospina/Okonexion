package com.appsthergo.instytul.metro.appsthergoappname;

import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.ScrollView;

import libreria.sistema.App;
import libreria.tipos_contenido.Encuesta;


public class VerEncuestaActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ver_encuesta);


        ScrollView scroll=(ScrollView)findViewById(R.id.scroll_encuestas);
        scroll.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_3)));


        Bundle datos=getIntent().getExtras();
        int id_encuesta=datos.getInt("id_encuesta");
        String titulo=datos.getString("titulo");
        String fecha=datos.getString("fecha");

        App.establecerBarraAccion(this, titulo);

        Encuesta encuesta=new Encuesta(this);
        encuesta.cargarResultados(R.id.contenedor_verencuesta,id_encuesta,fecha);

    }


}
