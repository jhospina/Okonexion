package com.appsthergo.instytul.metro.nombreapp;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TextView;

import com.appsthergo.instytul.metro.nombreApp.pqr.PQR_PeticionesActivity;
import com.appsthergo.instytul.metro.nombreApp.pqr.PQR_QuejasActivity;
import com.appsthergo.instytul.metro.nombreApp.pqr.PQR_ReclamosActivity;
import com.appsthergo.instytul.metro.nombreApp.pqr.PQR_SugerenciasActivity;

import libreria.complementos.Util;
import libreria.conexion.ComunicacionPQR;
import libreria.conexion.Conexion;
import libreria.sistema.App;


public class PqrActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pqr_menu);

        App.establecerBarraAccion(this, App.txt_menuBtn_4);

        establecerApariencia();

        if(Conexion.verificar(this)) {
            ComunicacionPQR com = new ComunicacionPQR(this);
            com.execute();
        }
    }


    private void establecerApariencia(){
        //Obtiene el componente Layout del Boton 1 "Peticiones"
        LinearLayout layoutBtn1 = (LinearLayout) findViewById(R.id.lay_btn1_pqr);
        //Obtiene el componente Layout del Boton 2 "Quejas"
        LinearLayout layoutBtn2 = (LinearLayout) findViewById(R.id.lay_btn2_pqr);
        //Obtiene el componente Layout del Boton 3 "Reclamos"
        LinearLayout layoutBtn3 = (LinearLayout) findViewById(R.id.lay_btn3_pqr);
        //Obtiene el componente Layout del Boton 4 "Sugerencias"
        LinearLayout layoutBtn4 = (LinearLayout) findViewById(R.id.lay_btn4_pqr);

        TableLayout contenedor=(TableLayout)findViewById(R.id.contenedor_pqr);
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.txt_menuBtn_4_color)));

        layoutBtn1.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));
        layoutBtn2.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));
        layoutBtn3.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));
        layoutBtn4.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));

        //Peticiones
        layoutBtn1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(PqrActivity.this, PQR_PeticionesActivity.class);
                startActivity(intent);
            }
        });

        //Quejas
        layoutBtn2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(PqrActivity.this, PQR_QuejasActivity.class);
                startActivity(intent);
            }
        });

        //Reclamos
        layoutBtn3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(PqrActivity.this, PQR_ReclamosActivity.class);
                startActivity(intent);
            }
        });

        //Sugerencias
        layoutBtn4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(PqrActivity.this, PQR_SugerenciasActivity.class);
                startActivity(intent);
            }
        });


        TextView txt_menu_1=(TextView)findViewById(R.id.txt_btn1_pqr);
        TextView txt_menu_2=(TextView)findViewById(R.id.txt_btn2_pqr);
        TextView txt_menu_3=(TextView)findViewById(R.id.txt_btn3_pqr);
        TextView txt_menu_4=(TextView)findViewById(R.id.txt_btn4_pqr);

        txt_menu_1.setText(App.txt_info_pqr_peticiones);
        txt_menu_2.setText(App.txt_info_pqr_quejas);
        txt_menu_3.setText(App.txt_info_pqr_reclamos);
        txt_menu_4.setText(App.txt_info_pqr_sugerencias);

        //Color del texto de los botones
        txt_menu_1.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        txt_menu_2.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        txt_menu_3.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        txt_menu_4.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));


        ImageView img_btn1=(ImageView) findViewById(R.id.img_pqr_btn_1);
        ImageView img_btn2=(ImageView) findViewById(R.id.img_pqr_btn_2);
        ImageView img_btn3=(ImageView) findViewById(R.id.img_pqr_btn_3);
        ImageView img_btn4=(ImageView) findViewById(R.id.img_pqr_btn_4);


        if(Util.esColorOscuro(App.colorFondoMenuBt_4))
        {
            img_btn1.setImageResource(R.mipmap.img_peticion_white);
            img_btn2.setImageResource(R.mipmap.img_quejas_white);
            img_btn3.setImageResource(R.mipmap.img_reclamos_white);
            img_btn4.setImageResource(R.mipmap.img_sugerencias_white);
        }else{
            img_btn1.setImageResource(R.mipmap.img_peticion_black);
            img_btn2.setImageResource(R.mipmap.img_quejas_black);
            img_btn3.setImageResource(R.mipmap.img_reclamos_black);
            img_btn4.setImageResource(R.mipmap.img_sugerencias_black);
        }


    }


}
