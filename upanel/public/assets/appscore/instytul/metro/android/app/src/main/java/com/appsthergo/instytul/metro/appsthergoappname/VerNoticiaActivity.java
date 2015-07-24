package com.appsthergo.instytul.metro.appsthergoappname;

import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.ScrollView;
import android.widget.TextView;

import libreria.complementos.Util;
import libreria.conexion.CargarImagen;
import libreria.conexion.Conexion;
import libreria.sistema.App;
import libreria.tipos_contenido.Noticias;


public class VerNoticiaActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Bundle datos = getIntent().getExtras();
        if (datos != null) {
            if (datos.containsKey("id_noticia")) {
                setContentView(R.layout.activity_ver_noticia);
                int id_noticia=(datos.getInt("id_noticia")>0)?datos.getInt("id_noticia"):Integer.parseInt(getIntent().getStringExtra("id_noticia"));

                mostrarNoticia(id_noticia);
            }
        }
    }


    public void mostrarNoticia(int id_noticia) {

        //Obtiene los datos de la noticia a mostar
        Noticias noticia = Noticias.buscar(this, id_noticia);
        ScrollView contenedor = (ScrollView) findViewById(R.id.scroll_contenedor_ver_noticia);
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));

        if (noticia != null) {

            App.establecerBarraAccion(this, noticia.titulo);

            TextView titulo = (TextView) findViewById(R.id.txt_titulo_noticia);


            titulo.setText(noticia.titulo);
            titulo.setTextColor(Color.parseColor(App.txt_menuBtn_2_color));

            TextView fecha = (TextView) findViewById(R.id.txt_fecha_noticia);
            fecha.setText(App.txt_info_fecha_publicacion + " " + noticia.fecha);
            fecha.setTextColor(Color.parseColor(App.txt_menuBtn_2_color));

            WebView descripcion = (WebView) findViewById(R.id.web_descripcion_noticia);
            descripcion.loadData(noticia.descripcion, "text/html", "ISO-8859-1");
            descripcion.setBackgroundColor(Color.parseColor(App.colorFondoMenuBt_2));


            //Carga y muestra la imagen de la noticia, si existe y tiene conexion a internet
            if (noticia.urlImagen.length() > 0 && Conexion.verificar(this)) {
                ImageView imagen = (ImageView) findViewById(R.id.img_ver_noticia);

                int ancho_pantalla = Util.obtenerTamanoPantalla(this).x;

                if (ancho_pantalla >= App.W_IMAGEN_NOTICIA) {
                    imagen.getLayoutParams().width = App.W_IMAGEN_NOTICIA;
                    imagen.getLayoutParams().height = App.H_IMAGEN_NOTICIA;
                } else {
                    int altura_imagen = Math.round((App.H_IMAGEN_NOTICIA * ancho_pantalla) / App.W_IMAGEN_NOTICIA);
                    imagen.getLayoutParams().width = ancho_pantalla;
                    imagen.getLayoutParams().height = altura_imagen;
                }

                new CargarImagen(this, noticia.urlImagen, imagen).execute();
            }
        }
    }
}
