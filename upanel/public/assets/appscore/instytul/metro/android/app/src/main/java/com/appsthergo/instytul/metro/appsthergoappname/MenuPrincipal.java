package com.appsthergo.instytul.metro.appsthergoappname;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TextView;

import org.json.JSONObject;

import java.util.Random;

import libreria.complementos.Util;
import libreria.conexion.Conexion;
import libreria.servicios.ServicioNoticias;
import libreria.sistema.App;
import libreria.sistema.AppConfig;
import libreria.sistema.AppMeta;
import libreria.tipos_contenido.Institucional;
import libreria.tipos_contenido.Noticias;


public class MenuPrincipal extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_principal);
        App.establecerBarraAccion(this,null);
        establecerApariencia();

        Thread hilo = new Thread(new Runnable() {
            @Override
            public void run() {
                registrarInstalacion();
            }
        });
        hilo.start();

    }

    private void establecerApariencia(){
        //Obtiene el componente Layout del Boton 1 "Institucional"
        LinearLayout layoutMenu1 = (LinearLayout) findViewById(R.id.lay_menu1);
        //Obtiene el componente Layout del Boton 2 "Noticias"
        LinearLayout layoutMenu2 = (LinearLayout) findViewById(R.id.lay_menu2);
        //Obtiene el componente Layout del Boton 3 "Encuestas"
        LinearLayout layoutMenu3 = (LinearLayout) findViewById(R.id.lay_menu3);
        //Obtiene el componente Layout del Boton 4 "PQR"
        LinearLayout layoutMenu4 = (LinearLayout) findViewById(R.id.lay_menu4);


        //Institucional
        layoutMenu1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {



                Intent intent=new Intent(MenuPrincipal.this,InstitucionalActivity.class);
                startActivity(intent);
            }
        });

        //Noticias
        layoutMenu2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(MenuPrincipal.this,NoticiasActivity.class);
                startActivity(intent);
            }
        });

        //Encuestas
        layoutMenu3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(MenuPrincipal.this,EncuestasActivity.class);
                startActivity(intent);
            }
        });

        //PQR
        layoutMenu4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(MenuPrincipal.this,PqrActivity.class);
                startActivity(intent);
            }
        });

        TableLayout contenedor=(TableLayout)findViewById(R.id.contenedor_menu_principal);
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));

        layoutMenu1.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_1)));
        layoutMenu2.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));
        layoutMenu3.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_3)));
        layoutMenu4.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));

        TextView txt_menu_1=(TextView)findViewById(R.id.txt_menu1);
        TextView txt_menu_2=(TextView)findViewById(R.id.txt_menu2);
        TextView txt_menu_3=(TextView)findViewById(R.id.txt_menu3);
        TextView txt_menu_4=(TextView)findViewById(R.id.txt_menu4);

        txt_menu_1.setText(App.txt_menuBtn_1);
        txt_menu_2.setText(App.txt_menuBtn_2);
        txt_menu_3.setText(App.txt_menuBtn_3);
        txt_menu_4.setText(App.txt_menuBtn_4);

        //Color del texto de los botones del menu
        txt_menu_1.setTextColor(Color.parseColor(App.txt_menuBtn_1_color));
        txt_menu_2.setTextColor(Color.parseColor(App.txt_menuBtn_2_color));
        txt_menu_3.setTextColor(Color.parseColor(App.txt_menuBtn_3_color));
        txt_menu_4.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
    }


    private void registrarInstalacion(){

        if(!Conexion.verificar(this))
            return;

        String regInstalacion="instalacion_"+App.obtenerIdDispositivo(this);

        if(AppMeta.findByClave(this,regInstalacion)!=null)
           return;

        String fecha=Util.obtenerFechaActual();;

        AppMeta meta=new AppMeta(this);

        meta.setClave(regInstalacion);
        meta.setValor(fecha);
        meta.save();

        String[][] datos = new String[3][2];
        datos[0][0] = "key_app";
        datos[0][1] = App.keyApp;
        datos[1][0] = "clave";
        datos[1][1] = regInstalacion;
        datos[2][0] = "valor";
        datos[2][1] =  fecha;

        Conexion.conectar(App.URL_META_REGISTRAR, datos);
    }


}



